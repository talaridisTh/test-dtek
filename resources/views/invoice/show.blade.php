<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Τιμολόγιο {{ $invoice['invoice_id'] }}</title>
    <link rel="shortcut icon" id="favicon" href="">
    <link rel="stylesheet" href="{{ asset('assets/vendors/pdf.css') }}">
    <link href="{{ asset('assets/vendors/custom/vendors/fontawesome5/css/all.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
    <style>
      body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>

<body class="customers viewinvoice">
    <div id="wrapper">
        <div id="content">
            <div class="container">
                <div class="row">
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="mtop15 preview-top-wrapper">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mbot30">
                                    <div class="invoice-html-logo">
                                        <a href="/" class="logo img-responsive">
                                          <img src="{{ asset('assets/media/logos/logo.png') }}" class="img-responsive" alt="{{ $store_info['name'] }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="top" data-sticky="" data-sticky-class="preview-sticky-header">
                            <div class="container preview-sticky-container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <h3 class="bold no-mtop invoice-html-number no-mbot">
                                              <span class="sticky-visible hide">INV-{{$invoice['invoice_id']}}</span>
                                            </h3>
                                            <h4 class="invoice-html-status mtop7">
                                                @if ($invoice['invoice_status'] == 1)
                                                <span class="label label-danger  s-status invoice-status-1">Ακυρωμένο</span>
                                                @elseif (false)
                                                    <span class="label label-success  s-status invoice-status-1">Εξοφλήθηκε</span>
                                               @else
                                                 <span class="label label-warning  s-status invoice-status-1">Εκδόθηκε</span>
                                              @endif
                                            </h4>
                                        </div>
                                        <div class="visible-xs">
                                            <div class="clearfix"></div>
                                        </div>
                                        <form action="{{$action}}" method="get" accept-charset="utf-8">
                                            @csrf
                                            <input type="hidden" value="1" name="en">
                                            <button type="submit" name="invoicepdf" value="invoicepdf" class="btn btn-default pull-right action-button mtop5">
                                                <i class="fa fa-file-pdf"></i>
                                                Download English
                                              </button>
                                        </form>
                                        <form action="{{$action}}" method="get" accept-charset="utf-8">
                                            @csrf
                                            <button type="submit" name="invoicepdf" value="invoicepdf" class="btn btn-default pull-right action-button mtop5" style="margin-right: 20px;">
                                                <i class="fa fa-file-pdf"></i>
                                                Download
                                              </button>
                                        </form>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel_s mtop20">
                        <div class="panel-body">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="row mtop20">
                                    <div class="col-md-5 col-sm-5 transaction-html-info-col-left">
                                      <h4 class="bold invoice-html-number">INV-{{$invoice['invoice_id']}}</h4>
                                      <address class="invoice-html-company-info">
                                        <b style="color:black" class="company-name-formatted">{{$store_info['name']}}</b>
                                          <br>{{ $store_info['profession'] }}<br>ΤΗΛ {{ $store_info['phone'] }}
                                          <br>{{$store_info['address']}}<br>{{$store_info['city']}}<br>
                                          <br>EMAIL: {{ $store_info['email'] }}
                                          <br>ΑΦΜ {{ $store_info['afm'] }} - ΔΟΥ {{ $store_info['doy'] }}
                                          <br>ΑΡ Γ.Ε.ΜΗ {{ $store_info['argemi'] }}
                                      </address>
                                    </div>
                                    <div class="col-sm-7 text-right transaction-html-info-col-right">
                                      <span class="bold invoice-html-bill-to">ΣΤΟΙΧΕΙΑ ΠΕΛΑΤΗ</span>
                                      <address class="invoice-html-customer-billing-info">
                                        Προς: {{ $customer['customer_name'] }}<br>
                                        Επάγγελμα: {{ $customer['company_kind'] }}<br>
                                        Διεύθυνση: {{ $customer_address['address_1']}} <br>
                                        Πόλη: {{ $customer_address['city']}} <br>
                                        Τηλέφωνο: {{ $customer['phone'] }} <br>
                                        Κινητό: {{ $customer['mobile'] }}<br>
                                        Email: {{ $customer['email'] }}<br>
                                        ΑΦΜ: {{ $customer['tax_id'] }} - ΔΟΥ: {{ $customer['tax_office'] }}
                                      </address>
                                      <!-- shipping details -->
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
                                          <span>Τρόπος Πληρωμής:</span>{{ $payment_text }}<br>
                                          <span>Σκοπός Διακίνησης:</span>ΠΩΛΗΣΗ<br>
                                          <span>Τόπος Αποστολής</span>ΕΔΡΑ ΜΑΣ<br>
                                          <span>Τόπος Παράδοσης</span>ΕΔΡΑ ΤΟΥΣ<br>
                                        <span class="bold">Ημ/νία Έκδοσης:</span> {{ $invoice['invoice_date'] }}
                                      </p>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table items items-preview invoice-items-preview" data-type="invoice">
                                                <thead>
                                                    <tr>
                                                        <th align="center">#</th>
                                                        <th class="description" width="50%" align="left">Προϊόν</th>
                                                        <th align="right">Ποσότητα</th>
                                                        <th align="right">Τιμή Μονάδας</th>
                                                        <th style="font-weight:normal;height:30px;border:none;text-align:right;">Αξία Προ Έκπτωσης Τιμή</th>
                                                        <th style="font-weight:normal;height:30px;border:none;text-align:right;">Έκπτωση <br> % | Ποσό</th>
                                                        <th style="font-weight:normal;height:30px;border:none;text-align:right;padding-right:10px;">Αξία Μετα Έκπτωσης</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
                                                    <tr nobr="true">
                                                        <td align="center">{{ $loop->index + 1 }}</td>
                                                        <td class="description" align="left;"><span style="font-size:px;"><strong>{{ $p['manufacturer_name'] }} {{ $p['details']['name'] }}  {{ $p['details']['width'] }}/{{ $p['details']['height_percentage'] }}{{ $p['details']['radial_structure'] }}{{ $p['details']['diameter'] }} {{ $p['details']['weight_flag'] }}{{ $p['details']['speed_flag'] }} @if($p['details']['fitting_postition'] == 1) E @elseif($p['details']['fitting_postition'] == 2) Π @else Ε/Π @endif @if($p['details']['tube_type'] == 0) TL @elseif($p['details']['tube_type'] == 1) TT @else TL/TT @endif {{ $p['details']['model'] }}</strong></span>
                                                        <td align="right">{{ $p['quantity'] }}</td>
                                                        <td align="right">{{ $p['price'] }}&euro;</td>
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
                                                        <td style="height:30px;text-align:right">{{ round($enviromental_tax_count,2) }}</td>
                                                        <td style="height:30px;text-align:right">{{ round($enviromental_tax/1.24,2) }}&euro;</td>
                                                        <td style="height:30px;text-align:right"></td>
                                                        <td style="height:30px;text-align:right"></td>
                                                        <td style="height:30px;text-align:right" class="amount">{{  round(($enviromental_tax_count * $enviromental_tax)/1.24,2) }}&euro;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @php
                                        $total_tax += round($enviromental_tax_count * $enviromental_tax-(($enviromental_tax_count * $enviromental_tax)/1.24),2);
                                        $total_discount = 0;
                                    @endphp
                                    <div class="col-md-6 col-md-offset-6">
                                        <table class="table text-right">
                                            <tbody>
                                                <tr id="subtotal">
                                                    <td style="text-align:left;"><span class="bold" style="font-weight:700;">Σύνολο</span>
                                                    </td>
                                                    <td class="subtotal">{{ round($subtotal, 2) }} &euro; </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left;"><span class="bold" style="font-weight:700;">Κόστος Μεταφορικών</span>
                                                    </td>
                                                    <td class="total">{{ round($order['shipping_cost'], 2) }} &euro; </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left;"><span class="bold" style="font-weight:700;">Κόστος Πληρωμής</span>
                                                    </td>
                                                    <td class="total">{{ round($order['payment_cost'], 2) }} &euro; </td>
                                                </tr>
                                                <tr class="tax-area">
                                                    <td class="bold" style="font-weight:700;text-align:left;">ΦΠΑ</td>
                                                    <td>{{ round($total_tax, 2) }} &euro;</td>
                                                </tr>
                                                @if ($order['discount_type'] == 1 || $order['discount_type'] == 2)
                                                @php
                                                $total = (float) $subtotal + (float) $order['shipping_cost'] + (float) $order['payment_cost'];
                                                $total_discount = 0;
                                                if($order['discount_type'] == 1) {
                                                  $total_discount = (float) $total * ( (float) $order['discount_amount'] / 100 );
                                                } else {
                                                  $total_discount = (float) $order['discount_amount'];
                                                }

                                                $total_discount = number_format($total_discount, 2, '.', '');
                                                @endphp
                                                <tr>
                                                    <td><span class="bold" style="font-weight:700;text-align:left;">Τύπος Έκπτωσης</span>
                                                    </td>
                                                    <td class="total">{{ $order['discount_type'] == 1 ? 'Ποσοστό' : 'Σταθερό ποσό' }} ({!! $order['discount_type'] == 1 ? $order['discount_amount'].'%' : $order['discount_amount'].'&euro;' !!})</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="bold" style="font-weight:700;">Ποσό Έκπτωσης</span>
                                                    </td>
                                                    <td class="total">{{ $total_discount }}&euro;</td>
                                                </tr>
                                                @endif
                                                @php
                                                    $total = (float) $subtotal + (float) $order['shipping_cost'] + (float) $order['payment_cost'] - (float)$total_discount +$total_tax;
                                                @endphp
                                                <tr>
                                                    <td style="text-align:left;"><span class="bold" style="font-weight:700;">Τελικό Σύνολο</span></td>
                                                    <td class="total">{{ round($total, 2) }} &euro; </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @if (!empty($order['comments']))
                                <div class="row" style="margin-bottom:20px;">
                                    <div class="col-md-12">
                                        <h5>Σχόλια</h5>
                                        <div class="comments">
                                            {{ $order['comments'] }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="bank-accounts">
                                            <h5>Λογαριασμοί:</h5>
                                            <div class="bank-accounts-list">
                                                Εθνικη Τραπεζα: GR75 0110 2510 0000 2510 0173 456
                                                <br>Eurobank : GR7102607030000680200273475
                                                <br>Τραπεζα Πειραιως: GR70 0172 2030 0052 0309 4292 227
                                                <br>Attica Bank: GR25 0160 4660 0000 0008 5043 188
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stamp-section" style="min-height:200px;">
                                            <h5 style="text-align:center;text-decoration:underline;">Για την έκδοση</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="signature-section" style="min-height:200px;">
                                            <h5 style="text-align:right;text-decoration:underline;">Για την παραλαβή</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
