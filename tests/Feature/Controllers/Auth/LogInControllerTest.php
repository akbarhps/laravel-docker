<?php

namespace Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LogInControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config("auth.provider") == ["keycloak"]) {
            $this->markTestSkipped("This test is not applicable for keycloak provider");
        }
    }

    private function logIn($data): TestResponse
    {
        return $this->post(route('auth.login.store'), $data);
    }

    public function test_signIn_with_invalid_input_should_return_errors()
    {
        $response = $this->logIn(['email' => '', 'password' => '']);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_signIn_non_existing_user_should_return_email_error()
    {
        $response = $this->logIn(['email' => 'not_exists@not_exists.com', 'password' => 'not_exists']);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_signIn_existing_user_with_invalid_password_should_return_password_error()
    {
        $user = User::factory()->create();

        $response = $this->logIn(['email' => $user->email, 'password' => 'invalid_password']);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['password']);
    }

    public function test_signIn_keycloak_user_should_return_email_error()
    {
        $user = User::factory()->create(['password' => null]);

        $response = $this->logIn(['email' => $user->email, 'password' => 'password']);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_signIn_brute_forcing_should_rate_limited()
    {
        $user = User::factory()->create();

        for ($i = 0; $i <= 10; $i++) {
            $this->logIn(['email' => $user->email, 'password' => 'INVALID_PASSWORD']);
        }

        $response = $this->logIn(['email' => $user->email, 'password' => $user->password]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_signIn_with_valid_password_should_return_logged_in_session()
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->logIn(['email' => $user->email, 'password' => 'password']);
        $response->assertRedirect();
        $this->assertAuthenticated();
    }
}
