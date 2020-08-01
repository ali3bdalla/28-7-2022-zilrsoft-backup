<?php

namespace Modules\Users\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateUserBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $balanceType;
    /**
     * @var string
     */
    private $updatetype;
    /**
     * @var int
     */
    private $amount;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $balanceType
     * @param string $updatetype
     * @param int $amount
     */
    public function __construct(User $user,$balanceType = 'client_balance',$updatetype='increase',$amount=0)
    {
        //
        $this->user = $user;
        $this->balanceType = $balanceType;
        $this->updatetype = $updatetype;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if($this->balanceType =='client_balance')
        {

            $newBalance = $this->updatetype == 'increase'  ? $this->user->balance + $this->amount :$this->user->balance - $this->amount;
            $this->user->update([
                'balance' =>  $newBalance
             ]);
        }else
        {
            $newBalance = $this->updatetype == 'increase'  ? $this->user->vendor_balance + (float)$this->amount :$this->user->vendor_balance -  (float)$this->amount;
            $this->user->update([
                'vendor_balance' =>  $newBalance
            ]);
        }

    }
}
