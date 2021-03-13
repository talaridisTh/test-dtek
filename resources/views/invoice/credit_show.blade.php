<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Πιστωτικό Τιμολόγιο {{ $invoice['credit_invoice_id'] }}</title>
    <style>
        table {
          border: none !important;
        }
        .table {
          border: none !important;
        }
        th
        {
            padding: 10px 0px 5px 5px; text-align: left; font-size: 13px; border: 0px solid black;
        }
        td
        {
            padding: 5px 0px 0px 5px; text-align: left; border: 0px solid black;
        }
        .table > thead > tr > td.active,
        .table > tbody > tr > td.active,
        .table > tfoot > tr > td.active,
        .table > thead > tr > th.active,
        .table > tbody > tr > th.active,
        .table > tfoot > tr > th.active,
        .table > thead > tr.active > td,
        .table > tbody > tr.active > td,
        .table > tfoot > tr.active > td,
        .table > thead > tr.active > th,
        .table > tbody > tr.active > th,
        .table > tfoot > tr.active > th {
            background-color: #f5f5f5;
        }
        .table-hover > tbody > tr > td.active:hover,
        .table-hover > tbody > tr > th.active:hover,
        .table-hover > tbody > tr.active:hover > td,
        .table-hover > tbody > tr:hover > .active,
        .table-hover > tbody > tr.active:hover > th {
            background-color: #e8e8e8;
        }
        .table > thead > tr > td.success,
        .table > tbody > tr > td.success,
        .table > tfoot > tr > td.success,
        .table > thead > tr > th.success,
        .table > tbody > tr > th.success,
        .table > tfoot > tr > th.success,
        .table > thead > tr.success > td,
        .table > tbody > tr.success > td,
        .table > tfoot > tr.success > td,
        .table > thead > tr.success > th,
        .table > tbody > tr.success > th,
        .table > tfoot > tr.success > th {
            background-color: #dff0d8;
        }
        .table-hover > tbody > tr > td.success:hover,
        .table-hover > tbody > tr > th.success:hover,
        .table-hover > tbody > tr.success:hover > td,
        .table-hover > tbody > tr:hover > .success,
        .table-hover > tbody > tr.success:hover > th {
            background-color: #d0e9c6;
        }
        .table > thead > tr > td.info,
        .table > tbody > tr > td.info,
        .table > tfoot > tr > td.info,
        .table > thead > tr > th.info,
        .table > tbody > tr > th.info,
        .table > tfoot > tr > th.info,
        .table > thead > tr.info > td,
        .table > tbody > tr.info > td,
        .table > tfoot > tr.info > td,
        .table > thead > tr.info > th,
        .table > tbody > tr.info > th,
        .table > tfoot > tr.info > th {
            background-color: #d9edf7;
        }
        .table-hover > tbody > tr > td.info:hover,
        .table-hover > tbody > tr > th.info:hover,
        .table-hover > tbody > tr.info:hover > td,
        .table-hover > tbody > tr:hover > .info,
        .table-hover > tbody > tr.info:hover > th {
            background-color: #c4e3f3;
        }
        .table > thead > tr > td.warning,
        .table > tbody > tr > td.warning,
        .table > tfoot > tr > td.warning,
        .table > thead > tr > th.warning,
        .table > tbody > tr > th.warning,
        .table > tfoot > tr > th.warning,
        .table > thead > tr.warning > td,
        .table > tbody > tr.warning > td,
        .table > tfoot > tr.warning > td,
        .table > thead > tr.warning > th,
        .table > tbody > tr.warning > th,
        .table > tfoot > tr.warning > th {
            background-color: #fcf8e3;
        }
        .table-hover > tbody > tr > td.warning:hover,
        .table-hover > tbody > tr > th.warning:hover,
        .table-hover > tbody > tr.warning:hover > td,
        .table-hover > tbody > tr:hover > .warning,
        .table-hover > tbody > tr.warning:hover > th {
            background-color: #faf2cc;
        }
        .table > thead > tr > td.danger,
        .table > tbody > tr > td.danger,
        .table > tfoot > tr > td.danger,
        .table > thead > tr > th.danger,
        .table > tbody > tr > th.danger,
        .table > tfoot > tr > th.danger,
        .table > thead > tr.danger > td,
        .table > tbody > tr.danger > td,
        .table > tfoot > tr.danger > td,
        .table > thead > tr.danger > th,
        .table > tbody > tr.danger > th,
        .table > tfoot > tr.danger > th {
            background-color: #f2dede;
        }
        .table-hover > tbody > tr > td.danger:hover,
        .table-hover > tbody > tr > th.danger:hover,
        .table-hover > tbody > tr.danger:hover > td,
        .table-hover > tbody > tr:hover > .danger,
        .table-hover > tbody > tr.danger:hover > th {
            background-color: #ebcccc;
        }
        body {font-family: 'dejavu sans' !important;}
    </style>
