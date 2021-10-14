<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

use App\Models\Order;
use App\Models\Customer;


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
        $marketplaceBaseURL = env('DESPATCH_CLOUD_MARKETPLACE_API_URL');
        $marketplaceAPIKEY = env('DESPATCH_CLOUD_MARKETPLACE_API_KEY');
        $apiEndpoint = env('DESPATCH_CLOUD_MARKETPLACE_ORDER_ENDPOINT');

        $url = $marketplaceBaseURL . $apiEndpoint . '?api_key=' . $marketplaceAPIKEY;

        Log::channel('api')->info('An informational message.');

        $response =  Http::get($url)->throw(function ($response, $e) {
            //
        })->json();

        if( isset($response['data']) && count($response['data']) ){
            dd($response);
        }

        // $this->info($response);
        return Command::SUCCESS;
    }
}
