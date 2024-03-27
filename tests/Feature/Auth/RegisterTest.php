<?php

namespace Tests\Feature\Auth;

use App\Models\Administrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a guest can register an administrator.
     */
    public function test_guest_can_register_administrator()
    {
        $requestBody = [
            'email' => 'user@example.com',
            'password' => 'password',
        ];

        $this->postJson(route('api.auth.register'), $requestBody)
            ->assertCreated();

        $this->assertDatabaseHas(Administrator::class, [
            'email' => $requestBody['email'],
        ]);
    }

    /**
     * Test a guest cannot register an administrator with an existing email.
     */
    public function test_guest_cannot_register_administrator_with_existing_email()
    {
        $administrator = Administrator::factory()->create();

        $requestBody = [
            'email' => $administrator->email,
            'password' => 'password',
        ];

        $this->postJson(route('api.auth.register'), $requestBody)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    /**
     * Test a guest cannot register an administrator with an invalid email.
     */
    public function test_guest_cannot_register_administrator_with_invalid_email()
    {
        $requestBody = [
            'email' => 'invalid-email',
            'password' => 'password',
        ];

        $this->postJson(route('api.auth.register'), $requestBody)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    /**
     * Test a guest cannot register an administrator with a password less than 8 characters.
     */
    public function test_guest_cannot_register_administrator_with_password_less_than_8_characters()
    {
        $requestBody = [
            'email' => 'user@example.com',
            'password' => 'pass',
        ];

        $this->postJson(route('api.auth.register'), $requestBody)
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    /**
     * Test a guest cannot register an administrator with a password greater than 32 characters.
     */
    public function test_guest_cannot_register_administrator_with_password_greater_than_32_characters()
    {
        $requestBody = [
            'email' => 'user@example.com',
            'password' => 'passwordpasswordpasswordpasswordpassword',
        ];

        $this->postJson(route('api.auth.register'), $requestBody)
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }
}
