<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RECEIPT</title>
    <link rel="shortcut icon" id="favicon" href="">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;

        }
        .m-0 {
            margin: 0px;
        }
        .bold {
            font-weight: 700;
        }
    </style>
</head>

<body class="customers viewinvoice">

<table width="100%">
    <tbody>
    <tr>
        <td>
            <img style="width:120px;margin-bottom:50px;" src="{{ $store_info['logo'] }}" class="img-responsive" alt="{{ $store_info['name'] }}">
        </td>
        <td style="text-align:right;">
            <p style="font-size:20px;font-weight:bold;" class="m-0">RECEIPT</p>
            <p class="m-0" style="color:#666;font-weight:700;"></p>
            <p style="color: orange;margin:0px;text-transform:uppercase;">PRINTED</p>
        </td>
    </tr>

    <tr>
        <td>
            <address class="invoice-html-company-info">
                <b style="color:black" class="company-name-formatted">{{$store_info['name']}}</b>
                <br>{{ $store_info['profession'] }}<br>ΤΗΛ {{ $store_info['phone'] }}
                <br>{{$store_info['address']}}<br>{{$store_info['city']}}<br>
                <br>EMAIL: {{ $store_info['email'] }}
                <br>Tax ID: {{ $store_info['afm'] }} - ΔΟΥ {{ $store_info['doy'] }}
                <br>ΑΡ Γ.Ε.ΜΗ {{ $store_info['argemi'] }}
            </address>
        </td>
        <td style="text-align:right;">
            <address style="margin-bottom: 20px;width:200px;">
                To: {{ $customer['company_name'] }}<br>
                Profession: {{ $customer['company_kind'] }}<br>
                Address: {{ $customer_address['address_1']}} <br>
                Telephone: {{ $customer['phone'] }} <br>
                Mobile: {{ $customer['mobile'] }}<br>
                Email: {{ $customer['email'] }}<br>
                Tax ID: {{ $customer['tax_id'] }} - TAX OFFICE: {{ $customer['tax_office'] }}
            </address>
            <p class="no-mbot invoice-html-date">
                @php
                    $payment_text='Μετρητά';
                    if($order->payment_type == 2)
                        $payment_text='Επιταγή';
                    if($order->payment_type == 3)
                        $payment_text='Πιστωτική Κάρτα';
                    if($order->payment_type == 4)
                        $payment_text='Αντικαταβολή';
                    if($order->payment_type == 5)
                        $payment_text='Με κατάθεση';
                    if($order->payment_type == 6)
                        $payment_text='Paypal';
                @endphp
                <span>Payment Type:</span>{{ $payment_text }}<br>
                <span class="bold">Issuing Date:</span>
                {{ date('d-m-Y') }}
            </p>
        </td>
    </tr>

    <tr style="width:100%;">
        <td style="width:100%;">

        </td>
    </tr>
    </tbody>
</table>

