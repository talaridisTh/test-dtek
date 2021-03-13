"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
		var table = $('#kt_table_1');
		var cid = $('#customer_id').val();
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
				"url": BASE_URL + "/addresses",
				"data": function(json) {
					json.customer_id = cid;
					return json;
				},
				"dataSrc": "data.data"
			},
			columns: [
				{data: 'address_id'},
				{data: 'firstname'},
				{data: 'lastname'},
				{data: 'company'},
				{data: 'address_1'},
				{data: 'address_2'},
				{data: 'city'},
				{data: 'postcode'},
				{data: 'country_name'},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const addressId = full.address_id;
						return `
							<a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="` + BASE_URL + `/address/` + addressId + `" title="Προβολή Διεύθυνσης">
								<i class="la flaticon-interface-11"></i>
							</a>
							<a class="btn btn-sm btn-clean btn-icon btn-icon-md edit-address-link" href="#" data-address-id="` + addressId + `" title="Επεξεργασία Διεύθυνσης">
								<i class="la la-edit"></i>
							</a>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md delete-address-link pointer" data-id="` + addressId + `" title="Διαγραφή Διεύθυνσης">
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
