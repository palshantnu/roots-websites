<?php

namespace App\Http\Requests;

use App\Http\Middleware\ResolveSite;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Each site's form asks for different details; the site comes from the route.
        $siteRules = match ($this->attributes->get(ResolveSite::ATTRIBUTE)?->code) {
            'research' => [
                'phone' => ['required', 'string', 'max:30'],
                'subject' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:120'],
                'message' => ['nullable', 'string', 'max:5000'],
            ],
            'it' => [
                'company' => ['nullable', 'string', 'max:255'],
                'service' => ['required', 'string', 'max:255'],
                'budget' => ['nullable', 'string', 'max:255'],
            ],
            default => [],
        };

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            ...$siteRules,
        ];
    }
}
