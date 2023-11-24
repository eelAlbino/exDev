<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Transaction;
use App\Repositories\UserBalanceRepository;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $balance = (new UserBalanceRepository)->getByUserID($user->id);
        return [
            'user_id' => $user->id,
            'balance_id' => $balance->id,
            'type' => $this->faker->randomElement(Transaction::types()),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence
        ];
        
    }
}
