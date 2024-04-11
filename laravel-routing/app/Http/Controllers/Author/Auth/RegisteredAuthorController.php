<?php

namespace App\Http\Controllers\Author\Auth;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredAuthorController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('author.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Author::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $author = Author::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($author));

        Auth::guard('author')->login($author);

        dd('Done');

        return redirect(route('author.dashboard', absolute: false));
    }
}
