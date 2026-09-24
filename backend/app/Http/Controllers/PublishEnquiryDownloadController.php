<?php

namespace App\Http\Controllers;

use App\Models\PublishEnquiry;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublishEnquiryDownloadController extends Controller
{
    public function __invoke(PublishEnquiry $publishEnquiry): StreamedResponse|Response
    {
        abort_unless($publishEnquiry->manuscript_path, 404);

        $extension = pathinfo($publishEnquiry->manuscript_path, PATHINFO_EXTENSION);
        $name = $publishEnquiry->book_title ? \Illuminate\Support\Str::slug($publishEnquiry->book_title) : 'manuscript';

        return Storage::disk('local')->download(
            $publishEnquiry->manuscript_path,
            "{$name}-manuscript.{$extension}"
        );
    }
}
