<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionListRequest;
use App\Repositories\TransactionRepository;

class TransactionsController extends Controller
{

    protected $transactionRepository;

    /**
     * Контроллер страницы транцакций пользователя
     *
     * @return void
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->middleware('auth');
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Список транзакций текущего пользователя
     * 
     * @param TransactionListRequest $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(TransactionListRequest $request)
    {
        $filter = $request->input('filter');
        if (!is_array($filter)) { 
            $filter = [];
        }
        $filter['user_id'] = Auth::id();
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');
        $sort = [
            $sortBy => $sortOrder
        ];
        
        $transactions = $this->transactionRepository->getBuilder($filter, $sort)->paginate(10);
        return view('transactions.index', compact('transactions', 'sortBy', 'sortOrder', 'filter'));
    }
}
