<?php

namespace App\Mail;

use App\Customer;
use App\CustomerAddress;
use App\Http\Controllers\OrderController;
use App\Order;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrepareOrder extends Mailable
{
    use Queueable, SerializesModels;
    private $order_id;
    private $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id,$pdf)
    {
        $this->order_id = $order_id;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Prepare Order";
        $data['order'] = Order::findOrFail($this->order_id);
        $data['discounts'] = json_decode($data['order']->customer->group->discounts, true);
        $data['products'] =  $data['order']->getInventoryProducts();
        $data['enviromental_tax'] = OrderController::$environmental_tax;
        $data['customer'] = Customer::findOrFail($data['order']['customer_id']);
        $data['customer_address'] = CustomerAddress::find($data['order']['address_id']);
        $data['store_info'] = array(
            'logo'          =>'',
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
        return $this->view('invoice.inventory')
            ->with($data)
            ->from('info@dndcom.gr')
            ->subject("Prepare Order ".$data['customer']['customer_name'])
            ->attachData($this->pdf, 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
