<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<UserRole>
 */
class UserRoleFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => strtoupper(Str::random(8)),
            'role_id' => Role::factory()->create()->id,
        ];
    }
}
