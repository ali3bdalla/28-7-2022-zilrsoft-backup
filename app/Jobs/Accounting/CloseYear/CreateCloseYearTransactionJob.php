<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCloseYearTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account
     */
    private $account;
    private $balance;
    /**
     * @var array
     */
    private $options;
    private $loggedUser;
    private $entity;

    public function __construct($entity,Account $account, $balance,$loggedUser, $options = [])
    {
        //
        $this->account = $account;
        $this->balance = $balance;
        $this->options = $options;
        $this->loggedUser = $loggedUser;
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transactionType = $this->account->type;
        if ($this->balance < 0) {
            $transactionType = $this->account->type == 'debit' ? 'credit' : 'debit';
        }
        $this->entity->transactions()->create(array_merge($this->options, [
            'account_id' => $this->account->id,
            'amount' => abs($this->balance),
            'creator_id' => $this->loggedUser->id,
            'organization_id' => $this->loggedUser->organization_id,
            'type' => $transactionType,
            'created_at' => $this->entity->created_at,
            'updated_at' => $this->entity->updated_at
        ]));
    }
}
