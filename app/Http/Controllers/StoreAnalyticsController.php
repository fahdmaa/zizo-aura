<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnalyticsEventRequest;
use App\Models\StoreAnalyticsEvent;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class StoreAnalyticsController extends Controller
{
    public function store(StoreAnalyticsEventRequest $request): Response
    {
        $data = $request->validated();
        $path = $data['path'];

        if ($path === '/admin' || Str::startsWith($path, '/admin/')) {
            return response()->noContent();
        }

        StoreAnalyticsEvent::create([
            'event_type' => $data['event'],
            'visitor_hash' => hash_hmac('sha256', $request->session()->getId(), (string) config('app.key')),
            'path' => $path,
            'created_at' => now(),
        ]);

        return response()->noContent();
    }
}
