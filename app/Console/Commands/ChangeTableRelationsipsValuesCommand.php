<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Console\Command;

class ChangeTableRelationsipsValuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:change_table_relationships_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        Transaction::where('debitable_type', 'App\Account')->update([
            'debitable_type' => Account::class,
        ]);
        Transaction::where('creditable_type', 'App\Account')->update([
            'creditable_type' => Account::class,
        ]);
        Transaction::where('creditable_type', 'App\Item')->update([
            'creditable_type' => Item::class,
        ]);
        Transaction::where('debitable_type', 'App\Item')->update([
            'debitable_type' => Item::class,
        ]);

        Transaction::where('creditable_type', 'App\User')->update([
            'creditable_type' => User::class,
        ]);
        Transaction::where('debitable_type', 'App\User')->update([
            'debitable_type' => User::class,
        ]);

        foreach (Transaction::where([
            ['debitable_type', Account::class],
            ['creditable_type', null],
        ])->get() as $transaction) {
            $transaction->update([
                'type' => 'debit',
                'account_id' => $transaction->debitable_id,
            ]);
        }

        foreach (Transaction::where([
            ['creditable_type', Account::class],
            ['debitable_type', null],
        ])->get() as $transaction) {
            $transaction->update([
                'type' => 'credit',
                'account_id' => $transaction->creditable_id,
            ]);
        }

        $stockAccount = Account::where('slug', 'stock')->first();

        foreach (Transaction::where([
            ['debitable_type', Item::class],
            // ['creditable_type', null],
        ])->get() as $transaction) {
            $transaction->update([
                'type' => 'debit',
                'account_id' => $stockAccount->id,
                'item_id' => $transaction->debitable_id,

            ]);
        }

        foreach (Transaction::where([
            ['creditable_type', Item::class],
            // ['debitable_type', null],
        ])->get() as $transaction) {
            $transaction->update([
                'type' => 'credit',
                'account_id' => $stockAccount->id,
                'item_id' => $transaction->debitable_id,

            ]);
        }


        $query = Transaction::where([
            ['account_id', 0],
            ['creditable_type', 'App\Models\Account'],
            ['debitable_type', 'App\Models\Account'],

        ]);
        echo $query->count() . "\n";
        foreach ($query->get() as $key => $transaction) {

            if ($transaction->creditable != null && $transaction->debitable != null) {
                echo $transaction->container_id . "\n";

                Transaction::create([
                    'account_id' => $transaction->creditable_id,
                    'amount' => $transaction->amount,
                    'container_id' => $transaction->container_id,
                    'user_id' => $transaction->user_id,
                    'organization_id' => $transaction->organization_id,
                    'type' => 'credit',
                    'is_manual' => $transaction->is_manual,
                    'is_pending' => $transaction->is_pending,
                    'invoice_id' => $transaction->invoice_id,
                    'item_id' => $transaction->item_id,
                    'creator_id' => $transaction->creator_id,
                    'deleted_at' => $transaction->deleted_at,
                ]);

                $transaction->update([
                    'account_id' => $transaction->debitable_id,
                    'type' => 'debit',
                ]);
            }

        }

    }
}
