<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Τιμολόγιο {{ $invoice['invoice_id'] }}</title>
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
                    <img style="width:120px;margin-bottom:30px;" src="{{ $store_info['logo'] }}" class="img-responsive" alt="{{ $store_info['name'] }}">
                </td>
                <td style="text-align:right;">
                    <p style="width:400px;font-size:20px;font-weight:bold;" class="m-0">ΤΙΜΟΛΟΓΙΟ-ΔΕΛΤΙΟ ΑΠΟΣΤΟΛΗΣ</p>
                    <p class="m-0" style="color:#666;font-weight:700;">#INV-{{$invoice['invoice_id']}}</p>
                    @if ($invoice['invoice_status'] == 1)
                        <p style="color: red;margin:0px;text-transform:uppercase;">Ακυρωμένο</p>
                    @elseif (false)
                        <p style="color: green;margin:0px;text-transform:uppercase;">Εξοφλήθηκε</p>
                    @else
                        <p style="color: orange;margin:0px;text-transform:uppercase;">Εκδόθηκε</p>
                    @endif
                </td>
            </tr>

            <tr>
                <td>
                    <address class="invoice-html-company-info">
                        <b style="color:black" class="company-name-formatted">{{$store_info['name']}}</b>
                        <br>{{ $store_info['profession'] }}<br>ΤΗΛ {{ $store_info['phone'] }}
                        <br>{{$store_info['address']}}<br>{{$store_info['city']}}<br>
                        <br>EMAIL: {{ $store_info['email'] }}
                        <br>ΑΦΜ {{ $store_info['afm'] }} - ΔΟΥ {{ $store_info['doy'] }}
                        <br>ΑΡ Γ.Ε.ΜΗ {{ $store_info['argemi'] }}
                    </address>
                </td>
                <td style="text-align:right;">
                    <address style="margin-bottom: 20px;width:400px;">
                        Προς: {{ $customer['company_name'] }}<br>
                        Επάγγελμα: {{ $customer['company_kind'] }}<br>
                        Διεύθυνση: {{ $customer_address['address_1']}} <br>
                        Πόλη: {{ $customer_address['city']}} <br>
                        Τηλέφωνο: {{ $customer['phone'] }} <br>
                        Κινητό: {{ $customer['mobile'] }}<br>
                        Email: {{ $customer['email'] }}<br>
                        ΑΦΜ: {{ $customer['tax_id'] }} - ΔΟΥ: {{ $customer['tax_office'] }}
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
                        <span>Τρόπος Πληρωμής: </span>{{ $payment_text }}<br/>
                        <span>Σκοπός Διακίνησης: </span>ΠΩΛΗΣΗ<br/>
                        <span>Τόπος Αποστολής: </span>ΕΔΡΑ ΜΑΣ<br/>
                        <span>Τόπος Παράδοσης: </span>ΕΔΡΑ ΤΟΥΣ<br/>
                        <span class="bold">Ημ/νία Έκδοσης: </span>
                        {{ $invoice['invoice_date'] }}
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
                                <th style="font-weight:normal;height:30px;border:none;text-align:left;">Προϊόν</th>
                                <th style="font-weight:normal;height:30px;border:none;text-align:right;">Ποσότητα</th>
                                <th style="font-weight:normal;height:30px;border:none;text-align:right;">Τιμή Μονάδας</th>
                                <th style="font-weight:normal;height:30px;border:none;text-align:right;">Αξία Προ Έκπτωσης Τιμή</th>
                                <th style="font-weight:normal;height:30px;border:none;text-align:right;">Έκπτωση <br> % | Ποσό</th>
                                <th style="font-weight:normal;height:30px;border:none;text-align:right;padding-right:10px;">Αξία Μετα Έκπτωσης</th>
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
                                    <td style="height:30px;text-align:right" class="amount">{{ round(($p['price'] *$p['quantity']), 2) }}&euro;</td>
                                    <td style="height:30px;text-align:right">
                                    @if ($p['discounts']['discount'] > 0)
                                           {{ round($p['discounts']['discount'], 2) }}% | {{ round($p['discounts_totals']['discount'], 2) }}&euro;
                                    @endif
                                    </td>
                                    <td style="height:30px;text-align:right" class="amount">{{ round((($p['price'] *$p['quantity']) - $p['discounts_totals']['discount']), 2) }}&euro;</td>
                                </tr>
                            @endforeach
                            @php
                            $subtotal += ($enviromental_tax_count * $enviromental_tax)/1.24;
                            @endphp
                            <tr style="width:100%;height:30px;border-bottom:1px solid #ccc;">
                                <td style="height:30px;text-align:center">{{ count($products)+1 }}</td>
                                <td style="height:30px;text-align:left;" class="description">
                                    <span style="font-size:12px;">Οικολογικό Τέλος</span>
                                </td>
                                <td style="height:30px;text-align:right">{{ round($enviromental_tax_count, 2) }}</td>
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

    <table width="100%" style="margin-top:20px;">
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
                                <td style="text-align:right;"><span class="bold">Σύνολο</span></td>
                                <td style="text-align:right;padding-right:0px;margin-right:0px;" class="subtotal">{{ round($subtotal, 2) }} &euro; </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><span class="bold">Κόστος Μεταφορικών</span></td>
                                <td style="text-align:right;" class="total">{{ round($order['shipping_cost'], 2) }} &euro; </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><span class="bold">Κόστος Πληρωμής</span></td>
                                <td style="text-align:right;" class="total">{{ round($order['payment_cost'], 2) }} &euro; </td>
                            </tr>
                            <tr class="tax-area">
                                <td style="text-align:right;" class="bold">ΦΠΑ</td>
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
                                <td style="text-align:right;"><span class="bold">Τύπος Έκπτωσης</span></td>
                                <td style="text-align:right;" class="total">{{ $order['discount_type'] == 1 ? 'Ποσοστό' : 'Σταθερό ποσό' }} ({!! $order['discount_type'] == 1 ? $order['discount_amount'].'%' : $order['discount_amount'].'&euro;' !!})</td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><span class="bold">Ποσό Έκπτωσης</span></td>
                                <td style="text-align:right;" class="total">{{ $total_discount }}&euro;</td>
                            </tr>
                            @endif
                            @php
                                $total = (float) $subtotal + (float) $order['shipping_cost'] + (float) $order['payment_cost'] - (float)$total_discount +$total_tax;
                            @endphp
                            <tr>
                                <td style="text-align:right;"><span class="bold">Τελικό Σύνολο</span></td>
                                <td style="text-align:right;" class="total">{{ round($total, 2) }} &euro; </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Comments --}}
                    <table width="100%" style="margin-top:20px;margin-bottom:20px;">
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

                    {{-- Bank accounts & stamps --}}
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td style="text-align:left;vertical-align:top;width:200px;">
                                    <span class="bold">Λογαριασμοί:</span><br>
                                    <br><span style="font-size:10px;">Εθνικη Τραπεζα: GR75 0110 2510 0000 2510 0173 456</span>
                                    <br><span style="font-size:10px;">Eurobank : GR7102607030000680200273475</span>
                                    <br><span style="font-size:10px;">Τραπεζα Πειραιως: GR70 0172 2030 0052 0309 4292 227</span>
                                    <br><span style="font-size:10px;">Attica Bank: GR25 0160 4660 0000 0008 5043 188</span>
                                </td>
                                <td width="100px;" style="text-align:center;vertical-align:top;height:200px;">
                                    <span class="bold" style="text-decoration:underline;">Για την έκδοση</span>
                                </td>
                                <td width="100px;" style="text-align:right;vertical-align:top;height:200px;">
                                    <span class="bold" style="text-decoration:underline;">Για την παραλαβή</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
