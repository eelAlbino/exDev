<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\UserBalance;
use App\Repositories\UserRepository;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserBalance>
 */
class UserBalanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::whereDoesntHave('balances')->inRandomOrder()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        return [
            'user_id' => $user->id,
            'balance' => 0,
            'type' => UserBalance::TYPE_BASE
        ];
    }
}
