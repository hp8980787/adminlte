<?php

namespace App\Listeners;

use App\Events\UpdateOrder;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateOrderShippingStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UpdateOrder  $event
     * @return void
     */
    public function handle(UpdateOrder $event)
    {
        $ids = $event->orders;
        $orders = Order::query()->with('products')->whereIn('id', $ids)->get();
        foreach ($orders as $order) {
            $is_shipping = [];
            $products = $order->products;
            foreach ($products as $product) {
                if ($product->stock >= $product->pivot->quantity) {
                    $is_shipping[] = true;
                } else {
                    $is_shipping[] = false;
                }

            }

            $judge = (bool)array_product($is_shipping);

            $order->is_shipping = $judge ? 1 : 0;
            $order->save();
        }
    }
}
