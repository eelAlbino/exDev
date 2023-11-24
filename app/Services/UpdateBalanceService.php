<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserBalance;
use App\Models\Transaction;
use App\Repositories\UserBalanceRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdateBalanceService
{

    private User $user;

    private UserBalanceRepository $userBalanceRepository;

    private TransactionRepository $transactionRepository;

    function __construct(User $user, ?UserBalanceRepository $userBalanceRepository = null, ?TransactionRepository $transactionRepository = null)
    {
        $this->user = $user;
        $this->userBalanceRepository = $userBalanceRepository ?? new UserBalanceRepository;
        $this->transactionRepository = $transactionRepository ?? new TransactionRepository;
    }

    /**
     * Обновляет баланс пользователя. При отсутствии баланса в системе создается новый баланс
     * 
     * @param string $type
     * @param float $amount
     * @param string $description
     * @param string $balanceType
     * @throws Exception
     * @return Transaction|NULL
     */
    public function process(string $type, float $amount, string $description = '', ?string $balanceType = null): ?Transaction
    {
        if (!$balanceType) {
            $balanceType = UserBalance::TYPE_BASE;
        }
        // Пересчет и добавление транзакции осуществляется атомарно
        return DB::transaction(function () use ($type, $amount, $description, $balanceType): Transaction {
            $amount = abs($amount);
            if ($type == Transaction::TYPE_CREDIT) {
                $amount *= -1;
            }
            $currentBalance = $this->userBalanceRepository->getByUserID($this->user->id);
            $curBalanceVal = $currentBalance ? $currentBalance->balance : 0;
            if ($curBalanceVal <= 0 && $amount < 0) {
                throw new Exception('Баланс пользователя отрицательный. Разрешено только пополнение баланса');
            }
            $totalBalanceVal = $curBalanceVal + $amount;
            if ($totalBalanceVal < 0) {
                throw new Exception('Недостаточно средств на счете. Баланс не может стать отрицательным.');
            }
            $balance = UserBalance::updateOrCreate([
                'user_id' => $this->user->id,
                'type' => $balanceType
            ], [
                'balance' => $totalBalanceVal
            ]);
            return (new TransactionRepository)->create([
                'user_id' => $this->user->id,
                'balance_id' => $balance->id,
                'type' => $type,
                'amount' => abs($amount),
                'description' => $description
            ]);
        });
    }
}