@extends('layouts.app')
@section('custom_css')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .delete-future-qty i {
            color: var(--danger) !important;
        }
    </style>
@endsection

@section('content')
<script src="{{ asset('assets/app/custom/quagga.min.js') }}"></script>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Λίστα Μελλοντικών Προϊόντων
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_filter">Ημ/νία Παραλαβής</label>
                        <input type="text" class="form-control" name="date_filter" id="date_filter">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-brand btn-elevate btn-icon-sm" id="search-future_qty"><i class="la la-search"></i> Αναζήτηση</button>
                        <a style="display:none;" id="new-product-scan" href="#" class="btn btn-primary">Scan</a>
                        <button type="button" class="btn btn-brand btn-default btn-icon-sm" id="clear-search-future_qty"><i class="la la-close"></i> Καθαρισμός Αναζήτησης</button>
                    </div>
                </div>
            </div>
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>Προϊόν</th>
                        <th>Stock</th>
                        <th>Ημ/νία Παραλαβής</th>
                        <th>Ενέργειες</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>
<div id="camera-input" style="
	width: 100%;
    display: none !important;
    z-index: 99998;
    height: 100%;
    top: 0px;
    left: 0px;
	right: 0;
	bottom: 0;
	position: fixed;
	background:white;
	display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex;
    -webkit-align-items: center; -moz-align-items: center; -ms-align-items: center; align-items: center;
	-webkit-flex-direction: column-reverse; -moz-flex-direction: column-reverse; -ms-flex-direction: column-reverse; flex-direction: column-reverse;">
    <div id="camera-feedback"></div>
    <div id="bottom-scanner" style="position: absolute; bottom: 10px; left: 0; right: 0; z-index: 99999; display: flex; justify-content: center;">
        <button id="close-scan" style="width: 120px; " class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
            Κλείσιμο
        </button>
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
    <script>
        window.warehouses = {!! $warehouses !!};
        window.BASE_URL = BASE_URL;
    </script>
    <script src="{{ asset('assets/app/custom/product/future_quantities_list.js') }}?v=1" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $('#search-future_qty').click(function() {
                $('#kt_table_1').DataTable().draw();
            });

            $('#clear-search-future_qty').click(function() {
                $('#date_filter').val('').trigger('change');
                $('#kt_table_1').DataTable().draw();
            });

            $('#date_filter').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
        var _scannerIsRunning = false;
        var App = {
            init: function() {
                var self = this;
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector('#camera-feedback'),
                        constraints: {
                            facingMode: "environment"
                        },
                    },
                    decoder: {
                        readers: [
                            "code_128_reader"
                        ],
                        debug: {
                            showCanvas: true,
                            showPatches: true,
                            showFoundPatches: true,
                            showSkeleton: true,
                            showLabels: true,
                            showPatchLabels: true,
                            showRemainingPatchLabels: true,
                            boxFromPatches: {
                                showTransformed: true,
                                showTransformedBox: true,
                                showBB: true
                            }
                        }
                    },

                }, function (err) {
                    if (err) {
                        window.alert(err);
                        return
                    }
                });
            }};
        $( document ).ready(function() {
            if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
                || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)))
            {
                $('#new-product-scan').show();
                App.init();
                Quagga.onProcessed(function (result) {
                    var drawingCtx = Quagga.canvas.ctx.overlay,
                        drawingCanvas = Quagga.canvas.dom.overlay;

                    if (result) {
                        if (result.boxes) {
                            drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                            result.boxes.filter(function (box) {
                                return box !== result.box;
                            }).forEach(function (box) {
                                Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
                            });
                        }

                        if (result.box) {
                            Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
                        }

                        if (result.codeResult && result.codeResult.code) {
                            Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
                        }
                    }
                });

                Quagga.onDetected(function (result)
                {
                    let result_code = result.codeResult.code;
                    if(result_code.includes("product::"))
                    {
                        closeQuagga()
                        let product_id = result_code.split("::")[1];
                        addQuantityProduct(product_id);
                    }
                });

                function closeQuagga() {
                    Quagga.stop();
                    _scannerIsRunning = false;
                    $( "#camera-input" ).hide();
                    $('#camera-feedback').html('');
                }
            }
        });
        $( "#new-product-scan , #close-scan" ).click(function()
        {
            if (_scannerIsRunning)
            {
                $("#camera-input").hide();
                $('#camera-feedback').html('');
                Quagga.stop();
                _scannerIsRunning = false;
            }
            else
            {
                App.init();
                Quagga.start();
                $( "#camera-input" ).show();
                _scannerIsRunning = true;
            }
        });

        function addQuantityProduct(product_id)
        {
            let id = product_id;
            let stock = $(this).data('stock');
            let warehouses = window.warehouses;
            let warehouse_options = '<option value="-1">Επιλέξτε Αποθήκη</option>';
            warehouses.map(w => warehouse_options += "<option value='" + w.warehouse_id + "'>" + w.name + "</option>");

            let weekOfYear = moment().week();
            weekOfYear += '';
            if(weekOfYear.length == 1) weekOfYear = '0' + weekOfYear;
            let year = moment().year();
            year += '';
            year = year.substr(2);

            let batch = year + weekOfYear;
            let swal_product_name = '';
            $.ajax({
                url: BASE_URL + '/products/search/?product_id=' + product_id,
                dataType: 'json',
                method: 'GET',
                async: false
            })
            .done(function(res) {
                if(res.products && res.products.length > 0) {
                    let p = res.products[0];

                    swal_product_name = p.manufacturer_name + ' ' + p.name + ' ' + p.width + '/' + p.height_percentage + p.radial_structure + p.diameter + ' ' + p.weight_flag + p.speed_flag + ' ';
                    if(p.fitting_position == 1) swal_product_name += 'Ε';
                    else if(p.fitting_position == 2) swal_product_name += 'Π';
                    else swal_product_name += 'Ε/Π';
                    swal_product_name += ' ';
                    if(p.tube_type == 0) swal_product_name += 'TL';
                    else if(p.tube_type == 2) swal_product_name += 'TT';
                    else swal_product_name += 'TL/TT';
                    swal_product_name += ' ' + p.model;
                }
            })
            .fail(function(err) {
                console.log(err);
            })
            Swal.fire({
                title: 'Εφαρμογή Μελλοντικής Ποσότητας',
                html: `
		<div class="row">
				<div class="col-md-12">
					<label for="pp">Προϊόν</label>
					<p>${swal_product_name}</p>
				</div>
				<div class="col-md-12">
					<label for="warehouse_id">Αποθήκη</label>
					<select class="form-control custom-select2" id="warehouse_id" name="warehouse_id">${warehouse_options}</select>
				</div>
				<div class="col-md-12">
					<label for="shelf_id">Ράφι</label>
					<select class="form-control custom-select2" id="shelf_id" name="shelf_id"></select>
				</div>
				<div class="col-md-12">
					<label for="batch">Batch</label>
					<input type="number" class="form-control" id="batch" name="batch" value="${batch}">
				</div>
				<div class="col-md-12">
					<label for="stock">Stock</label>
					<input type="number" class="form-control" id="stock" name="stock" value="">
				</div>
			</div>
		`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Εφαρμογή',
                onOpen: function() {
                    $('.custom-select2').select2({
                        dropdownCssClass: 'increasedzindexclass'
                    });

                    $('#warehouse_id').change(function() {
                        let warehouse_id = $(this).val();
                        if(warehouse_id == -1) {
                            $('#shelf_id').html('');
                            return;
                        }

                        $.ajax({
                            url: window.BASE_URL + '/warehouses/findShelves',
                            method: 'GET',
                            data: {
                                warehouse_id
                            },
                            dataType: 'json'
                        })
                            .done(function(res) {
                                if(res.shelves) {
                                    let shelves_options = '';
                                    res.shelves.map(s => {
                                        shelves_options += "<option value='" + s.shelf_id + "'>" + s.name + "</option>";
                                    })
                                    $('#shelf_id').html(shelves_options);
                                } else {
                                    $('#shelf_id').html('');
                                }
                            })
                            .fail(function(err) {
                                $('#shelf_id').html('');
                            })
                    })
                },
                preConfirm: function() {
                    let warehouse_id = Swal.getPopup().querySelector('#warehouse_id').value;
                    warehouse_id = parseInt(warehouse_id);
                    if(isNaN(warehouse_id) || warehouse_id < 1) {
                        Swal.showValidationMessage(`Επιλέξτε μία αποθήκη`);
                        return;
                    }

                    let shelf_id = Swal.getPopup().querySelector('#shelf_id').value;
                    shelf_id = parseInt(shelf_id);
                    if(isNaN(shelf_id) || shelf_id < 1) {
                        Swal.showValidationMessage(`Επιλέξτε ένα ράφι`);
                    }

                    let batch = Swal.getPopup().querySelector('#batch').value;
                    batch = parseInt(batch);
                    if(isNaN(batch) || batch < 1) {
                        Swal.showValidationMessage(`Εισάγεται batch`);
                    }

                    let stock = Swal.getPopup().querySelector('#stock').value;
                    stock = parseInt(stock);
                    if(isNaN(stock) || stock < 1) {
                        Swal.showValidationMessage(`Εισάγεται stock`);
                    }

                    return {
                        warehouse_id,
                        shelf_id,
                        batch,
                        stock,
                    };
                }
            }).then((result) => {
                if(result.value) {
                    let warehouse_id = result.value.warehouse_id;
                    let shelf_id = result.value.shelf_id;
                    let batch = result.value.batch;
                    let stock = result.value.stock;
                    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    let data = {
                        _token: CSRF_TOKEN,
                        warehouse_id,
                        shelf_id,
                        batch,
                        stock,
                        product_id: product_id
                    };

                    let formdata = new FormData();
                    Object.keys(data).map(o => {
                        formdata.append(o, data[o]);
                    })

                    $.ajax({
                        url: BASE_URL + '/products/applyQuantity',
                        method: 'POST',
                        data: formdata,
                        contentType: false,
                        processData: false
                    })
                        .done(function(res) {
                            if(res.success || res.status == 'success') {
                                window.alert('Η ποσότητα προστέθηκε!')
                            }
                            else
                            {
                                window.alert(res);
                            }
                        })
                        .fail(function(err) {
                            window.alert(err);
                        })
                }
            });
        }
    </script>
@endsection
