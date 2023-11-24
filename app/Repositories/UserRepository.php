<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Получение пользователя по id
     * @param int $id
     * @return User|NULL
     */
    public function getByID(int $id): ?User
    {
        return User::find($id) ?? null;
    }

    /**
     * Получение пользователя по логину
     * @param string $login
     * @return User|NULL
     */
    public function getByLogin(string $login): ?User
    {
        return User::where([
            'login' => $login
        ])->first() ?? null;
    }

    /**
     * Получение всех пользователей, у который отсутствует баланс
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\User[]
     */
    public function getUsersWithoutBalance(): Collection
    {
        return User::whereDoesntHave('balances')->get();
    }

    /**
     * Есть ли пользователи с указанным логином
     * @param string $login
     * @return bool
     */
    public function existsByLogin(string $login): bool
    {
        return User::where('login', $login)->exists();
    }

    /**
     * Есть ли пользователи с указанным email
     * @param string $login
     * @return bool
     */
    public function existsByEmail(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    /**
     * Создание пользователя
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }
}
