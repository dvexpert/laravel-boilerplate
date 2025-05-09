<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Inertia\{Inertia, Response};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{RedirectResponse, Request};

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password page.
     */
    public function show(): Response
    {
        return Inertia::render('auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        throw_unless(Auth::guard('web')->validate([
            'email'    => $request->user()->email,
            'password' => $request->password,
        ]), ValidationException::withMessages([
            'password' => __('auth.password'),
        ]));

        $request->session()->put('auth.password_confirmed_at', Carbon::now()->getTimestamp());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
