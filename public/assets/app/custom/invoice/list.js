"use strict";
var InvoiceKTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
		var table = $('#invoice_kt_table_1');

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
				"url": BASE_URL + "/invoices",
				"dataSrc": "data.data",
				"data": function ( d ) {
					d.customer_id = $('#invoice_customer_id').val();
					d.invoice_status = $('#invoice_status').val();
					d.from = $('#invoice_search_from').val();
					d.to = $('#invoice_search_to').val();
				}
			},
			columns: [
				{data: 'invoice_id'},
				{data: 'order_id'},
				{data: 'invoice_status'},
				{data: 'invoice_date'},
				{data: 'Ενέργειες', responsivePriority: -1},
            ],
			columnDefs: [
                {
                    targets: 1,
					render: function(data, type, full, meta) {
						const orderId = full.order_id;
						return `<a href="` + BASE_URL + `/orders/` + orderId + `/edit" target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-md pointer"><i class="la flaticon-interface-11"></i> ` + full.order_id + `</a>`;
					}
                },
                {
                    targets: 2,
					render: function(data, type, full, meta) {
						const invoice_status = full.invoice_status;
						if(invoice_status == 0) return '<span>Ενεργό</span>';
						return '<span>Ακυρωμένο</span>';
					},
                },
                {
                    targets: 3,
					render: function(data, type, full, meta) {
                        const invoice_date = full.invoice_date;
                        let date_split = invoice_date.split(' ');                        
                        let date_part = date_split[0].split('-').reverse().join('/');
                        let time_part = date_split[1];
                        let order_date = date_part + ' ' + time_part;
                        return `<span>${order_date}</span>`;
					}
                },
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const invoiceId = full.invoice_id;
                        const orderId = full.order_id;
							/*task 3*/
                            return `
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="` + BASE_URL + `/invoices/` + invoiceId + `" target="_blank" title="Προβολή Τιμολογίου">
                                    <i class="la flaticon-interface-11"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md cancel-invoice pointer" data-id="${invoiceId}" data-order-id="${orderId}" title="Ακύρωση Τιμολογίου">
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
	InvoiceKTDatatablesDataSourceAjaxServer.init();
});
