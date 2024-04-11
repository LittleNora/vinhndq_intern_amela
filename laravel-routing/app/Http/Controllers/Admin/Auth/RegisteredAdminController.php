<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrators;
use App\Models\Author;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredAdminController extends Controller
{

    private $prefix;

    public function __construct(
        private Administrators $model
    )
    {
        $this->prefix = 'admin';
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view("{$this->prefix}.auth.register");
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . $this->model::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin = $this->model::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($admin));

        Auth::guard('admin')->login($admin);

        return redirect(route("{$this->prefix}.dashboard", absolute: false));
    }
}
