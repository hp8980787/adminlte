<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShippedMailToCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $order;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $logistics = $this->order->logistics;
        return $this->view('email.orders.shipped')->with([
            'trans_id' => $this->order->trans_id,
            'order_number' => $this->order->order_number,
            'ship_no' => $this->order->ship_no,
            'url'=>$logistics->url,
        ]);
    }
}
