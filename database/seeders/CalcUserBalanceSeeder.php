<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Transaction;
use App\Models\UserBalance;
use App\Repositories\TransactionRepository;

class CalcUserBalanceSeeder extends Seeder
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $balanceType = UserBalance::TYPE_BASE;
        // Для каждого пользователя создается актуальный на данный момент баланс
        $users->each(function ($user) use($balanceType) {
            $totalBalance = $this->transactionRepository->calculateBalanceForUser($user->id, $balanceType);
            // Создаем или обновляем запись в таблице балансов
            UserBalance::updateOrCreate([
                'user_id' => $user->id,
                'type' => $balanceType
            ], [
                'balance' => $totalBalance
            ]);
        });
    }
}
