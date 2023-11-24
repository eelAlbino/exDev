<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UpdateBalanceService;

class UpdateUserBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    private string $type;

    private float $amount;

    private string $description;

    public function __construct(int $userID, string $type, float $amount, string $description = '')
    {
        $this->user = (new UserRepository)->getByID($userID);
        $this->type = $type;
        $this->amount = $amount;
        $this->description = $description;
    }

    public function handle()
    {
        (new UpdateBalanceService($this->user))->process($this->type, $this->amount, $this->description);
    }
}
