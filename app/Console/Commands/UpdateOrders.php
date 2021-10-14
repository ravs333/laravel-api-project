<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

use App\Models\Order;

class UpdateOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order status from pending to approved';

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
        
        $pendingOrders =  Order::where('type', 'pending')->limit(50)->get()->toArray();

        foreach ($pendingOrders as $pendingOrder) {
            $apiEndpoint = env('DESPATCH_CLOUD_MARKETPLACE_ORDER_ENDPOINT') . $pendingOrder['id'];
            $url = $marketplaceBaseURL . $apiEndpoint . '?api_key=' . $marketplaceAPIKEY;
            $response =  Http::post($url, ['type' => 'approved'])->json();
            Log::channel('api')->info('Update Order API:: ', array(
                'url' => $url,
                'response' => $response
            ));
            if( isset($response['type']) && $response['type'] == 'approved' ){
                Order::where('id', $pendingOrder['id'])
                    ->update(['type' => 'approved']);
            }
        }

        return Command::SUCCESS;
    }
}
