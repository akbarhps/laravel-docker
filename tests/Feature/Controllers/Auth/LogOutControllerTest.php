<?php

namespace Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogOutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config("auth.provider") == ["keycloak"]) {
            $this->markTestSkipped("This test is not applicable for keycloak provider");
        }
    }

    public function test_signOut_with_signed_in_user_should_regenerate_session()
    {
        $user = User::factory()->create();
        auth()->login($user);

        $response = $this->get(route('auth.logout'));
        $response->assertRedirect();
        $this->assertGuest();
    }
}
