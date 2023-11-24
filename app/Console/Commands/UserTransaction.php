<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use App\Jobs\UpdateUserBalanceJob;
use App\Models\Transaction;
use Exception;

class UserTransaction extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:transaction {login} {type} {amount} {--description=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проведение операций по балансу пользователя, по логину (начисление/списание) с указанием описания операции. Вывод баланса в минус считается ошибкой. Как я понял задачу, итоговый баланс не должен быть меньше нуля, либо если он уже меньше нуля, не должен уменьшаться.';

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
        $type = (string) $this->argument('type');
        $amount = (float) $this->argument('amount');
        $description = (string) $this->option('description');

        $user = $this->userRepository->getByLogin($login);
        do {
            $types = Transaction::types();
            if (!in_array($type , $types)) {
                $this->error('Некорректное значение параметра type. Возможные значения: '. implode(', ', $types));
                break;
            }
            if ($amount == 0) {
                $this->error('Передан пустое значение поля amount');
                break;
            }
            if (!$user) {
                $this->error('Пользователь по логину"'. $login .'" не найден');
                break;
            }
            
            try {
                // Выполнение проведения транзакции через очередь
                UpdateUserBalanceJob::dispatch($user->id, $type, $amount, $description);
            } catch (Exception $exception) {
                $this->error($exception->getMessage());
            }
            $this->info('Транзакция принята в работу');

        } while (false);
    }

}
