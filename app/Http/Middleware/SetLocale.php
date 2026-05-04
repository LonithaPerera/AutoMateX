<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Prefer DB-persisted locale (logged-in users), fall back to session, then app default
        if ($request->user() && $request->user()->locale) {
            $locale = $request->user()->locale;
        } else {
            $locale = session('locale', config('app.locale'));
        }

        if (in_array($locale, ['en', 'si', 'ta'])) {
            App::setLocale($locale);
            // Keep session in sync
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
