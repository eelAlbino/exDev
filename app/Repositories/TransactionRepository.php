<?php
namespace App\Repositories;

use App\Models\Transaction;
use App\Models\UserBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository
{
    /**
     * Подсчет актуального на данный момент баланса для пользователя
     * @param int $userID
     * @return float
     */
    public function calculateBalanceForUser(int $userID, ?string $balanceType = null): float
    {
        if (!$balanceType) {
            $balanceType = UserBalance::TYPE_BASE;
        }
        $balance = (new UserBalanceRepository)->getByUserID($userID, $balanceType);
        $query = Transaction::where('user_id', $userID)->where('balance_id', $balance->id);
        return (float) $query->sum(DB::raw('CASE WHEN type = "credit" THEN amount ELSE -amount END'));
    }

    /**
     * Создание транзакции
     *
     * @param array $data
     * @return \App\Models\Transaction
     */
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    /**
     * Получение всех транзакций пользователя
     *
     * @param int $userID
     * @param string|null $type
     * @return \App\Models\UserBalance
     */
    public function getByUserID(int $userID): UserBalance
    {
        if (!$type) {
            $type = UserBalance::TYPE_BASE;
        }
        $balance = UserBalance::where('user_id', $userID)->where('type', $type)->first();
        if (!$balance) {
            $balance = UserBalance::create([
                'user_id' => $userID,
                'type' => $type
            ]);
        }
        return $balance;
    }

    /**
     * Получение списка транзакций
     * @param array $filter
     * @param array $sort
     * @return Collection
     */
    public function getBuilder(array $filter, array $sort): Builder
    {
        $query = Transaction::query();
        foreach ($filter as $key => $val) {
            if ($key == 'description') {
                $query->when($val, function ($query) use ($val) {
                    $query->where('description', 'like', '%' . $val . '%');
                });
            }
            else {
                $query->where('user_id', $val);
            }
        }
        foreach ($sort as $key => $val) {
            $query->orderBy($key, $val);
        }
        return $query;
    }
}
