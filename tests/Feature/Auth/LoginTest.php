<?php

namespace Tests\Feature\Auth;

use App\Models\Administrator;
use App\Services\Auth\TokenService;
use Doctrine\Common\Lexer\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test an administrator can log in.
     */
    public function test_administrator_can_log_in()
    {
        $administrator = Administrator::factory()->create();

        $requestBody = [
            'email' => $administrator->email,
            'password' => 'password',
        ];

        $this->postJson(route('api.login'), $requestBody)
            ->assertOk()
            ->assertJsonStructure(['access_token', 'refresh_token']);
    }

    /**
     * Test an administrator cannot log in with invalid credentials.
     */
    public function test_administrator_cannot_log_in_with_invalid_credentials()
    {
        $administrator = Administrator::factory()->create();

        $requestBody = [
            'email' => $administrator->email,
            'password' => 'invalid-password',
        ];

        $this->postJson(route('api.login'), $requestBody)
            ->assertUnauthorized();
    }

    /**
     * Test an administrator cannot log in with an invalid email.
     */
    public function test_administrator_cannot_log_in_with_invalid_email()
    {
        $requestBody = [
            'email' => 'invalid-email',
            'password' => 'password',
        ];

        $this->postJson(route('api.login'), $requestBody)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    /**
     * Test an administrator cannot log in with a no existing email.
     */
    public function test_administrator_cannot_log_in_with_no_existing_email()
    {
        $requestBody = [
            'email' => 'user@example.com',
            'password' => 'password',
        ];

        $this->postJson(route('api.login'), $requestBody)
            ->assertUnauthorized();
    }

    /**
     * Test an administrator can refresh their token.
     */
    public function test_administrator_can_refresh_token()
    {
        $administrator = Administrator::factory()->create();

        $tokenService = new TokenService();
        $tokens = $tokenService->createToken($administrator);

        $this->actingAs($administrator)
            ->getJson(route('api.refresh-token'), ['Authorization' => 'Bearer '.$tokens['refresh_token']])
            ->assertOk()
            ->assertJsonStructure(['access_token']);
    }
}
