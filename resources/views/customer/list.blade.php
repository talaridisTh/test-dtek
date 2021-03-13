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
                    Λίστα πελατών
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('customers.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Νέος Πελάτης
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body link-search">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>ID Πελάτη</th>
                        <th>Όνομα</th>
                        <th>Φορολ. Κλάση</th>
                        <th>Τηλέφωνο</th>
                        <th>Κινητό</th>
                        <th>Εταιρεία</th>
                        <th>Email</th>
                        <th>Φαξ</th>
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
    <script src="{{ asset('assets/app/custom/delete-modal.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/app/custom/customer/list.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            previous_search = urlParams.get('search');
            if(previous_search != '' && previous_search != null) {
                $('#kt_table_1').DataTable().search(previous_search).draw()
            }
            $('body').on('change', '.link-search input[type="search"]', function() {
                var s = $(this).val();
                window.history.replaceState(null, null, "?search=" + s);
            });
        });
    </script>
@endsection