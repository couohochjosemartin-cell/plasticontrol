<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_la_raiz_redirige_al_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_el_login_carga_correctamente(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }
}