<table width="100%">
    <tbody>
    <tr>
        <td>
            <table style="width:100%;border: none;" cellspacing="0" cellpadding="0">
                <thead style="width:100%;">
                <tr style="width:100%;color:white;background-color:#415164;">
                    <th style="font-weight:normal;height:30px;border:none;text-align:center;">#</th>
                    <th style="font-weight:normal;height:30px;border:none;text-align:left;">Product</th>
                    <th style="font-weight:normal;height:30px;border:none;text-align:right;">Quantity</th>
                    <th style="font-weight:normal;height:30px;border:none;text-align:right;">Unit Price</th>
                    <th style="font-weight:normal;height:30px;border:none;text-align:right;">Price Before Discount</th>
                    <th style="font-weight:normal;height:30px;border:none;text-align:right;">Discount <br> % | Amount</th>
                    <th style="font-weight:normal;height:30px;border:none;text-align:right;padding-right:10px;">Price After Discount</th>
                </tr>
                </thead>
                <tbody style="width:100%;">
                @php
                    $total_tax = 0;
                    $subtotal = 0;
                    $enviromental_tax_count = 0;
                    $discount_total=0;
                @endphp

                @foreach ($products as $p)
                    @php

                        if($p['environmental_tax'] > 0)
                            $enviromental_tax_count += $p['quantity'];

                        if($p['discounts']['discount'] > 0)
                            $discount_total+=$p['discounts_totals']['discount'];

                        $total_tax += ($p['product_tax'] * $p['quantity']);
                        $subtotal += ($p['price']  * $p['quantity'])-$p['discounts_totals']['discount'];

                    @endphp
                    <tr style="width:100%;height:30px;border-bottom:1px solid #ccc;">
                        <td style="height:30px;text-align:center">{{ $loop->index + 1 }}</td>
                        <td style="height:30px;text-align:left;" class="description">
                            <span style="font-size:12px;">{{ $p['manufacturer_name'] }} {{ $p['details']['name'] }}  {{ $p['details']['width'] }}/{{ $p['details']['height_percentage'] }}{{ $p['details']['radial_structure'] }}{{ $p['details']['diameter'] }} {{ $p['details']['weight_flag'] }}{{ $p['details']['speed_flag'] }} @if($p['details']['fitting_postition'] == 1) E @elseif($p['details']['fitting_postition'] == 2) Π @else Ε/Π @endif @if($p['details']['tube_type'] == 0) TL @elseif($p['details']['tube_type'] == 1) TT @else TL/TT @endif {{ $p['details']['model'] }}</span>
                        </td>
                        <td style="height:30px;text-align:right">{{ $p['quantity'] }}</td>
                        <td style="height:30px;text-align:right">{{ round($p['price'],2) }}&euro;</td>
                        <td style="height:30px;text-align:right" class="amount">{{ ($p['price'] *$p['quantity']) }}&euro;</td>
                        <td style="height:30px;text-align:right">
                            @if ($p['discounts']['discount'] > 0)
                                {{ $p['discounts']['discount'] }}% | {{ $p['discounts_totals']['discount'] }}&euro;
                            @endif
                        </td>
                        <td style="height:30px;text-align:right" class="amount">{{ (($p['price'] *$p['quantity']) - $p['discounts_totals']['discount']) }}&euro;</td>
                    </tr>
                @endforeach
                @php
                    $subtotal += ($enviromental_tax_count * $enviromental_tax)/1.24;
                @endphp
                <tr style="width:100%;height:30px;border-bottom:1px solid #ccc;">
                    <td style="height:30px;text-align:center">{{ count($products)+1 }}</td>
                    <td style="height:30px;text-align:left;" class="description">
                        <span style="font-size:12px;">Environmental Tax</span>
                    </td>
                    <td style="height:30px;text-align:right">{{ $enviromental_tax_count }}</td>
                    <td style="height:30px;text-align:right">{{ round($enviromental_tax/1.24,2) }}&euro;</td>
                    <td style="height:30px;text-align:right"></td>
                    <td style="height:30px;text-align:right"></td>
                    <td style="height:30px;text-align:right" class="amount">{{  round(($enviromental_tax_count * $enviromental_tax)/1.24,2) }}&euro;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<table width="100%" style="margin-top:50px;">
    <tbody>
    <tr>
        <td style="text-align:right;border:none;">
            <table width="100%" class="table text-right">
                <tbody style="margin-right:0px;padding-right:0px;">
                @php
                    $total_tax += round($enviromental_tax_count * $enviromental_tax-(($enviromental_tax_count * $enviromental_tax)/1.24),2);
                    $total_discount = 0;
                @endphp
                <tr id="subtotal" style="margin-right:0px;padding-right:0px;">
                    <td style="text-align:right;"><span class="bold">Total</span></td>
                    <td style="text-align:right;padding-right:0px;margin-right:0px;" class="subtotal">{{ $subtotal }} &euro; </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><span class="bold">Shipping Cost </span></td>
                    <td style="text-align:right;" class="total">{{ $order['shipping_cost'] }} &euro; </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><span class="bold">Payment Cost</span></td>
                    <td style="text-align:right;" class="total">{{ $order['payment_cost'] }} &euro; </td>
                </tr>
                <tr class="tax-area">
                    <td style="text-align:right;" class="bold">TAX</td>
                    <td style="text-align:right;">{{ $total_tax }} &euro;</td>
                </tr>
                @if ($order['discount_type'] == 1 || $order['discount_type'] == 2)
                    @php

                        if($order['discount_type'] == 1) {
                            $total_discount = (float) $total * ( (float) $order['discount_amount'] / 100 );
                        } else {
                            $total_discount = (float) $order['discount_amount'];
                        }

                        $total_discount = number_format($total_discount, 2, '.', '');
                    @endphp
                    <tr>
                        <td style="text-align:right;"><span class="bold">Type of Discount</span></td>
                        <td style="text-align:right;" class="total">{{ $order['discount_type'] == 1 ? 'Ποσοστό' : 'Σταθερό ποσό' }} ({!! $order['discount_type'] == 1 ? $order['discount_amount'].'%' : $order['discount_amount'].'&euro;' !!})</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;"><span class="bold">Discount Amount</span></td>
                        <td style="text-align:right;" class="total">{{ $total_discount }}&euro;</td>
                    </tr>
                @endif
                @php
                    $total = (float) $subtotal + (float) $order['shipping_cost'] + (float) $order['payment_cost'] - (float)$total_discount +$total_tax;
                @endphp
                <tr>
                    <td style="text-align:right;"><span class="bold">Total Amount</span></td>
                    <td style="text-align:right;" class="total">{{ $total }} &euro; </td>
                </tr>
                </tbody>
            </table>

            <table width="100%" style="margin-top:40px;">
                <tbody>
                @if (!empty($order['comments']))
                    <tr>
                        <td style="text-align:left;"><span class="bold">Σχόλια</span></td>
                    </tr>
                    <tr>

                        <td style="text-align:left;"><span class="">{{ $order['comments'] }}</span></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>

</html>
