<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add {login} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание пользователя через консоль';

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $login = (string) $this->argument('login');
        $email = (string) $this->argument('email');
        do {
            if (!$this->isValidLogin($login)) {
                $this->error('Параметр login некорретного формата');
            }
            if (!$this->isValidEmail($email)) {
                $this->error('Параметр email некорретного формата');
            }
            // Проверка на уникальность логина и почты
            if ($this->userRepository->existsByLogin($login)) {
                $this->error('Пользователь с таким логином уже существует в системе.');
                break;
            }
            if ($this->userRepository->existsByEmail($email)) {
                $this->error('Пользователь с таким e-mail уже существует в системе.');
                break;
            }
            $password = Str::password(12);
            // Создание пользователя
            $user = $this->userRepository->create([
                'login' => $login,
                'email' => $email,
                'password' => bcrypt($password),
            ]);
            // Вывод информации о пользователе, включая пароль
            $this->info("Создан новый пользователь с ID: {$user->id}");
            $this->info("Логин: {$login}");
            $this->info("Пароль: {$password}");
        } while (false);
    }

    /**
     * Валидация параметра email
     * @param string $email
     * @return bool
     */
    private function isValidEmail(string $email): bool
    {
        $emailValidator = Validator::make([
            'email' => $email
        ], [
            'email' => 'required|email',
        ]);
        
        return !$emailValidator->fails();
    }

    /**
     * Валидация параметра login
     * @param string $login
     * @return bool
     */
    private function isValidLogin(string $login): bool
    {
        $validator = Validator::make([
            'login' => $login
        ], [
            'login' => 'required|string|alpha_num|regex:/^[a-zA-Z0-9]+$/',
        ]);
        
        return !$validator->fails();
    }
}
