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
				"url": BASE_URL + "/customers",
				"dataSrc": "data.data"
			},
			columns: [
				{data: 'customer_id'},
				{data: 'customer_name'},
				{data: 'tax_name'},
				{data: 'phone'},
				{data: 'mobile'},
				{data: 'company_name'},
				{data: 'email'},
				{data: 'fax'},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const customerId = full.customer_id;
						return `
							<a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="` + BASE_URL + `/customers/` + customerId + `" title="Προβολή Πελάτη">
								<i class="la flaticon-interface-11"></i>
							</a>
							<a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="` + BASE_URL + `/customers/` + customerId + `/edit" title="Επεξεργασία Πελάτη">
								<i class="la la-edit"></i>
							</a>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md delete-customer pointer" data-id="` + customerId + `" title="Διαγραφή Πελάτη">
								<i class="la la-trash"></i>
							</span>`;
					},
				},
			],
		}).order( [ 1, 'asc' ] ).draw();
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

$('body').on('click', '.delete-customer', function() {
	let id = $(this).data('id');
	deleteModal("του πελάτη", "Ο πελάτης", id, "/customers/");
});
