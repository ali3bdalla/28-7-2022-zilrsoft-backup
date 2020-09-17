<?php

namespace App\Console\Commands\Transaction;

use App\Http\Requests\Accounting\Transaction\DeleteTransactionsRequest;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Illuminate\Console\Command;
use Illuminate\Http\Response;

class DeleteTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to delete transactions';

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
        $transactionsContainersArray = [14453,14462,14241,14185,14175,14169,14182,13931,13885];

        foreach ($transactionsContainersArray as $id)
        {
            $container = TransactionsContainer::find($id);
            if(!empty($container))
                $this->delete($container);


        }
    }

    private function delete(TransactionsContainer $transactionsContainer)
    {
        $request = new DeleteTransactionsRequest();

        $response = $request->erase($transactionsContainer);

        if($response instanceof  Response)
        {
            if($response->status() == 200)
                echo  $transactionsContainer->id .' has been deleted\n';
            else
                echo  $transactionsContainer->id .' error '. $response->getContent() . '\n';
        }
    }
}
