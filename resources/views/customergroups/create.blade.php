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
										Προσθήκη Ομάδας Πελατών
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
						<form data-redirect="/customergroups" class="kt-form kt-form--fit kt-form--label-right" id="kt_form" action="{{ $data['action'] }}" method="{{ $data['method'] }}" novalidate="novalidate">
							@csrf
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="name">Όνομα:</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" value="{!! !empty($data['customer_group']['name']) ? $data['customer_group']['name'] : '' !!}" name="name" id="name" placeholder="Όνομα Ομάδας Πελατών" aria-describedby="name-error" required>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="kt-portlet ">
											<div class="kt-portlet__head">
												<div class="kt-portlet__head-label">
													<h3 class="kt-portlet__head-title">
														Εκπτώσεις / Επιστροφές
													</h3>
												</div>
											</div>
											<div class="kt-portlet__body">
												<div class="kt-widget12">
													<div class="kt-widget12__content pb-0">
														<div class="kt-widget12__item">
															<div class="kt-widget12__info">
																<span class="kt-widget12__desc">Κατασκευαστής</span>
																<span class="kt-widget12__value" style="font-size: 1rem;">
																	<select id="manufacturer" class="form-control custom-select2">
																		<option value="-1">Επιλέξτε Κατασκευαστή</option>
																		@foreach($data['manufacturers'] as $manu)
																		<option value="{{ $manu['manufacturer_id'] }}">{{ $manu['name'] }}</option>
																		@endforeach
																	</select>
																</span>
															</div>
														</div>
														<div class="kt-widget12__item">
															<div class="kt-widget12__info">
																<span class="kt-widget12__desc">Κατηγορία</span>
																<span class="kt-widget12__value" style="font-size: 1rem;">
																	<select id="category" class="form-control custom-select2">
																		<option value="-1">Επιλέξτε Κατηγορία</option>
																		@foreach($data['categories'] as $cat)
																		<option value="{{ $cat['category_id'] }}">{{ $cat['name'] }}</option>
																		@endforeach
																	</select>
																</span>
															</div>
														</div>
														<div class="kt-widget12__item">
															<div class="kt-widget12__info">
																<span class="kt-widget12__desc">Ποσοστό Έκπτωσης</span>
																<span class="kt-widget12__value" style="font-size: 1rem;">
																	<input type="number" step="0.01" class="form-control" min='0' max="100" id="discount_perc" value='0'>
																</span>
															</div>
														</div>
														<div class="kt-widget12__item">
															<div class="kt-widget12__info">
																<span class="kt-widget12__desc">Ποσοστό Επιστροφής</span>
																<span class="kt-widget12__value" style="font-size: 1rem;">
																	<input type="number" step="0.01" class="form-control" min='0' max="100" id="return_perc" value='0'>
																</span>
															</div>
														</div>
														<div class="kt-widget12__item m-0">
															<div class="kt-widget12__info">
																<span class="kt-widget12__desc">
																	<button type="button" class="btn btn-primary btn-tall" id="add_discount_return">Προσθήκη</button>
																</span>
																<span class="kt-widget12__value"></span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<table class="table table-hover" id="discount_table">
											<thead>
												<tr>
													<th>Κατασκευαστής</th>
													<th>Κατηγορία</th>
													<th>% Έκπτωσης</th>
													<th>% Επιστροφής</th>
													<th>Ενέργεια</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
								<input type="hidden" name="discounts" id="discounts">
							</div>
							<div class="kt-portlet__foot kt-portlet__foot--fit-x">
								<div class="kt-form__actions">
									<div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
										{!! $data['isEdit'] ? 'Αποθήκευση' : 'Δημιουργία' !!}
									</div>
									<div>
										<a href="{{ route('customergroups.index') }}" class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">Ακύρωση</a>
									</div>
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
<script src="{{ asset('assets/app/custom/wizard/wizard-v3.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/vfs_fonts.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function() {
	let discounts = @json($data['discounts']);
	if(Array.isArray(discounts) || discounts == null) discounts = {};
	$('#discounts').val(encodeURIComponent(JSON.stringify(discounts)));
	let manufacturers = @json($data['manufacturers']);
	let categories = @json($data['categories']);
	$('.custom-select2').select2({});

	var discount_table = $('#discount_table').DataTable({
		responsive: true,
		// Pagination settings
		dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
		<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
		buttons: [
			'print',
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdfHtml5',
		],
		columns: [
			{data: 'manufacturer'},
			{data: 'category'},
			{data: 'discount'},
			{data: 'return'},
			{data: 'Ενέργειες', responsivePriority: -1},
		],
		columnDefs: [
			{
				targets: 0,
				'createdCell':  function (td, cellData, rowData, row, col) {
					$(td).data('tr-manufacturer', rowData['manufacturer']['id']);
				},
				render: function(data, type, full, meta) {
					return full['manufacturer']['name'];
				},
			},
			{
				targets: 1,
				'createdCell':  function (td, cellData, rowData, row, col) {
					$(td).data('tr-category', rowData['category']['id']);
				},
				render: function(data, type, full, meta) {
					return full['category']['name'];
				},
			},
			{
				targets: 2,
				render: function(data, type, full, meta) {
					return `
						<input type="number" step="0.01" value="${data}" class="discount-input form-control">
					`;
				},
			},
			{
				targets: 3,
				render: function(data, type, full, meta) {
					return `
						<input type="number" step="0.01" value="${data}" class="return-input form-control">
					`;
				},
			},
			{
				targets: -1,
				title: 'Ενέργειες',
				orderable: false,
				render: function(data, type, full, meta) {
					return `
						<button type="button" 
							class="btn btn-sm btn-clean btn-icon btn-icon-md pointer delete-discount"
						><i class="la la-trash"></i></button>`;
				},
			},
		],
	});

	function cleanNumber(value, number_type = 'int') {
		let val;
		if(number_type == 'int') {
			val = parseInt(value);
		} else {
			val = parseFloat(value);
		}
		if(isNaN(val)) return 0;
		return val;
	}

	function resetForm() {
		$('#return_perc').val(0);
		$('#discount_perc').val(0);
		$('#manufacturer').val(-1).trigger('change');
		$('#category').val(-1).trigger('change');
	}

	function addRow(data, manu_name, cat_name) {
		let rowData = {
			'manufacturer': {
				'id': data['manufacturer'],
				'name': manu_name
			},
			'category': {
				'id': data['category'],
				'name': cat_name
			},
			'discount': data['discount'],
			'return': data['return']
		};
		discount_table.rows.add([rowData]).draw();
	}

	for(let key in discounts) {
		if(discounts.hasOwnProperty(key)) {
			let d = discounts[key];
			let manufacturer = d.manufacturer;
			let category = d.category;

			let manu = manufacturers.find(m => m.manufacturer_id == manufacturer);
			let manufacturer_name = '';
			if(manu != null) {
				manufacturer_name = manu.name;
			}

			let cat = categories.find(c => c.category_id == category);
			let category_name = '';
			if(cat != null) {
				category_name = cat.name;
			}

			let data = {
				manufacturer,
				category,
				discount: d['discount'],
				return: d['return']
			};
			addRow(data, manufacturer_name, category_name)
		}
	}

	$('body').on('change', '.discount-input', function() {
		let row = discount_table.row( $(this).parents('tr') ).data();
		let manufacturer = row.manufacturer.id;
		let category = row.category.id;
		let key = manufacturer + '::' + category;
		if(discounts[key] != null && discounts[key] != undefined) {
			discounts[key]['discount'] = cleanNumber($(this).val(), 'float');
			$('#discounts').val(encodeURIComponent(JSON.stringify(discounts)));

		}
	});

	$('body').on('change', '.return-input', function() {
		let row = discount_table.row( $(this).parents('tr') ).data();
		let manufacturer = row.manufacturer.id;
		let category = row.category.id;
		let key = manufacturer + '::' + category;
		if(discounts[key] != null && discounts[key] != undefined) {
			discounts[key]['return'] = cleanNumber($(this).val(), 'float');
			$('#discounts').val(encodeURIComponent(JSON.stringify(discounts)));
		}
	});

	$('body').on('click', '.delete-discount', function() {
		let row = discount_table.row( $(this).parents('tr') ).data();
		let manufacturer = row.manufacturer.id;
		let category = row.category.id;
		let currentKey = manufacturer + '::' + category;
		if(discounts[currentKey] != undefined && discounts[currentKey] != null) {
			delete discounts[currentKey];
			$('#discounts').val(encodeURIComponent(JSON.stringify(discounts)));
		}
		discount_table.row( $(this).parents('tr') ).remove().draw();
	});

	$('#add_discount_return').click(function() {
		$(this).prop('disabled', true);
		let return_perc = cleanNumber($('#return_perc').val(), 'float');
		let discount_perc = cleanNumber($('#discount_perc').val(), 'float');
		let manufacturer = cleanNumber($('#manufacturer').val(), 'int');
		let category = cleanNumber($('#category').val(), 'int');

		let data = {
			'return': return_perc,
			'discount': discount_perc,
			'manufacturer': manufacturer,
			'category': category
		};

		if(manufacturer == -1 || category == -1) {
			let error_str = 'κατασκευαστή';
			if(category == -1) {
				error_str = 'κατηγορία';
			}

			Swal.fire({
				icon: 'error',
				title: 'Σφάλμα',
				text: 'Παρακαλώ επιλέξτε ' + error_str
			});
			$(this).prop('disabled', false);
			return;
		}

		let currentKey = manufacturer + '::' + category;
		if(discounts[currentKey] != null && discounts[currentKey] != undefined) {
			Swal.fire({
				icon: 'error',
				title: 'Σφάλμα',
				text: 'Η αντιστοιχία υπάρχει ήδη'
			});
			return;
		}

		discounts[''+currentKey] = data;
		$('#discounts').val(encodeURIComponent(JSON.stringify(discounts)));

		let manu_name = $('#manufacturer option:selected').text();
		let cat_name = $('#category option:selected').text();
		addRow(data, manu_name, cat_name);
		resetForm();
		$(this).prop('disabled', false);
	});

});
</script>
@endsection