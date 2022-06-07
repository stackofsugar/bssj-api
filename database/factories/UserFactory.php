<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {
    public function definition() {
        $username = $this->faker->unique()->userName();
        return [
            "username" => $username,
            "fullname" => $this->faker->name(),
            "address" => $this->faker->address(),
            "phone" => $this->faker->e164PhoneNumber(),
            "email" => $this->faker->unique()->safeEmail(),
            "password" => Hash::make($username),
        ];
    }
}
