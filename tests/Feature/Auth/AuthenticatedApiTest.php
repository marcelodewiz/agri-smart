<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticatedApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token']);
    }

    public function test_userLoginInvalid(){
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Email ou senha invalidos.']);
    }

    public function test_user_can_logout()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/logout');

        $response->assertOk()
            ->assertJson(['message' => 'Deslogado com sucesso.']);
    }

    public function test_validRegisterRequest(){
        $this->withoutExceptionHandling();

        $this->postJson(route('auth.register'), [
            'name' => 'Marcelo',
            'email' => 'marcelo@teste.com.br',
            'password' => '123456',
            'password_confirmation' => '123456',
            'nameToken' => 'teste_token'
        ])
            ->assertCreated();

        $this->assertDatabaseHas('users',['name' => 'Marcelo']);

        $response = $this->postJson(route('auth.login'), [
            'email' => 'marcelo@teste.com.br',
            'password' => '123456',
        ])
            ->assertOk();
    }
}
