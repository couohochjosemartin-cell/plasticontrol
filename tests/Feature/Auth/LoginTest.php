<?php

namespace Tests\Feature\Auth;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function crearUsuario(
        string $estado = 'Activo'
    ): Usuario {
        $rol = Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Rol de prueba',
        ]);

        return Usuario::create([
            'rol_id' => $rol->id,
            'creado_por_id' => null,
            'nombre_completo' => 'Usuario de Prueba',
            'usuario' => 'adminprueba',
            'password' => Hash::make('Password123'),
            'estado' => $estado,
        ]);
    }

    public function test_usuario_activo_puede_iniciar_sesion(): void
    {
        $usuario = $this->crearUsuario();

        $response = $this->post('/login', [
            'usuario' => 'adminprueba',
            'password' => 'Password123',
        ]);

        $response->assertRedirect(
            route('dashboard')
        );

        $this->assertAuthenticatedAs(
            $usuario
        );
    }

    public function test_contrasena_incorrecta_no_permite_iniciar_sesion(): void
    {
        $this->crearUsuario();

        $response = $this->post('/login', [
            'usuario' => 'adminprueba',
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors(
            'usuario'
        );

        $this->assertGuest();
    }

    public function test_usuario_inactivo_no_puede_iniciar_sesion(): void
    {
        $this->crearUsuario(
            'Inactivo'
        );

        $response = $this->post('/login', [
            'usuario' => 'adminprueba',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors(
            'usuario'
        );

        $this->assertGuest();
    }

    public function test_usuario_no_autenticado_no_puede_entrar_al_dashboard(): void
    {
        $response = $this->get(
            '/dashboard'
        );

        $response->assertRedirect(
            route('login')
        );
    }

    public function test_usuario_puede_cerrar_sesion(): void
    {
        $usuario = $this->crearUsuario();

        $this->actingAs($usuario);

        $response = $this->post(
            '/logout'
        );

        $response->assertRedirect(
            route('login')
        );

        $this->assertGuest();
    }
}