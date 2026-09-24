<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublishEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
            'book_title' => ['nullable', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'string', 'max:100'],
            'word_count' => ['nullable', 'string', 'max:50'],
            'formats' => ['nullable', 'array'],
            'formats.*' => ['string', 'max:100'],
            'manuscript' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
        ];
    }
}
