@extends('layouts.app')
@section('custom_css')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')    
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Λίστα Τιμολογίων
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoice_customer_id">Πελάτης</label>
                        <select name="invoice_customer_id" id="invoice_customer_id" class="form-control customer_id"></select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoice_status">Κατάσταση</label>
                        <select name="invoice_status" id="invoice_status" class="form-control">
                            <option value="-1">Ολα</option>
                            <option value="0">Ενεργό</option>
                            <option value="1">Ακυρωμένο</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoice_search_from">Από</label>
                        <input type="text" class="form-control" name="invoice_search_from" id="invoice_search_from" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoice_search_to">Έως</label>
                        <input type="text" class="form-control" name="invoice_search_to" id="invoice_search_to" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-brand btn-elevate btn-icon-sm" id="search-invoices"><i class="la la-search"></i> Αναζήτηση</button>
                        <button type="button" class="btn btn-brand btn-default btn-icon-sm" id="clear-search-invoices"><i class="la la-close"></i> Καθαρισμός Αναζήτησης</button>
                    </div>
                </div>
            </div>
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="invoice_kt_table_1">
                <thead>
                    <tr>
                        <th>ID Τιμολογίου</th>
                        <th>Παραγγελία</th>
                        <th>Κατάσταση</th>
                        <th>Ημ/νία</th>
                        <th>Ενέργειες</th>
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/datatable/dataTables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/datatable/jszip.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/datatable/pdfmake.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/datatable/vfs_fonts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/datatable/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/app/custom/invoice/list.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
    		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            // Init customer select2
            $('.customer_id').select2({
                // @todo - Get customer search results
                placeholder: "Αναζήτηση πελάτη",
                minimumInputLength: 2,
                ajax: {
                    url: BASE_URL + '/customers/search',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token: CSRF_TOKEN,
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        let customers = data['customers'];
                        return {
                            results: customers
                        };
                    },
                    cache: true
                }
            });
              
            $('#search-invoices').click(function() {
                $('#invoice_kt_table_1').DataTable().draw();
            });

            $('#clear-search-invoices').click(function() {
                $('#invoice_customer_id').val('-1').trigger('change');
                $('#invoice_search_from').val('').trigger('change');
                $('#invoice_search_to').val('').trigger('change');
                $('#invoice_status').val('-1').trigger('change');
                
                $('#invoice_kt_table_1').DataTable().draw();
            });

            $('#invoice_search_from').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('#invoice_search_to').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('body').on('click', '.cancel-invoice', function() {
                let invoice_id = $(this).data('id');
                let order_id = $(this).data('order-id');
                let self = this;
                let invoice_status = 1;
                Swal.fire({
                    title: 'Ακύρωση Τιμολογίου',
                    text: 'Είστε σίγουρος ότι θέλετε να ακυρώσετε το τιμολόγιο;',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ναι, ακύρωση'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: BASE_URL + '/invoices/' + invoice_id,
                            method: 'POST',
                            data: {
                                _method: 'PUT',
                                invoice_id,
                                order_id,
                                invoice_status
                            },
                            dataType: 'json'
                        })
                        .done(function(res) {
                            $('#invoice_kt_table_1').DataTable().draw();
                        })
                        .fail(function(err) {
                            console.log(err);
                        })
                    }
                })
            });
        });
    </script>
@endsection