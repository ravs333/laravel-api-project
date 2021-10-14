<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Product;
use App\Models\OrderItems;


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
            $orders = array(
                'inserted' => array(),
                'updated' => array(),
            );

            foreach ($response['data'] as $apiOrder) {
                $order = Order::firstOrNew(['id' => intval($apiOrder['id'])], $apiOrder);

                if( $order->exists ){
                    if($order->type != $apiOrder['type']){
                        $order->type = $apiOrder['type'];
                        $orders['updated'][] = $apiOrder;
                    }
                    
                }else{
                    $apiEndpoint = env('DESPATCH_CLOUD_MARKETPLACE_ORDER_ENDPOINT') . $apiOrder['id'];
                    $url = $marketplaceBaseURL . $apiEndpoint . '?api_key=' . $marketplaceAPIKEY;
                    $orderDetails =  Http::get($url)->throw(function ($response, $e) {
                        //
                    })->json();

                    if( count($orderDetails) ){

                        //Save Customer Details
                        $customerDetails = $orderDetails['customer'];
                        $customer = Customer::firstOrNew(['id' => $customerDetails['id']], $customerDetails);
                        if(!$customer->exists){
                            $customer->id = $customerDetails['id'];
                            $customer->created_at = date('Y-m-d H:i:s', strtotime($customerDetails['created_at']));
                            $customer->updated_at = date('Y-m-d H:i:s', strtotime($customerDetails['updated_at']));
                            $customer->save();
                        }

                        // Save Address 
                        $billingAddressDetails = $orderDetails['billing_address'];
                        $billingAddress = Address::firstOrNew(['id' => $billingAddressDetails['id']], $billingAddressDetails);
                        if(!$billingAddress->exists){
                            $billingAddress->id = $billingAddressDetails['id'];
                            $billingAddress->created_at = date('Y-m-d H:i:s', strtotime($billingAddressDetails['created_at']));
                            $billingAddress->updated_at = date('Y-m-d H:i:s', strtotime($billingAddressDetails['updated_at']));
                            $billingAddress->save();
                        }

                        $shippingAddressDetails = $orderDetails['shipping_address'];
                        $shippingAddress = Address::firstOrNew(['id' => $shippingAddressDetails['id']], $shippingAddressDetails);
                        if(!$shippingAddress->exists){
                            $shippingAddress->id = $shippingAddressDetails['id'];
                            $shippingAddress->created_at = date('Y-m-d H:i:s', strtotime($shippingAddressDetails['created_at']));
                            $shippingAddress->updated_at = date('Y-m-d H:i:s', strtotime($shippingAddressDetails['updated_at']));
                            $shippingAddress->save();
                        }

                        //Save Order Info
                        $order = new Order();
                        $order->id = $apiOrder['id'];
                        $order->payment_method = $apiOrder['payment_method'];
                        $order->shipping_method = $apiOrder['shipping_method'];
                        $order->customer_id = $apiOrder['customer_id'];
                        $order->company_id = $apiOrder['company_id'];
                        $order->type = $apiOrder['type'];
                        $order->billing_address_id = $apiOrder['billing_address_id'];
                        $order->shipping_address_id = $apiOrder['shipping_address_id'];
                        $order->total = $apiOrder['total'];
                        $order->created_at = date('Y-m-d H:i:s', strtotime($apiOrder['created_at']));
                        $order->updated_at = date('Y-m-d H:i:s', strtotime($apiOrder['updated_at']));
                        $order->save();


                        //Save Order Items and Products
                        $orderItems = $orderDetails['order_items'];      
                        
                        foreach ($orderItems as $orderItemDetails) {
                            // Save product
                            $productDetails = $orderItemDetails['product'];
                            $product = Product::firstOrNew(['id' => $productDetails['id']], $productDetails);
                            if(!$product->exists){
                                $product->id = $productDetails['id'];
                                $product->created_at = date('Y-m-d H:i:s', strtotime($productDetails['created_at']));
                                $product->updated_at = date('Y-m-d H:i:s', strtotime($productDetails['updated_at']));
                                $product->save();
                            }

                            // Save Order Item
                            $orderItem = new OrderItems();
                            $orderItem->id = $orderItemDetails['id'];
                            $orderItem->order_id = $orderItemDetails['order_id'];
                            $orderItem->product_id = $orderItemDetails['product_id'];
                            $orderItem->quantity = $orderItemDetails['quantity'];
                            $orderItem->subtotal = $orderItemDetails['subtotal'];
                            $orderItem->created_at = date('Y-m-d H:i:s', strtotime($orderItemDetails['created_at']));
                            $orderItem->updated_at = date('Y-m-d H:i:s', strtotime($orderItemDetails['updated_at']));
                            $orderItem->save();
                        }

                        $orders['inserted'][] = $apiOrder;
                    }
                }

            }
        }

        $this->info('Inserted ' . count($orders['inserted']) . ' Orders.');
        $this->info('Updated ' . count($orders['updated']) . ' Orders.');
        return Command::SUCCESS;
    }
}
