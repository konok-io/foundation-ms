<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->with('error', 'Your account is not active. Please contact administrator.');
            }

            $user->update([
                'last_login' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            Log::info('User logged in', ['user_id' => $user->id, 'ip' => $request->ip()]);

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->with('error', 'The provided credentials do not match our records.')->withInput($request->only('email'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Log::info('User logged out', ['user_id' => Auth::id()]);
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
