<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->firstName;
        return [
            'name' => $name,
            'email' => strtolower($name).'_'.strtolower(fake()->lastName).'@example.com',
            'phone' => '+380'. random_int(100000000, 999999999),
            'photo' => fake()->imageUrl(70, 70),
            'position_id' => Position::query()->inRandomOrder()->value('id')
        ];
    }

}
