@extends('layouts.app')

@section('custom_css')
<link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
    <div class="row mb-4">
        <div class="col-xl-12">
            <a href="{{ route('customergroups.edit', $group['customer_group_id']) }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                <i class="la la-edit"></i> Επεξεργασία Ομάδας Πελατών
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Widgets/Invoice Details-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Λεπτομέρειες Ομάδας Πελατών
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-4 col-sm-12">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Όνομα</span>
                                        <span class="kt-widget12__value">{!! !empty($group['name']) ? $group['name'] : '-' !!}</span>
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
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Invoice Details-->
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
<script>
$(document).ready(function() {
	let discounts = @json($data['discounts']);
	if(Array.isArray(discounts) || discounts == null) discounts = {};
	$('#discounts').val(encodeURIComponent(JSON.stringify(discounts)));
	let manufacturers = @json($data['manufacturers']);
	let categories = @json($data['categories']);

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
                        <span>${data}</span>
					`;
				},
			},
			{
				targets: 3,
				render: function(data, type, full, meta) {
					return `
						<span>${data}</span>
					`;
				},
			}
		],
	});

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
});
</script>
@endsection