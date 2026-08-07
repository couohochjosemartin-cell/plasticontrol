<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credenciales = [
            'usuario' => $request->string('usuario')->toString(),
            'password' => $request->string('password')->toString(),
            'estado' => 'Activo',
        ];

        if (! Auth::attempt(
            $credenciales,
            $request->boolean('recordarme')
        )) {
            return back()
                ->withErrors([
                    'usuario' => 'Las credenciales proporcionadas no son válidas.',
                ])
                ->onlyInput('usuario');
        }

        $request->session()->regenerate();

        $request->user()->forceFill([
            'ultimo_acceso_at' => now(),
        ])->save();

        return redirect()->intended(
            route('dashboard')
        );
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('estado', 'Sesión cerrada correctamente.');
    }
}