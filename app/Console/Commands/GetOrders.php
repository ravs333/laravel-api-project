<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Orders List from Marketplace API';

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
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
