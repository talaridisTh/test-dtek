"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
		var table = $('#kt_table_1');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				"url": BASE_URL + "/custromers",
				"dataSrc": "data.data"
			},
			columns: [
				{data: 'id'},
				{data: 'company_name'},
				{data: 'tax_id'},
				{data: 'communications_manager_phone'},
				{data: 'communications_manager'},
				{data: 'Actions', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: -1,
					title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
						const custromerId = full.id;
						return `
							<span class="dropdown">
									<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
										<i class="la la-ellipsis-h"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href="` + BASE_URL + `/custromers/` + custromerId + `"><i class="la flaticon-interface-11"></i> View Custromer</a>
											<a class="dropdown-item" href="#"><i class="la la-print"></i> Generate Report</a>
									</div>
							</span>
							<a href="` + BASE_URL + `/custromers/` + custromerId + `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit custromer">
								<i class="la la-edit"></i>
							</a>`;
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