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
				"url": BASE_URL + "/orders",
				"dataSrc": "data.data",
				"data": function ( d ) {
					d.order_status = $('#search_order_status').val();
					d.from = $('#search_from').val();
					d.to = $('#search_to').val();
				}
			},
			columns: [
				{data: 'order_id'},
				{data: 'customer_name'},
				{data: 'order_total'},
				{data: 'paid'},
				{data: 'remainder'},
				{data: 'order_status'},
				{data: 'created_at'},
				{data: 'Ενέργειες', responsivePriority: -1},
            ],
            "order": [[ 0, "desc" ]],
			columnDefs: [
                {
                    targets: 1,
					render: function(data, type, full, meta) {
						const customerId = full.customer_id;
						return `<a href="` + BASE_URL + `/customers/` + customerId + `" target="_blank">` + full.customer_name + `</a>`;
					}
                },
                {
                    targets: 5,
					render: function(data, type, full, meta) {
                        const status = full.order_status;
                        let order_status = '';
                        if(status == 1) {
                            order_status = 'Δημιουργία';
                        } else if(status == 2) {
                            order_status = 'Ανοιχτή για προϊόντα / έκπτωση';
                        } else if(status == 3) {
                            order_status = 'Ράφι Αναμονής';
                        } else if(status == 4) {
                            order_status = 'Αποστολή';
                        } else if(status == 5) {
                            order_status = 'Ολοκληρώθηκε';
                        } else if(status == 6) {
                            order_status = 'Επιστράφηκε';
                        } else if(status == 7) {
                            order_status = 'Ακυρώθηκε';
                        }
						return `<span>${order_status}</span>`;
					}
                },
                {
                    targets: 6,
					render: function(data, type, full, meta)
					{
						if(full.created_at && full.created_at.includes(' '))
						{
							const created_at = full.created_at;
							let date_split = created_at.split(' ');
							let date_part = date_split[0].split('-').reverse().join('/');
							let time_part = date_split[1];
							let order_date = date_part + ' ' + time_part;
							return `<span>${order_date}</span>`;
						}
						return '';
					}
                },
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const orderId = full.order_id;
						return `
							<a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="` + BASE_URL + `/orders/` + orderId + `/edit" title="Επεξεργασία Παραγγελίας">
								<i class="la la-edit"></i>
                            </a>
                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md" onclick="deleteOrder(`+orderId+`)" title="Διαγραφή Παραγγελίας">
                                <i class="la la-trash"></i>
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
