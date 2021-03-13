@extends('layouts.app')
@section('custom_css')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        tr td:nth-child(6),
        tr td:nth-child(7),
        tr td:nth-child(8),
        tr td:nth-child(10) {
            width: 50px;
        }

        .m-0 {
            margin: 0;
        }

        tr.add-separator td {
            border-top: 2px solid #a7a7a7;
        }
    </style>
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
                    Λίστα Προϊόντων
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('products.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Νέο Προϊόν
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="manufacturer">Κατασκευαστής</label>
                        <select name="manufacturer" id="manufacturer" class="form-control kt-select2 trigger-redraw">
                            <option value="-1">Επιλέξτε Κατασκευαστή</option>
                            @foreach ($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer['manufacturer_id'] }}">{{ $manufacturer['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Κατηγορία</label>
                        <select name="category" id="category" class="form-control kt-select2 trigger-redraw">
                            <option value="-1">Επιλέξτε Κατηγορία</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['category_id'] }}">{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enforce_order_by_qty">Ταξινόμηση ανά ποσότητα</label>
                        <select name="enforce_order_by_qty" id="enforce_order_by_qty" class="form-control">
                            <option value="-1">Όχι</option>
                            <option value="1">Ναι</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dimension_1">Διάσταση</label>
                        <input id="dimension_1" class="form-control" type="text" placeholder="Διάσταση">
                        <input id="dimension_2" class="form-control" type="text" placeholder="Διάσταση">
                        <input id="dimension_3" class="form-control" type="text" placeholder="Διάσταση">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-brand btn-elevate btn-icon-sm" id="search-products"><i class="la la-search"></i> Αναζήτηση</button>
                        <button type="button" class="btn btn-brand btn-default btn-icon-sm" id="clear-search-products"><i class="la la-close"></i> Καθαρισμός Αναζήτησης</button>
                    </div>
                </div>
            </div>
            <!--begin: Datatable -->
            <div class="link-search table-responsive">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                    <thead>
                        <tr>
                            <th>Διάσταση</th>
                            <th>Κατασκευαστής</th>
                            <th>'Ονομα</th>
                            <th>Δείκτες</th>
                            <th>Ποσότητες</th>
                            <th>Batch</th>
                            <th>Μελλοντικές Ποσότητες</th>
                            <th>Πλευρά</th>
                            <th>TT/TL</th>
                            <th>Περιγραφή</th>
                            <th>Κωδικός</th>
                            <th>Ενέργειες</th>
                        </tr>
                    </thead>
                </table>
            </div>

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
    <script src="{{ asset('assets/app/custom/product/list.js') }}?v=1.2" type="text/javascript"></script>
    <script>
        $("#dimension_1, #dimension_2, #dimension_3").keyup(function(event) {
            if (event.keyCode === 13) {
                $('#kt_table_1').DataTable().draw();
            }
        });
        function buildSearch() {
            var manufacturer = $('#manufacturer').val();
            var category = $('#category').val();
            var dimension_1 = $('#dimension_1').val();
            var dimension_2 = $('#dimension_2').val();
            var dimension_3 = $('#dimension_3').val();
            var search = $('.link-search input[type="search"]').val();

            window.history.replaceState(null, null, "?search=" + search + "&category="+category + "&manufacturer="+manufacturer+"&dimension_1="+dimension_1+"&dimension_2="+dimension_2+"&dimension_3="+dimension_3);
        }
        $(document).ready(function() {
            $('.kt-select2').select2();

            $('#search-products').click(function() {
                buildSearch();
                $('#kt_table_1').DataTable().draw();
            });

            $('#clear-search-products').click(function() {
                $('#manufacturer').val('-1').trigger('change');
                $('#category').val('-1').trigger('change');
                $('#dimension_1').val('').trigger('change');
                $('#dimension_2').val('').trigger('change');
                $('#dimension_3').val('').trigger('change');
                $('#kt_table_1').DataTable().draw();
            });
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            var searchArr = ['search','category','manufacturer','dimension_1','dimension_2','dimension_3'];
            for(var i = 0; i < searchArr.length; i++) {
                var key = searchArr[i];
                previous_search = urlParams.get(key);
                if(key == "search") {
                    if(previous_search != '' && previous_search != null) {
                        $('#kt_table_1').DataTable().search(previous_search);
                    }
                } else {
                    if(previous_search != "" && previous_search != null) {
                        $('#'+key).val(previous_search);
                    }
                }
            }
            $('#kt_table_1').DataTable().draw();

            $('body').on('change', '.link-search input[type="search"]', function() {
                var s = $(this).val();
                buildSearch();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#kt_table_1').on('draw.dt', function () {
                let atLeastOne = ($('#dimension_1').val() != '' || $('#dimension_2').val() != '' || $('#dimension_3').val() != '');
                let prevDimension = '';
                if(atLeastOne) {
                    $('#kt_table_1 tbody tr').each(function(i, el) {
                        let currentDimension = $($(this).find('.list-product-dim')).text().substr(0,7);
                        if(prevDimension != '' && prevDimension != currentDimension) {
                            $(this).addClass('add-separator');
                        }
                        prevDimension = currentDimension;
                    })
                }
            });
        });
    </script>
@endsection
