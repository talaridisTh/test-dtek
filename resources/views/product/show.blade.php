@extends('layouts.app')

@section('custom_css')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
    .only-print {
        display: none;
    }
    </style>
@endsection

@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid" id="show-product-page">
    <div class="row mb-4">
        <div class="col-xl-12">
            <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                <i class="la la-edit"></i> Επεξεργασία Προιοντος
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">

            <!--begin:: Widgets/Product Details-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Λεπτομέρειες Προϊόντος
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="kt-widget12__item col-lg-3 col-md-6 col-sm-6">
                                    <div class="border barcode-section">
                                    <h3 class="text-center">Barcode</h3>
                                        <div id="barcode_wrapper">
                                            <img style="max-width:100%;" id="barcode" class="d-block m-0 mx-auto printable">
                                            <p class="only-print">
                                            {!! !empty($product['width']) ? $product['width'] : '0' !!}/{!! !empty($product['height_percentage']) ? $product['height_percentage'] : '0' !!} {!! !empty($product['radial_structure']) ? $product['radial_structure'] : '0' !!} {!! !empty($product['diameter']) ? str_replace('.00', '', $product['diameter']) : '0' !!}
                                            /
                                            {!! !empty($manufacturer['name']) ? $manufacturer['name'] : '-' !!}/
                                            {!! !empty($product['name']) ? $product['name'] : '' !!}/
                                            {!! !empty($product['weight_flag']) ? $product['weight_flag'] : '' !!}{!! !empty($product['speed_flag']) ? $product['speed_flag'] : '' !!}/
                                            @if (!empty($product) && $product['fitting_position'] == 1)
                                                Μπροστά
                                            @elseif (!empty($product) && $product['fitting_position'] == 2)
                                                Πίσω
                                            @elseif (!empty($product) && $product['fitting_position'] == 3)
                                                Και στις δύο
                                            @endif/
                                            @if (!empty($product) && $product['tube_type'] == 0)
                                                TL
                                            @elseif (!empty($product) && $product['tube_type'] == 1)
                                                TT
                                            @elseif (!empty($product) && $product['tube_type'] == 2)
                                                TL/TT
                                            @endif/
                                            {!! !empty($product['model']) ? $product['model'] : '' !!}
                                            </p>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-primary kt-font-bold kt-font-transform-u d-block rounded-0 w-100" id="print_barcode"><i class="la la-print"></i> ΕΚΤΥΠΩΣΗ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Όνομα</span>
                                        <span class="kt-widget12__value">{!! !empty($product['name']) ? $product['name'] : '-' !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Κατασκευαστής</span>
                                        <span class="kt-widget12__value">{!! !empty($manufacturer['name']) ? $manufacturer['name'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Κατηγορία</span>
                                        <span class="kt-widget12__value">{!! !empty($category) ? $category : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Μοντέλο</span>
                                        <span class="kt-widget12__value">{!! !empty($product['model']) ? $product['model'] : '-' !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Τιμή Αγοράς</span>
                                        <span class="kt-widget12__value">{!! !empty($prices->buying_price) ? $prices->buying_price . '&euro;' : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Τιμή Λιανικής</span>
                                        <span class="kt-widget12__value">{!! !empty($prices->general_price) ? $prices->general_price . '&euro;' : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Τιμή Χονδρικής</span>
                                        <span class="kt-widget12__value">{!! !empty($prices->wholesale_price) ? $prices->wholesale_price . '&euro;' : '-' !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-12">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Περιγραφή</span>
                                        <span class="kt-widget12__value">
                                            {!! !empty($product['description']) ? $product['description'] : '-' !!}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Χαρακτηριστικά Προϊόντος
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Φάρδος</span>
                                        <span class="kt-widget12__value">{!! !empty($product['width']) ? $product['width'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Ποσοστό Ύψους</span>
                                        <span class="kt-widget12__value">{!! !empty($product['height_percentage']) ? $product['height_percentage'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Δομή Radial</span>
                                        <span class="kt-widget12__value">{!! !empty($product['radial_structure']) ? $product['radial_structure'] : '-' !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Διάμετρος</span>
                                        <span class="kt-widget12__value">{!! !empty($product['diameter']) ? $product['diameter'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Πλευρά τοποθέτησης</span>
                                        <span class="kt-widget12__value">
                                            @if (!empty($product) && $product['fitting_position'] == 1)
                                                Μπροστά
                                            @elseif (!empty($product) && $product['fitting_position'] == 2)
                                                Πίσω
                                            @elseif (!empty($product) && $product['fitting_position'] == 3)
                                                Και στις δύο
                                            @else   
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">TL/TT</span>
                                        <span class="kt-widget12__value">
                                            @if (!empty($product) && $product['tube_type'] == 0)
                                                TL
                                            @elseif (!empty($product) && $product['tube_type'] == 1)
                                                TT
                                            @elseif (!empty($product) && $product['tube_type'] == 2)
                                                TL/TT
                                            @else   
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Δείκτης Ταχύτητας</span>
                                        <span class="kt-widget12__value">{!! !empty($product['speed_flag']) ? $product['speed_flag'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Δείκτης Βάρους</span>
                                        <span class="kt-widget12__value">{!! !empty($product['weight_flag']) ? $product['weight_flag'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Heavy</span>
                                        <span class="kt-widget12__value">
                                            @if (!empty($product) && $product['is_heavy'] == 0)
                                                Όχι
                                            @elseif (!empty($product) && $product['is_heavy'] == 1)
                                                Ναι
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Ειδοποίηση ποσότητας κάτω από</span>
                                        <span class="kt-widget12__value">{!! !empty($product['notify_quantity']) ? $product['notify_quantity'] : '-' !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="kt-widget12__item col-lg-12">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Σχόλια</span>
                                        <span class="kt-widget12__value">{!! !empty($product['comments']) ? $product['comments'] : '-' !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Ποσότητα
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover data-table kt-datatable__table" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th>Αποθήκη</th>
                                    <th>Ράφι</th>
                                    <th>Batch</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quantities as $qty)
                                <tr>
                                    <td>{{ $qty->warehouse->name }}</td>
                                    <td>{{ $qty->shelf->name }}</td>
                                    <td>{{ $qty->batch }}</td>
                                    <td>{{ $qty->stock }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Μελλοντική Ποσότητα
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover data-table kt-datatable__table" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th>Stock</th>
                                    <th>Ημ/νία Παραλαβής</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($future_quantities as $future_qty)
                                <tr>
                                    <td>{{ $future_qty->stock }}</td>
                                    <td>{{ date('d/m/Y', strtotime($future_qty->arrival_date)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Product -->
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/JsBarcode.all.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        @if (!empty($product['id']))
            JsBarcode("#barcode", "product::{{ $product['id'] }}", {
                displayValue: false,
            });
            function printElem(divId) {
                var content = document.getElementById(divId).innerHTML;
                var mywindow = window.open('', 'Print', 'height=600,width=800');
    
                mywindow.document.write('<html><head><title>Print</title>');
                mywindow.document.write('</head><body >');
                mywindow.document.write(content);
                mywindow.document.write('</body></html>');
    
                mywindow.document.close();
                mywindow.focus()
                mywindow.print();
                mywindow.close();
                return true;
            }
            $('#print_barcode').click(function() {
                printElem('barcode_wrapper');
            });
        @endif

        $('.data-table').DataTable();
    });
</script>
@endsection