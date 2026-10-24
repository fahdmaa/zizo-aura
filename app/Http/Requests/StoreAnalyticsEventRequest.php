<?php

namespace App\Http\Requests;

use App\Models\StoreAnalyticsEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnalyticsEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event' => ['required', 'string', Rule::in(StoreAnalyticsEvent::TYPES)],
            'path' => ['required', 'string', 'max:255', 'regex:~^/(?!/)[^?#]*$~'],
        ];
    }
}
