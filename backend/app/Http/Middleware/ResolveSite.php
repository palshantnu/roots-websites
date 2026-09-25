<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pins a group of API routes to one website, e.g. `->middleware('site:research')`.
 *
 * The site always comes from the route definition and never from user input,
 * so a client cannot ask a Research endpoint for another site's content.
 */
class ResolveSite
{
    public const ATTRIBUTE = 'site';

    public function handle(Request $request, Closure $next, string $code): Response
    {
        $site = Site::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        abort_if($site === null, 404);

        $request->attributes->set(self::ATTRIBUTE, $site);

        return $next($request);
    }
}
