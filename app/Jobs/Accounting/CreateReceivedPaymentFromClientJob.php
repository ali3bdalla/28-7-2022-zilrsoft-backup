<?php

namespace App\Jobs\Accounting;

use App\Jobs\User\Balance\UpdateClientBalanceJob;
use App\Models\Account;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CreateReceivedPaymentFromClientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $client;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $description;
    private $accountId;

    /**
     * Create a new job instance.
     *
     * @param User $client
     * @param int $amount
     * @param $accountId
     * @param string $description
     */
    public function __construct(User $client, $amount = 0, $accountId, $description = "")
    {
        //

        $this->client = $client;
        $this->amount = $amount;
        $this->description = $description;
        $this->accountId = $accountId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clientAccount = Account::getSystemAccount("clients");
        $loggedUser = Auth::user();
        $organizationAccount = Account::findOrFail($this->accountId);
        $container = Entry::create(
            [
                'creator_id' => $loggedUser->id,
                'description' => $this->description,
                'amount' => (float)$this->amount,
                'organization_id' => $loggedUser->organization_id,
            ]
        );
        $organizationAccount->transactions()->create(
            [
                'creator_id' => $loggedUser->id,
                'organization_id' => $loggedUser->organization_id,
                'amount' => (float)$this->amount,
                'user_id' => $this->client->id,
                'description' => 'client_balance',
                'container_id' => $container->id,
                'type' => 'debit',

            ]
        );

        $clientAccount->transactions()->create(
            [
                'creator_id' => $loggedUser->id,
                'organization_id' => $loggedUser->organization_id,
                'amount' => (float)$this->amount,
                'user_id' => $this->client->id,
                'container_id' => $container->id,
                'description' => 'client_balance',
                'type' => 'credit',
            ]
        );
        UpdateClientBalanceJob::dispatchSync($this->client, $this->amount, 'decrease');
        $organizationAccount->payments()->create(
            [
                'creator_id' => $loggedUser->id,
                'organization_id' => $loggedUser->organization_id,
                'user_id' => $this->client->id,
                'amount' => $this->amount,
                'slug' => "",
                'description' => $this->description,
                'payment_type' => "receipt",
            ]
        );
    }
}
