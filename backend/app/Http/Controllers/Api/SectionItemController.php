<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SectionItemResource;
use App\Models\SectionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionItemController extends Controller
{
    /**
     * Published section items grouped by section key, e.g.
     * `{ "data": { "hero_stats": [...], "process_steps": [...] } }`.
     * Pass `?section=key` to receive only that group.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section' => ['nullable', 'string', 'max:100'],
        ]);

        $items = SectionItem::forSite($this->site($request))
            ->published()
            ->when($validated['section'] ?? null, fn ($query, string $section) => $query->where('section', $section))
            ->orderBy('section')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => $items
                ->groupBy('section')
                ->map(fn ($group) => SectionItemResource::collection($group)->resolve($request)),
        ]);
    }
}
