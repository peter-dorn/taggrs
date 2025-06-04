<?php

namespace Database\Factories;

use App\Models\Tracking;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackingFactory extends Factory
{
    protected $model = Tracking::class;

    public function definition(): array
    {
        return [
            'event' => $this->faker->randomElement(['login', 'logout', 'create', 'update', 'delete']),
            'timestamp' => $this->faker->dateTimeBetween('-1 month', 'now')
        ];
    }

    /**
     * Indicate that the tracking is for a login event.
     */
    public function login(): static
    {
        return $this->state(fn (array $attributes) => [
            'event' => 'login',
        ]);
    }

    /**
     * Indicate that the tracking is for a logout event.
     */
    public function logout(): static
    {
        return $this->state(fn (array $attributes) => [
            'event' => 'logout',
        ]);
    }
}
