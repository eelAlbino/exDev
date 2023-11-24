<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Тип операции кредит (вычитание)
     * @var string
     */
    const TYPE_CREDIT = 'credit';

    /**
     * Тип операции дебет (прибавление)
     * @var string
     */
    const TYPE_DEBIT = 'debit';

    protected $fillable = [
        'user_id',
        'balance_id',
        'type',
        'amount',
        'description'
    ];

    /**
     * Типы транзакций
     * 
     * @return array
     */
    public static function types(): array
    {
        return [
            'credit' => static::TYPE_CREDIT,
            'debit' => static::TYPE_DEBIT
        ];
    }

    /**
     * Пользователь
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Баланс
     */
    public function balance()
    {
        return $this->belongsTo(UserBalance::class);
    }
}
