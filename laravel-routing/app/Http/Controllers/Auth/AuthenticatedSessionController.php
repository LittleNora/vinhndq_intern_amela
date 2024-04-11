<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    private $guard = 'web';

    public function __construct()
    {
        $routeName = explode('.', request()->route()->getName());

        $this->guard = count($routeName) > 1 ? $routeName[0] : 'web';
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view($this->renderViewOrRoute('auth.login'), ['guard' => $this->guard]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate($this->guard);

//        $request->session()->regenerate();

        return redirect()->intended(route($this->renderViewOrRoute('dashboard')));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard($this->guard)->logout();

//        $request->session()->invalidate();
//
//        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function renderViewOrRoute($viewOrRoute): string
    {
        return $this->guard == 'web' ? $viewOrRoute : "{$this->guard}.{$viewOrRoute}";
    }
}
