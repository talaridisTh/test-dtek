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
                    Λίστα παραγγελιών
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('orders.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Νέα Παραγγελία
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search_order_status">Κατάσταση</label>
                        <select name="search_order_status" id="search_order_status" class="form-control">
                            <option value="-1">Ολες</option>
                            <option value="1">Δημιουργία</option>
                            <option value="2">Ανοιχτή για προϊόντα / έκπτωση</option>
                            <option value="3">Ράφι Αναμονής</option>
                            <option value="4">Αποστολή</option>
                            <option value="5">Ολοκληρώθηκε</option>
                            <option value="6">Επιστράφηκε</option>
                            <option value="7">Ακυρώθηκε</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search_from">Από</label>
                        <input type="text" class="form-control" name="search_from" id="search_from">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search_to">Έως</label>
                        <input type="text" class="form-control" name="search_to" id="search_to">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-brand btn-elevate btn-icon-sm" id="search-orders"><i class="la la-search"></i> Αναζήτηση</button>
                        <button type="button" class="btn btn-brand btn-default btn-icon-sm" id="clear-search-orders"><i class="la la-close"></i> Καθαρισμός Αναζήτησης</button>
                    </div>
                </div>
            </div>
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>ID Παραγγελίας</th>
                        <th>Πελάτης</th>
                        <th>Σύνολο</th>
                        <th>Πληρωμένο ποσό</th>
                        <th>Υπόλοιπο</th>
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
    <script src="{{ asset('assets/app/custom/order/list.js') }}" type="text/javascript"></script>
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        function deleteOrder(order_id)
        {
            let self = this;
            Swal.fire({
                title: 'Διαγραφή Παραγγελίας',
                text: 'Είστε σίγουρος ότι θέλετε να διαγράψετε την παραγγελία;',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ναι, Διαγραφή'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: BASE_URL + '/orders/'+order_id,
                        method: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            _method:'DELETE'

                        },
                        dataType: 'json'
                    })
                        .done(function(res) {
                            if(res || res.status == 'success') {
                               window.location.reload();
                            }
                            else
                                window.alert('Κάτι πήγε στραβά');
                        })
                        .fail(function(err) {
                            window.alert(err);
                        })
                }
            })
        }
        $(document).ready(function() {
            $('#search-orders').click(function() {
                $('#kt_table_1').DataTable().draw();
            });

            $('#clear-search-orders').click(function() {
                $('#search_order_status').val('-1').trigger('change');
                $('#search_from').val('').trigger('change');
                $('#search_to').val('').trigger('change');
                $('#kt_table_1').DataTable().draw();
            });

            $('#search_from').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('#search_to').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
    </script>
@endsection
