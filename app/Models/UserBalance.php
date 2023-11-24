<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    use HasFactory;

    /**
     * Базовый тип баланса
     * @var string
     */
    const TYPE_BASE = 'base';

    protected $fillable = [
        'user_id',
        'type',
        'balance'
    ];

    /**
     * Пользователь
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
