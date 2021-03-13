@extends('layouts.app')
@section('custom_css')
<link href="{{ asset('assets/app/custom/wizard/wizard-v3.demo11.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
	<div class="kt-portlet">
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
				<div class="kt-grid__item">
					<!--begin: Form Wizard Nav -->
					<div class="kt-wizard-v3__nav">
						<div class="kt-wizard-v3__nav-items">
							<a class="kt-wizard-v3__nav-item" href="#" data-ktwizard-type="step" data-ktwizard-state="current">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										@if ($data['isEdit'])
											Επεξεργασία Προϊόντος
										@else
											Προσθήκη Προϊόντος
										@endif
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</a>
						</div>
					</div>
					<!--end: Form Wizard Nav -->
				</div>
				<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">
					<!--begin::Form-->
						<form data-redirect="/products" class="kt-form kt-form--fit kt-form--label-right" id="kt_form" action="{{ $data['action'] }}" method="{{ $data['method'] }}" novalidate="novalidate" style="width: 80%; padding-top: 0;">
							<div class="kt-portlet__body">
								@if ($data['isEdit'])
								<div class="row mb-4">
									<div class="col-lg-3">
										<div class="border">
											<h3 class="text-center">Barcode</h3>
											<div id="barcode_wrapper">
												<img style="max-width:100%;" id="barcode" class="d-block m-0 mx-auto printable">
											</div>
											<div class="mt-2">
												<button class="btn btn-primary kt-font-bold kt-font-transform-u d-block rounded-0 w-100" id="print_barcode"><i class="la la-print"></i> ΕΚΤΥΠΩΣΗ</button>
											</div>
										</div>
									</div>
									<div class="col-lg-3">
										<div class="kt-input-icon">
											@if(!empty($data['product']['image']))
												<div class="image-holder">
													<img src="{{url('/images/' . $data["product"]["image"])}}" alt="{!! !empty($data['product']['name']) ? $data['product']['name'] : '' !!}" />
												</div>
												<input type="file" class="custom-file-input" name="image" id="productImage">
											@else
												<input type="file" class="custom-file-input" name="image" id="productImage">
												<label style="text-align:left;" class="custom-file-label" for="productImage">Ανέβασε μία εικόνα</label>
											@endif
										</div>
									</div>
								</div>
								@endif
								<div class="row">
									<div class="form-group col-lg-3">
										<label for="manufacturer_id">Κατασκευαστής:</label>
										<select name="manufacturer_id" id="manufacturer_id" class="form-control kt-select2">
											<option value="-1">Επιλέξτε Κατασκευαστή</option>
											@foreach ($data['manufacturers'] as $manufacturer)
												<option value="{{ $manufacturer['manufacturer_id'] }}"
													@if (!empty($data['product']) && $data['product']['manufacturer_id'] == $manufacturer['manufacturer_id'])
														selected
													@endif
												>{{ $manufacturer['name'] }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-lg-3">
										<label for="category_id">Κατηγορία:</label>
										<select name="category_id" id="category_id" class="form-control kt-select2">
											<option value="-1">Επιλέξτε Κατηγορία</option>
											@foreach ($data['categories'] as $category)
												<option value="{{ $category['category_id'] }}"
													@if (!empty($data['product']) && $data['product']['category_id'] == $category['category_id'])
														selected
													@endif
												>{{ $category['name'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-lg-6">
										<label for="name">Όνομα:</label>
										<input type="text" class="form-control" name="name" id="name" value="{{ (!empty($data['product'])) ? $data['product']['name'] : '' }}" placeholder="Όνομα προϊόντος" aria-describedby="name-error">
									</div>
									<div class="form-group col-lg-6">
										<label for="model">Μοντέλο:</label>
										<input type="text" class="form-control" name="model" id="model" value="{{ (!empty($data['product'])) ? $data['product']['model'] : '' }}" placeholder="Μοντέλο προϊόντος" aria-describedby="model-error" required>
										<span class="form-text text-muted">Εισάγεται το μοναδικό μοντέλο προϊόντος</span>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4">
										<label for="buying_price">Τιμή Αγοράς:</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text" id="basic-addon1">&euro;</span>
											</div>
											<input type="number" min="0" step=".01" class="form-control" name="buying_price" id="buying_price" value="{{ (!empty($data['prices'])) ? $data['prices']->buying_price : '' }}" placeholder="Τιμή Αγοράς" aria-describedby="buying_price-error">
										</div>
									</div>
									<div class="form-group col-md-4">
										<label for="general_price">Τιμή Λιανικής:</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text" id="basic-addon1">&euro;</span>
											</div>
											<input type="number" min="0" step=".01" class="form-control" name="general_price" id="general_price" value="{{ (!empty($data['prices'])) ? $data['prices']->general_price : '' }}" placeholder="Τιμή Λιανικής" aria-describedby="general_price-error" required>
										</div>
									</div>
									<div class="form-group col-md-4">
										<label for="wholesale_price">Τιμή Χονδρικής:</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text" id="basic-addon1">&euro;</span>
											</div>
											<input type="number" min="0" step=".01" class="form-control" name="wholesale_price" id="wholesale_price" value="{{ (!empty($data['prices'])) ? $data['prices']->wholesale_price : '' }}" placeholder="Τιμή Χονδρικής" aria-describedby="wholesale_price-error">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="description">Περιγραφή:</label>
										<textarea class="form-control" name="description" id="description" cols="30" rows="10" aria-describedby="description-error">{{ (!empty($data['product'])) ? $data['product']['description'] : '' }}</textarea>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-lg-4">
										<label for="width">Φάρδος:</label>
										<input type="text" class="form-control" name="width" id="width" value="{{ (!empty($data['product'])) ? $data['product']['width'] : '' }}" placeholder="Φάρδος προϊόντος" aria-describedby="width-error" required>
									</div>
									<div class="form-group col-lg-4">
										<label for="height_percentage">Ποσοστό Ύψους:</label>
										<input type="number" min="0" max="100" step="1" class="form-control" name="height_percentage" id="height_percentage" value="{{ (!empty($data['product'])) ? $data['product']['height_percentage'] : '' }}" placeholder="Ποσοστό Ύψους" aria-describedby="height_percentage-error">
									</div>
									<div class="form-group col-lg-4">
										<label for="radial_structure">Δομή Radial:</label>
										<input type="text" class="form-control" name="radial_structure" id="radial_structure" value="{{ (!empty($data['product'])) ? $data['product']['radial_structure'] : '' }}" placeholder="Δομή Radial" aria-describedby="radial_structure-error" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-lg-4">
										<label for="diameter">Διάμετρος:</label>
										<input type="number" min="0" step="0.001" class="form-control" name="diameter" id="diameter" value="{{ (!empty($data['product'])) ? $data['product']['diameter'] : '' }}" placeholder="Διάμετρος προϊόντος" aria-describedby="diameter-error" required>
									</div>
									<div class="form-group col-lg-4">
										<label for="fitting_position">Πλευρά τοποθέτησης:</label>
										<select class="form-control" name="fitting_position" id="fitting_position" aria-describedby="fitting_position-error" required>
											<option value="1"
												@if (!empty($data['product']) && $data['product']['fitting_position'] == 1)
													selected
												@endif
											>Μπροστά</option>
											<option value="2"
												@if (!empty($data['product']) && $data['product']['fitting_position'] == 2)
												selected
												@endif
											>Πίσω</option>
											<option value="3"
												@if (!empty($data['product']) && $data['product']['fitting_position'] == 3)
													selected
												@endif
											>Και στις δύο</option>
										</select>
									</div>
									<div class="form-group col-lg-4">
										<label for="tube_type">TL/TT:</label>
										<select class="form-control" name="tube_type" id="tube_type" aria-describedby="tube_type-error" required>
											<option value="0"
												@if (!empty($data['product']) && $data['product']['tube_type'] == 0)
													selected
												@endif
											>TL</option>
											<option value="1"
												@if (!empty($data['product']) && $data['product']['tube_type'] == 1)
													selected
												@endif
											>TT</option>
											<option value="2"
												@if (!empty($data['product']) && $data['product']['tube_type'] == 2)
													selected
												@endif
											>TL/TT</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-lg-4">
										<label for="speed_flag">Δείκτης Ταχύτητας:</label>
										<input type="text" class="form-control" name="speed_flag" id="speed_flag" value="{{ (!empty($data['product'])) ? $data['product']['speed_flag'] : '' }}" placeholder="Δείκτης Ταχύτητας" aria-describedby="speed_flag-error" required>
									</div>
									<div class="form-group col-lg-4">
										<label for="weight_flag">Δείκτης Βάρους:</label>
										<input type="text" class="form-control" name="weight_flag" id="weight_flag" value="{{ (!empty($data['product'])) ? $data['product']['weight_flag'] : '' }}" placeholder="Δείκτης Βάρους" aria-describedby="weight_flag-error" required>
									</div>
									<div class="form-group col-lg-4">
										<label for="">
											Is Heavy?
										</label>
										<div class="kt-radio-inline">
											<label class="kt-radio">
												<input type="radio" name="is_heavy" value="0"
													@if (!empty($data['product']) && $data['product']['is_heavy'] == 0)
														checked
													@endif
												>
												Οχι
												<span></span>
											</label>
											<label class="kt-radio">
												<input type="radio" name="is_heavy" value="1"
													@if (!empty($data['product']) && $data['product']['is_heavy'] == 1)
														checked
													@endif
												>
												Ναι
												<span></span>
											</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4">
										<label for="notify_quantity">Ειδοποίηση ποσότητας κάτω από:</label>
										<input type="number" min="0" step="1" name="notify_quantity" id="notify_quantity" value="{{ (!empty($data['product'])) ? $data['product']['notify_quantity'] : '' }}" class="form-control">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="comments">Σχόλια:</label>
										<textarea class="form-control" name="comments" id="comments" cols="30" rows="10" aria-describedby="comments-error">{{ (!empty($data['product'])) ? $data['product']['comments'] : '' }}</textarea>
									</div>
								</div>
							</div>

							<div class="kt-portlet__body">
								<h3 class="kt-portlet__head-title">
									Ποσότητα
								</h3>
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover data-table kt-datatable__table" id="quantity_table__wrapper">
										<thead>
											<tr>
												<th>Αποθήκη</th>
												<th>Ράφι</th>
												<th>Batch</th>
												<th>Stock</th>
												<th>Ενέργειες</th>
											</tr>
										</thead>
										<tbody id="quantity_table">
											@foreach($quantities as $qty)
											<tr>
												<td>
													<input type="hidden" name="qty[{{$loop->index}}][product_quantity_id]" value="{{  $qty->product_quantity_id }}" />
													<select name="qty[{{$loop->index}}][warehouse_id]" class="form-control warehouse-select2" disabled required>
														@foreach($warehouses as $warehouse)
															<option value="{{ $warehouse->warehouse_id }}"
																@if($warehouse->warehouse_id == $qty->warehouse_id)
																	selected
																@endif
															>{{ $warehouse->name }}</option>
														@endforeach
													</select>
												</td>
												<td>
													<select name="qty[{{$loop->index}}][shelf_id]" class="form-control shelf-select2" data-warehouse-id="{{ $qty->warehouse_id }}" disabled required>
														<option value="{{ $qty->shelf()->get()[0]->shelf_id }}" selected>{{ $qty->shelf()->get()[0]->name }}</option>
													</select>
												</td>
												<td><input type="number" value="{{ $qty->batch }}" name="qty[{{$loop->index}}][batch]" min="0" max="10000" class="form-control" disabled required></td>
												<td><input type="number" value="{{ $qty->stock }}" name="qty[{{$loop->index}}][stock]" min="0" step="1" class="form-control" disabled required></td>
												<td>
													<div class="d-flex justify-content-between">
														<button type="button" class="btn btn-sm btn-success btn-icon btn-icon-md edit-qty" title="Επεξεργασία"
															data-quantity-id="{{ $qty->product_quantity_id }}"
														><i class="la la-edit"></i></button>
														<button type="button" class="btn btn-sm btn-danger btn-icon btn-icon-md delete-qty" title="Διαγραφή"
															data-quantity-id="{{ $qty->product_quantity_id }}"
														><i class="la la-trash"></i></button>
													</div>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="mt-2">
									<button type="button" class="btn btn-primary kt-font-bold kt-font-transform-u d-block rounded-0" id="add-qty"><i class="la la-plus"></i> Προσθήκη Ποσότητας</button>
								</div>
							</div>
							<div class="kt-portlet__body">
								<h3 class="kt-portlet__head-title">
									Μελλοντική Ποσότητα
								</h3>
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover data-table kt-datatable__table" id="future_quantity_table__wrapper">
										<thead>
											<tr>
												<th>Stock</th>
												<th>Ημ/νία Παραλαβής</th>
												<th>Ενέργειες</th>
											</tr>
										</thead>
										<tbody id="future_quantity_table">
											@foreach($future_quantities as $future_qty)
											<tr>
												<td>
													<input type="number" value="{{ $future_qty->stock }}" name="future_qty[{{$loop->index}}][stock]" class="form-control" placeholder="Ποσότητα" min="1" step="1" readonly disabled autocomplete="off">
												</td>
												<td><input name="future_qty[{{$loop->index}}][arrival_date]" type="text" class="form-control datepicker" value="{{ date('Y-m-d', strtotime($future_qty->arrival_date)) }}" readonly disabled autocomplete="off"></td>
												<td>
													<div class="d-flex justify-content-between">
														<button type="button" class="btn btn-sm btn-success btn-icon btn-icon-md edit-future-qty" title="Επεξεργασία"
															data-future-quantity-id="{{ $future_qty->product_future_quantity_id }}"
														><i class="la la-edit"></i></button>
														<button type="button" class="btn btn-sm btn-danger btn-icon btn-icon-md delete-future-qty" title="Διαγραφή"
															data-future-quantity-id="{{ $future_qty->product_future_quantity_id }}"
														><i class="la la-trash"></i></button>
													</div>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="mt-2">
									<button type="button" class="btn btn-primary kt-font-bold kt-font-transform-u d-block rounded-0" id="add-future-qty"><i class="la la-plus"></i> Προσθήκη Μελλοντικής Ποσότητας</button>
								</div>
							</div>

							<div class="kt-portlet__foot kt-portlet__foot--fit-x">
								<div class="kt-form__actions">
									<div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
										{!! $data['isEdit'] ? 'Αποθήκευση' : 'Δημιουργία' !!}
									</div>
									<a href="{{ route('products.index') }}" class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">Ακύρωση</a>
								</div>
							</div>
						</form>

						<!--end::Form-->
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('custom_script')
<script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/app/custom/wizard/wizard-v3.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/JsBarcode.all.min.js') }}" type="text/javascript"></script>
<script>
	@if ($data['isEdit'])
		var quantities = {{ count($quantities) }};
		var future_quantities = {{ count($future_quantities) }};
	@else
		var quantities = 0;
		var future_quantities = 0;
	@endif

$(document).ready(function() {
	$('.kt-select2').select2();
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd'
	});
	@if (!empty($data['product']))
		JsBarcode("#barcode", "product::{{ $data['product']['id'] }}", {
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

	var future_qty_table = $('#future_quantity_table__wrapper').DataTable();
	var qty_table = $('#quantity_table__wrapper').DataTable();

	let json_warehouses = {!! $json_warehouses !!};
	let json_shelves = {!! $json_shelves !!};

	$('#add-qty').click(function() {
		let counter = qty_table.rows().data().length;

		let warehouse_str = `<select name="qty[`+quantities+`][warehouse_id]" data-index="${counter}" class="form-control warehouse-select2" required>`;
		let selected_warehouse = -1;
		json_warehouses.map(w => {
			if(selected_warehouse == -1) selected_warehouse = w.warehouse_id;
			warehouse_str += `<option value="${w.warehouse_id}">${w.name}</option>`;
		})
		warehouse_str += `</select>`;

        qty_table.row.add( [
			warehouse_str,
			`<select name="qty[`+quantities+`][shelf_id]" class="form-control shelf-select2" data-index="${counter}" data-warehouse-id="${selected_warehouse}" required></select>`,
			`<input type="number" value="" min="0" max="10000" name="qty[`+quantities+`][batch]" class="form-control" required>`,
			`<input type="number" value="" name="qty[`+quantities+`][stock]" min="0" step="1" class="form-control" required>`,
			`
				<div class="d-flex justify-content-between">
					<button type="button" class="btn btn-sm btn-danger btn-icon btn-icon-md delete-qty" title="Διαγραφή"><i class="la la-trash"></i></button>
				</div>
			`
		] ).draw( false );

		$(".shelf-select2[data-index='" + counter + "']").select2({
			placeholder: 'Παρακαλώ επιλέξτε ράφι.'
		});

		$(".warehouse-select2[data-index='" + counter + "']").select2({
			placeholder: 'Παρακαλώ επιλέξτε αποθήκη.'
		});

		addShelfOptions(selected_warehouse, ".shelf-select2[data-index='" + counter + "']")
		quantities++;
	});

	$('.warehouse-select2').select2({
		placeholder: 'Παρακαλώ επιλέξτε αποθήκη'
	})

	function addShelfOptions(selected_warehouse, shelf_select) {
		let warehouse_shelves = json_shelves.filter(shelf => shelf.warehouse_id == selected_warehouse);
		let options = '';

		warehouse_shelves = warehouse_shelves.sort(function(a, b) {
			return a.name.localeCompare(b.name, undefined, {numeric: true});
		});

		warehouse_shelves.map(s => {
			options += '<option value="' + s.shelf_id + '">' + s.name +  '</option>';
		});

		$(shelf_select).find('option').remove();

		$(shelf_select).append(options).val("").trigger("change");
	}

	$('body').on('change', '.warehouse-select2', function() {
		let selected_warehouse = $(this).val();
		let tr = $(this).parents('tr');

		let shelf_select = tr.find('.shelf-select2');
		shelf_select.data('warehouse-id', selected_warehouse);

		addShelfOptions(selected_warehouse, shelf_select);
	})

	@if (!$data['isEdit'])
		$('.shelf-select2').each(function(i, el) {
			let selected_warehouse = $(this).data('warehouse-id');
			$(this).select2({
				placeholder: 'Παρακαλώ επιλέξτε ράφι.'
			});
			addShelfOptions(selected_warehouse, this);
		});
	@endif

	$('body').on('click', '.edit-qty', function() {
		$(this).css('visibility', 'hidden');
		let tr = $(this).parents('tr');
		tr.find('input').prop('disabled', false).prop('readonly', false);
		tr.find('select').prop('disabled', false).prop('readonly', false);
	})

	$('body').on('click', '.delete-qty', function() {
		let self = this;
		Swal.fire({
			title: 'Είστε σίγουρος?',
			text: "Διαγραφή της ποσότητας?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ναι, διαγραφή!'
		}).then((result) => {
			if (result.value) {
				qty_table
					.row( $(self).parents('tr') )
					.remove()
					.draw();
			}
		})
	});

	$('#add-future-qty').click(function() {
        future_qty_table.row.add( [
            `<input type="number" name="future_qty[`+future_quantities+`][stock]" class="form-control" placeholder="Ποσότητα" min="1" step="1" autocomplete="off">`,
            `<input name="future_qty[`+future_quantities+`][arrival_date]" type="text" class="form-control datepicker new-datepicker" placeholder="Ημ/νία Παραλαβής" autocomplete="off">`,
			`
				<div class="d-flex justify-content-between">
					<button type="button" class="btn btn-sm btn-danger btn-icon btn-icon-md delete-future-qty" title="Διαγραφή"><i class="la la-trash"></i></button>
				</div>
			`
		] ).draw( false );
		$('.new-datepicker').datepicker({
			format: 'yyyy-mm-dd'
		});
		future_quantities++;
	});

	$('body').on('click', '.edit-future-qty', function() {
		$(this).css('visibility', 'hidden');
		let tr = $(this).parents('tr');
		tr.find('input').prop('disabled', false).prop('readonly', false);
	});


	$('body').on('click', '.delete-future-qty', function() {
		let self = this;
		Swal.fire({
			title: 'Είστε σίγουρος?',
			text: "Διαγραφή της μελλοντικής ποσότητας?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ναι, διαγραφή!'
		}).then((result) => {
			if (result.value) {
				future_qty_table
					.row( $(self).parents('tr') )
					.remove()
					.draw();
			}
		})
	});
});
</script>
@endsection
