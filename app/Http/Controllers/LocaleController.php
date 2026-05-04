<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        if (in_array($locale, ['en', 'si', 'ta'])) {
            session(['locale' => $locale]);

            // Persist to database if logged in
            if ($request->user()) {
                $request->user()->update(['locale' => $locale]);
            }
        }

        return redirect()->back();
    }
}
