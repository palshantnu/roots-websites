<?php

namespace App\Http\Controllers\Api;

use App\Enums\SampleType;
use App\Http\Controllers\Controller;
use App\Http\Resources\SampleResource;
use App\Models\Sample;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SampleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'type' => ['nullable', Rule::enum(SampleType::class)],
        ]);

        $site = $this->site($request);

        $samples = Sample::forSite($site)
            ->published()
            ->when(
                isset($validated['type']) ? SampleType::from($validated['type']) : null,
                fn ($query, SampleType $type) => $query->ofType($type)
            )
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get()
            ->each(fn (Sample $sample) => $sample->setRelation('site', $site));

        return SampleResource::collection($samples);
    }

    public function show(Request $request, string $slug): SampleResource
    {
        return new SampleResource($this->findPublishedSample($request, $slug));
    }

    /**
     * Streams the document inline so PDFs open in the browser.
     */
    public function file(Request $request, string $slug): StreamedResponse
    {
        $sample = $this->findPublishedSample($request, $slug);

        abort_unless($sample->hasDocument(), 404);

        return Storage::disk(Sample::DOCUMENT_DISK)->response(
            $sample->file_path,
            $sample->downloadName(),
            $this->documentHeaders($sample),
        );
    }

    public function download(Request $request, string $slug): StreamedResponse
    {
        $sample = $this->findPublishedSample($request, $slug);

        abort_unless($sample->hasDocument(), 404);

        return Storage::disk(Sample::DOCUMENT_DISK)->download(
            $sample->file_path,
            $sample->downloadName(),
            $this->documentHeaders($sample),
        );
    }

    private function findPublishedSample(Request $request, string $slug): Sample
    {
        $site = $this->site($request);

        return Sample::forSite($site)
            ->published()
            ->where('slug', $slug)
            ->firstOrFail()
            ->setRelation('site', $site);
    }

    /**
     * @return array<string, string>
     */
    private function documentHeaders(Sample $sample): array
    {
        return [
            'Content-Type' => Sample::DOCUMENT_MIME_TYPES[$sample->fileExtension()] ?? 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
        ];
    }
}
