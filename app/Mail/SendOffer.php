<?php

namespace App\Mail;

use App\Customer;
use App\CustomerAddress;
use App\Http\Controllers\OrderController;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

class SendOffer extends Mailable
{
    use Queueable, SerializesModels;
    private $order_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = Order::findOrFail($this->order_id);
        $data['order'] = $order;
        $data['products'] = $data['order']->getFinalizedProducts();
        $data['enviromental_tax'] = OrderController::$environmental_tax;
        $data['customer'] = Customer::findOrFail($data['order']['customer_id']);
        $data['customer_address'] = CustomerAddress::find($data['order']['address_id']);
        $data['store_info'] = array(
            'logo'          => asset('assets/media/logos/tyrepro_logo.png'),
            'name'          => 'Name',
            'owner'         => 'Owner 1',
            'profession'    => 'profession',
            'address'       => 'address',
            'city'          => 'city',
            'phone'         => 'phone',
            'fax'           => '',
            'email'         => 'email',
            'afm'           => 'afm',
            'doy'           => 'doy',
            'argemi'        => 'argemi',
        );
        $pdf = PDF::loadView('order/send', $data);

        return $this->view('order/send')->with($data)->from('info@dndcom.gr')->attachData($pdf->download('offer.pdf'), 'offer.pdf', [
            'mime' => 'application/pdf',
        ]);
    }
}
