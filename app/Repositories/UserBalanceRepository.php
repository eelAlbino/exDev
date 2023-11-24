<?php
namespace App\Repositories;

use App\Models\UserBalance;

class UserBalanceRepository
{
    /**
     * Получение баланса пользователя по его ID и типу баланса
     * 
     * @param int $userID
     * @param string|null $type
     * @return \App\Models\UserBalance
     */
    public function getByUserID(int $userID, ?string $type = null): UserBalance
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
}