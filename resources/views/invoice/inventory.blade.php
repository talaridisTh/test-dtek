<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Νέα Παραγγελία</title>
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
                    <p style="font-size:20px;font-weight:bold;" class="m-0">ΔΕΛΤΙΟ ΑΠΟΣΤΟΛΗΣ</p>
                    <p class="m-0" style="color:#666;font-weight:700;"></p>
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
                    <address style="margin-bottom: 20px;width:200px;">
                        Προς: {{ $customer['company_name'] }}<br>
                        Επάγγελμα: {{ $customer['company_kind'] }}<br>
                        Διεύθυνση: {{ $customer_address['address_1']}} <br>
                        Τηλέφωνο: {{ $customer['phone'] }} <br>
                        Κινητό: {{ $customer['mobile'] }}<br>
                        Email: {{ $customer['email'] }}<br>
                        ΑΦΜ: {{ $customer['tax_id'] }} - ΔΟΥ: {{ $customer['tax_office'] }}
                    </address>
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
                                <th style="font-weight:normal;height:30px;border:none;text-align:right;">Ράφι</th>
                                <th style="font-weight:normal;height:30px;border:none;text-align:right;">Παρτίδα</th>
                            </tr>
                        </thead>
                        <tbody style="width:100%;">
                            @foreach ($products as $p)
                                <tr style="width:100%;height:30px;border-bottom:1px solid #ccc;">
                                    <td style="height:30px;text-align:center">{{ $loop->index + 1 }}</td>
                                    <td style="height:30px;text-align:left;" class="description">
                                        <span style="font-size:12px;">{{ $p['manufacturer_name'] }} {{ $p['details']['name'] }}  {{ $p['details']['width'] }}/{{ $p['details']['height_percentage'] }}{{ $p['details']['radial_structure'] }}{{ $p['details']['diameter'] }} {{ $p['details']['weight_flag'] }}{{ $p['details']['speed_flag'] }} @if($p['details']['fitting_postition'] == 1) E @elseif($p['details']['fitting_postition'] == 2) Π @else Ε/Π @endif @if($p['details']['tube_type'] == 0) TL @elseif($p['details']['tube_type'] == 1) TT @else TL/TT @endif {{ $p['details']['model'] }}</span>
                                    </td>
                                    <td style="height:30px;text-align:right">{{ $p['quantity'] }}</td>
                                    <td style="height:30px;text-align:right">{{ $p['shelf'] }}</td>
                                    <td style="height:30px;text-align:right" >{{ $p['batch'] }}</td>
                                </tr>
                            @endforeach
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
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
