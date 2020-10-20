<?php

namespace App\Jobs\User\Balance;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateClientBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $amount, $client, $type;

    /**
     * Create a new job instance.
     *
     * @param User $client
     * @param $amount
     * @param string $type
     */
    public function __construct(User $client, $amount, $type = 'increase')
    {
        $this->client = $client;
        $this->amount = $amount;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $balance = $this->client->balance;
        if ($this->type == 'increase') {
            $newBalance = (float)$balance + (float)$this->amount;
        } else {
            $newBalance = (float)$balance - (float)$this->amount;
        }
        $this->client->update([
            'balance' => $newBalance
        ]);
    }
}
