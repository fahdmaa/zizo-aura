<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StoreAnalyticsEvent;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class StoreAnalyticsReport
{
    public function make(int $days): array
    {
        $end = CarbonImmutable::now();
        $start = $end->startOfDay()->subDays($days - 1);
        $events = StoreAnalyticsEvent::query()->whereBetween('created_at', [$start, $end]);
        $orders = Order::query()->whereBetween('created_at', [$start, $end]);

        $metrics = [
            'gmv' => (float) (clone $orders)->where('status', '!=', 'cancelled')->sum('total'),
            'orders' => (clone $orders)->count(),
            'page_views' => (clone $events)->where('event_type', 'page_view')->count(),
            'store_clicks' => (clone $events)->where('event_type', 'store_click')->count(),
            'visitors' => (clone $events)->where('event_type', 'page_view')->distinct()->count('visitor_hash'),
            'whatsapp_clicks' => (clone $events)->where('event_type', 'whatsapp_order_click')->distinct()->count('visitor_hash'),
            'web_checkout_clicks' => (clone $events)->where('event_type', 'web_checkout_click')->distinct()->count('visitor_hash'),
        ];

        $series = collect(CarbonPeriod::create($start->toDateString(), $end->toDateString()))
            ->mapWithKeys(fn ($date) => [$date->toDateString() => [
                'date' => $date->toDateString(), 'page_views' => 0, 'visitors' => 0,
                'store_clicks' => 0, 'whatsapp_clicks' => 0, 'web_checkout_clicks' => 0,
                'orders' => 0, 'gmv' => 0.0,
            ]]);

        $this->addEventSeries($series, $start, $end);
        $this->addOrderSeries($series, $start, $end);

        return [
            'period' => ['days' => $days, 'from' => $start->toDateString(), 'to' => $end->toDateString()],
            'metrics' => $metrics,
            'series' => $series->values(),
        ];
    }

    private function addEventSeries(Collection $series, CarbonImmutable $start, CarbonImmutable $end): void
    {
        $rows = StoreAnalyticsEvent::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, event_type, COUNT(*) as total, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupByRaw('DATE(created_at), event_type')
            ->get();

        foreach ($rows as $row) {
            $day = $row->day;
            if (! isset($series[$day])) continue;
            $point = $series->get($day);
            if ($row->event_type === 'page_view') {
                $point['page_views'] = (int) $row->total;
                $point['visitors'] = (int) $row->visitors;
            } elseif ($row->event_type === 'store_click') {
                $point['store_clicks'] = (int) $row->total;
            } elseif ($row->event_type === 'whatsapp_order_click') {
                $point['whatsapp_clicks'] = (int) $row->visitors;
            } else {
                $point['web_checkout_clicks'] = (int) $row->visitors;
            }
            $series->put($day, $point);
        }
    }

    private function addOrderSeries(Collection $series, CarbonImmutable $start, CarbonImmutable $end): void
    {
        $rows = Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw("DATE(created_at) as day, COUNT(*) as orders, SUM(CASE WHEN status != ? THEN total ELSE 0 END) as gmv", ['cancelled'])
            ->groupByRaw('DATE(created_at)')
            ->get();

        foreach ($rows as $row) {
            if (! isset($series[$row->day])) continue;
            $point = $series->get($row->day);
            $point['orders'] = (int) $row->orders;
            $point['gmv'] = (float) $row->gmv;
            $series->put($row->day, $point);
        }
    }
}
