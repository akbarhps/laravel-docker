<?php

namespace Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config("auth.provider") == ["keycloak"]) {
            $this->markTestSkipped("This test is not applicable for keycloak provider");
        }
    }

    private function register(array $body): TestResponse
    {
        return $this->post(route('auth.register.store'), $body);
    }

    public function test_signUp_with_invalid_input_should_return_errors()
    {
        $response = $this->register(['name' => '', 'email' => '', 'password' => '']);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_signUp_with_existing_email_should_return_email_error()
    {
        $user = User::factory()->create();

        $response = $this->register(['name' => 'John Doe', 'email' => $user->email, 'password' => 'password']);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_signUp_with_non_existing_roleId_should_return_roleId_error()
    {
        $response = $this->register([
            'name' => 'John Doe',
            'email' => 'jondo@jon.com',
            'password' => 'password',
            'role_id' => 'INVALID_ROLE',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['role_id']);
    }

    public function test_signUp_with_valid_request_should_return_signed_in_user()
    {
        $response = $this->register([
            'name' => 'John Doe',
            'email' => 'jondo@jon.com',
            'password' => 'password',
            'role_id' => Role::factory()->create()->id,
        ]);
        $response->assertRedirect();
        $this->assertAuthenticated();
    }
}