</head>
<body style="min-width: 98%; min-height: 100%; overflow: hidden; alignment-adjust: central;">
  <table style="width:100%; font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; page-break-after:always;" border="0" cellspacing="0">
    <tbody>
      <tr>
        <th width="100%" style="font-size: 11px !important;">
          <table style="width: 100%; vertical-align: bottom;">

            @if ($store_info)
              <tr>
                <td style="width:210px; border: 0px; padding-left:15px;overflow: hidden;">
                  <img style="margin:-50px 0;" width="170" src="{{ $store_info['logo'] }}" alt="{{ $store_info['name'] }}" class="img-circle"/>
                </td>
                <td style="border: 0px;text-align: center;">
                  <p style="margin: 3px; font-size:11px !important; font-weight:lighter !important; margin-bottom:2px">DND</p>
                  <p style="margin: 0px !important; padding: 0px !important; font-size:8px !important; font-weight:lighter !important">{{ $store_info['name'] }}</p>
                  <p style="margin: 0px !important; padding: 0px !important; font-size:8px !important; font-weight:lighter !important">{{ $store_info['owner'] }}</p>
                  <p style="margin: 0px !important; padding: 0px !important; font-size:8px !important; font-weight:lighter !important">{{ $store_info['address'] }}, {{ $store_info['city'] }}</p>
                  <p style="margin: 0px !important; padding: 0px !important; font-size:8px !important; font-weight:lighter !important">ΤΗΛ {{ $store_info['phone'] }}  ΦΑΞ {{ $store_info['fax'] }}</p>
                  <p style="margin: 0px !important; padding: 0px !important; font-size:8px !important; font-weight:lighter !important">EMAIL: {{ $store_info['email'] }}</p>
                  <p style="margin: 0px !important; padding: 0px !important; font-size:8px !important; font-weight:lighter !important; white-space:nowrap">ΑΦΜ {{ $store_info['afm'] }} - ΔΟΥ {{ $store_info['doy'] }}</p>
                </td>
              </tr>
            @endif

            <tr>
              <td style="border: 0px; color:#444">
                <div style="color:#000066;font-size:14px;">ΠΙΣΤΩΤΙΚΟ ΤΙΜΟΛΟΓΙΟ</div>
                <div style="color:#999;font-size:12px;">ΣΤΟΙΧΕΙΑ ΠΕΛΑΤΗ</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>ΠΡΟΣ:</strong> {{ $customer['company_name'] }}</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>Επάγγελμα:</strong> {{ $customer['company_kind'] }}</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>Διεύθυνση:</strong> {{ $customer_address['address'] }}</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>Τηλέφωνο:</strong> {{ $customer['phone'] }}</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>Κινητό:</strong> {{ $customer['mobile'] }}</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>Fax:</strong> {{ $customer['fax'] }}</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>Email:</strong> {{ $customer['email'] }}</div>
                {{-- <div style="padding-left: 20px;font: 10px lighter;"> <strong>Πόλη:</strong> </div> --}}
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>ΑΦΜ:</strong> {{ $customer['tax_id'] }}</div>
                <div style="padding-left: 20px;font: 10px lighter;"> <strong>ΔΟΥ:</strong> {{ $customer['tax_office'] }}</div>
              </td>
              <td style="border: 0px;padding-left:30px; color:#444">
                    @if ($invoice)
                    <div style="padding-left: 20px;font: 10px lighter;"> <strong>Αρ. Τιμολογίου:</strong> Ν&deg; {{ $invoice['credit_invoice_id'] }}</div>
                    <div style="padding-left: 20px;font: 10px lighter;"> <strong>Ημ. Τιμολογίου:</strong> {{ $invoice['invoice_date'] }}</div>
                    @else
                    <div style="padding-left: 20px;font: 10px lighter;"> <strong>Αρ.Παραγγελίας:</strong> Ν&deg;  ΠΑΡ-{{ $invoice['order_id'] }}</div>
                    <div style="padding-left: 20px;font: 10px lighter;"> <strong>Ημ/νια Παραγγελίας:</strong> {{ $order['created_at'] }}</div>
                    @endif
                    <div style="padding-left: 20px;font: 10px lighter;"> <strong>Τόπος Φόρτωσης: Θεσσαλονίκη</div>
                    <div style="padding-left: 20px;font: 10px lighter;"> <strong>Προορισμός:</strong> {{ $customer_address['city'] }}</div>
                </td>
            </tr>
          </table>
          <br><br>
          <table class="table" style="font: 12px lighter; width:100%" border="0" cellspacing="0">
            <tr class="info" style="background-color: #E0E5E8;">
                <th>Α/Α</th>
                <th>Προϊόν</th>
                <th style="text-align:center; padding:0 10px">Ποσότητα</th>
                <th style="text-align:right; padding:0 10px">Τιμή</th>
                <th style="text-align:right; padding:0 10px">Σύνολο</th>
            </tr>
            @php
              $total_tax = 0;
              $subtotal = 0;
            @endphp
            @foreach ($products as $p)
            @php
              $total_tax += ($p['product_tax'] * $p['quantity']);
              $subtotal += (($p['price'] + $p['product_tax']) * $p['quantity']);
            @endphp
            <tr>
            <td class="warning" style="padding:0 10px">{{ $loop->index + 1 }}</td>
                <td class="success" style="padding:0 10px"><strong>
                        {{ $p['manufacturer_name'] }} {{ $p['details']['name'] }}  {{ $p['details']['width'] }}/{{ $p['details']['height_percentage'] }}{{ $p['details']['radial_structure'] }}{{ $p['details']['diameter'] }} {{ $p['details']['weight_flag'] }}{{ $p['details']['speed_flag'] }} @if($p['details']['fitting_postition'] == 1) E @elseif($p['details']['fitting_postition'] == 2) Π @else Ε/Π @endif @if($p['details']['tube_type'] == 0) TL @elseif($p['details']['tube_type'] == 1) TT @else TL/TT @endif {{ $p['details']['model'] }}
                </strong></td>
                <td class="danger col-sm-1" style="text-align:center;padding:0 10px">{{ $p['quantity'] }}</td>
                <td class="warning col-sm-1" style="text-align:right;padding:0 10px">{{ $p['price'] }}&euro;</td>
                <td class="danger col-sm-2" style="text-align:right;padding:0 10px">{{ $p['quantity'] * ($p['price'] + $p['product_tax']) }}&euro;</td>
            </tr>
            @endforeach
          </table>
          <table style="font: 12px lighter;" border="0" cellspacing="0" align="right">
            <tr>
              <th><strong>Σύνολο:</strong></th>
              <td class="danger" style="text-align:right;padding:0 10px">{{ $subtotal }} &euro;</td>
            </tr>
            <tr>
              <th><strong>ΦΠΑ:</strong></th>
              <td class="danger" style="text-align:right;padding:0 10px;">{{ $total_tax }} &euro;</td>
            </tr>
            @php
            $total = (float) $subtotal;
            @endphp
            <tr>
              <th>Τελικό Σύνολο:</th>
              <td class="danger" style="text-align:right;padding:0 10px">{{ $total }} &euro;</td>
            </tr>
          </table>
          <br><br><br><br>
          <strong>Σχόλια:</strong>
          <table style="width:100%" border="0" cellspacing="0">
            <tr>
              <th style="font: 10px lighter;">
                {{ $order['comments'] }}
              </th>
						</tr>
					</table>
        </th>
      </tr>
    </tbody>
  </table>
</body>
</html>
