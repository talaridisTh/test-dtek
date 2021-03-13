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
				"url": BASE_URL + "/shelves",
				"dataSrc": "data.data"
			},
			columns: [
				{data: 'shelf_id'},
                {data: 'name'},
                {data: 'warehouse_name'},
                {
					"data": 'is_product_shelf', 
					"render": function ( data, type, row, meta ) {
						if(data == 1) {
							return 'Ράφι Προϊόντος';
						}else if (data == 0) {
							return 'Ράφι Αναμονής';
						}else {
							return '-';
						}
					}
				},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const shelfId = full.shelf_id;
						return `
							<a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="` + BASE_URL + `/shelves/` + shelfId + `" title="Προβολή Ραφιού">
								<i class="la flaticon-interface-11"></i>
							</a>
							<a href="` + BASE_URL + `/shelves/` + shelfId + `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Επεξεργασία Ραφιού">
								<i class="la la-edit"></i>
							</a>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md delete-shelf pointer" data-id="` + shelfId + `" title="Διαγραφή Ραφιού">
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


$('body').on('click', '.delete-shelf', function() {
	let id = $(this).data('id');
	deleteModal("του ραφιού", "Το ράφι", id, "/shelves/");
});