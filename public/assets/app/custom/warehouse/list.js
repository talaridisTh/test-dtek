"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
		var table = $('#kt_table_1');

		// begin first table
		table.DataTable({
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
			searchDelay: 1500,
			processing: true,
			serverSide: true,
			ajax: {
				"url": BASE_URL + "/warehouses",
				"dataSrc": "data.data"
			},
			columns: [
				{data: 'warehouse_id'},
				{data: 'name'},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const warehouseId = full.warehouse_id;
						return `

							<a class="btn btn-sm btn-clean btn-icon btn-icon-md" class="dropdown-item" href="` + BASE_URL + `/warehouses/` + warehouseId + `" title="Προβολή Αποθήκης">
								<i class="la flaticon-interface-11"></i></a>
							<a href="` + BASE_URL + `/warehouses/` + warehouseId + `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Επεξεργασία Αποθήκης">
								<i class="la la-edit"></i>
							</a>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md delete-warehouse pointer" data-id="` + warehouseId + `" title="Διαγραφή Αποθήκης">
								<i class="la la-trash"></i>
							</span>`;
					},
				},
			],
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesDataSourceAjaxServer.init();
});

$('body').on('click', '.delete-warehouse', function() {
	let id = $(this).data('id');
	deleteModal("της αποθήκης", "Η αποθήκη", id, "/warehouses/");
});