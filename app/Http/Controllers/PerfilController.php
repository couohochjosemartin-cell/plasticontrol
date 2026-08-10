<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function editPassword(): View
    {
        return view('perfil.password');
    }

    public function updatePassword(
        UpdatePasswordRequest $request
    ): RedirectResponse {
        $request->user()->update([
            'password' => Hash::make(
                $request->validated('password')
            ),
        ]);

        return redirect()
            ->route('perfil.password.edit')
            ->with(
                'estado',
                'Contraseña actualizada correctamente.'
            );
    }
}