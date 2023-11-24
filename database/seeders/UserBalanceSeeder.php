<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Support\Facades\DB;

class UserBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table((new UserBalance)->getTable())->delete();
        $users = User::all();
        $users->each(function ($user) {
            UserBalance::factory()->create([
                'user_id' => $user->id
            ]);
        });
    }
}
