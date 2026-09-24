<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublishEnquiryRequest;
use App\Models\PublishEnquiry;
use Illuminate\Http\JsonResponse;

class PublishEnquiryController extends Controller
{
    public function store(StorePublishEnquiryRequest $request): JsonResponse
    {
        $data = $request->safe()->except('manuscript');

        if ($request->hasFile('manuscript')) {
            $data['manuscript_path'] = $request->file('manuscript')->store('manuscripts', 'local');
        }

        PublishEnquiry::create($data);

        return response()->json(['message' => 'Thank you. A publishing advisor will contact you within one working day.'], 201);
    }
}
