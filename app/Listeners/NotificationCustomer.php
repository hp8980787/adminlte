<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Mail\ShippedMailToCustomer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotificationCustomer
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
     * @param \App\Events\OrderShipped $event
     * @return void
     */
    public function handle(OrderShipped $event)
    {
        $order = $event->order;
        $user = app()->environment() === 'production' ? $order->email : '643711690@qq.com';
        $message = (new ShippedMailToCustomer($order))->onConnection('redis')->onQueue('emails');
        Mail::to($user)->queue($message);
    }
}
