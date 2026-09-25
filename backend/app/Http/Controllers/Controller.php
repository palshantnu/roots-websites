<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ResolveSite;
use App\Models\Site;
use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * The website the current API route belongs to (set by the `site` middleware).
     */
    protected function site(Request $request): Site
    {
        $site = $request->attributes->get(ResolveSite::ATTRIBUTE);

        abort_unless($site instanceof Site, 404);

        return $site;
    }
}
