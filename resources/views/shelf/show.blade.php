@extends('layouts.app')

@section('custom_css')
@endsection

@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid" id="show-shelf-page">
    <div class="row mb-4">
        <div class="col-xl-12">
            <a href="{{ route('shelves.edit', $shelf['shelf_id']) }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                <i class="la la-edit"></i> Επεξεργασία Ραφιού
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Shelf Details-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Λεπτομέρειες Ραφιού
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="kt-widget12__item col-lg-3 col-md-6 col-sm-12">
                                    <div class="border barcode-section">
                                        <h3 class="text-center">Barcode</h3>
                                        <div id="barcode_wrapper">
                                            <img id="barcode" class="d-block m-0 mx-auto printable">
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
                                        <span class="kt-widget12__value">{!! !empty($shelf['name']) ? $shelf['name'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Αποθήκη</span>
                                        <span class="kt-widget12__value">{!! !empty($warehouse['name']) ? $warehouse['name'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-6 col-sm-6">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Τύπος Ραφιού</span>
                                        <span class="kt-widget12__value">
                                            @if( $shelf['is_product_shelf'] && $shelf['is_product_shelf'] == 1 )
                                                Ράφι Προϊόντος
                                            @else
                                                Ράφι Αναμονής
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Shelf Details-->
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script src="{{ asset('js/JsBarcode.all.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        @if (!empty($shelf))
            JsBarcode("#barcode", "shelf::{{ $shelf['shelf_id'] }}", {
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
    });
</script>
@endsection