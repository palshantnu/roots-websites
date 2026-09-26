<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $message = new ContactMessage($request->validated());
        $message->site()->associate($this->site($request));
        $message->save();

        return response()->json(['message' => 'Thank you. We will get back to you within one working day.'], 201);
    }
}
