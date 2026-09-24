<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    public function store(StoreNewsletterSubscriberRequest $request): JsonResponse
    {
        NewsletterSubscriber::firstOrCreate(['email' => $request->validated('email')]);

        return response()->json(['message' => 'You are subscribed to the RTS journal.'], 201);
    }
}
