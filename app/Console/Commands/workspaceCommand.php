<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class workspaceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:workspace';

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
//        $number = 165.01;
//        echo  abs(round($number) - $number) < 0.01;
        $price = 55;
        $priceWithoutTax = $price /( 1 + 15 / 100 );
        dd($priceWithoutTax);
    }
}
