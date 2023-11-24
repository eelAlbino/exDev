<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TransactionRepository;
use App\Repositories\UserBalanceRepository;

class UserBalanceController extends Controller
{

    public function __construct(private TransactionRepository $transactionRepository, private UserBalanceRepository $userBalanceRepository)
    {
    }

    /**
     * Получение текущих данных по балансу пользователя: размер баланса + 10 последних транзакций
     * @param Request $request
     */
    public function current(Request $request)
    {
        $response = [
            'success' => false
        ];
        do {
            $userID = (int) Auth::id();
            if ($userID <= 0) {
                $response['errors'][]= [
                    'mess' => 'Необходима авторизация'
                ];
                break;
            }
            $balance = $this->userBalanceRepository->getByUserID($userID);
            $transactions = $this->transactionRepository->getBuilder([
                    'user_id' => $userID
                ], [
                    'id' => 'desc'
                ])->take(10)
                ->get();
            $response['success'] = true;
            $response['payload'] = [
                'balance' => $balance,
                'transactions' => $transactions
            ];
        } while (false);
        return response()->json($response);
    }
}
