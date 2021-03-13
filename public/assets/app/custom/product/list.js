"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
		var table = $('#kt_table_1');
		// begin first table
		table.DataTable({
			ordering: false,
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
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				"url": BASE_URL + "/products",
				"dataSrc": "data.data",
				"data": function ( d ) {
					d.manufacturer = $('#manufacturer').val();
					d.category = $('#category').val();
					d.dimensions = [
						$('#dimension_1').val(),
						$('#dimension_2').val(),
						$('#dimension_3').val()
                    ];
                    d.enforce_order_by_qty = $('#enforce_order_by_qty').val();
				}
			},
			columns: [
				{
					"render": function ( data, type, row, meta ) {
						let radial = '';
						if(row['radial_structure'] != undefined && row['radial_structure'] != '') radial = row['radial_structure'];
						if(row['height_percentage'] ==  null)
                            row['height_percentage'] = '';

                        if(row['diameter'] !=  null)
                            row['diameter'] = row['diameter'].toString().replace(".00","");

						return "<span class='list-product-dim'>" + row['width'] + '/' + row['height_percentage'] + ' ' + radial + ' ' + row['diameter'] + "</span><span class='list-product-name-mobile'>" + row['name'] + "</span>";
					  }
				},
                {data: 'manufacturer_name'},
                {data: 'name'},
                {
                    "render": function ( data, type, row, meta ) {
                        return row['weight_flag'] + row['speed_flag'];
                    }
				},
                {
                    "render": function ( data, type, row, meta ) {
                        let stock_info = row['stock_info'];
                        if(stock_info == null) return '';
                        const stock_per_wh = {};
                        let all_stock = stock_info.split(',');
                        all_stock.map(s => {
                            let current_stock_arr = s.split('::');
                            let stock = current_stock_arr[0];
                            stock = parseInt(stock);
                            if(isNaN(stock)) stock = 0;
                            const wh_name = current_stock_arr[1];
                            const wh_id = current_stock_arr[2];

                            stock_per_wh[wh_id] = stock_per_wh[wh_id] || [];
                            stock_per_wh[wh_id]['wh_name'] = wh_name;
                            if(stock_per_wh[wh_id]['stock'] != null && stock_per_wh[wh_id]['stock'] != undefined)
                                stock_per_wh[wh_id]['stock'] += stock;
                            else
                                stock_per_wh[wh_id]['stock'] = stock;
                        });

                        let stock_str = '';
                        Object.keys(stock_per_wh).map(sKey => {
                            let s = stock_per_wh[sKey];

                            stock_str += '<p>' + s['wh_name'] + ': ' + s['stock'] + '</p>';
                        })

                        //request to show per line
                        stock_str = '';
                        console.log(all_stock)
                        all_stock.map(s => {
                            let current_stock_arr = s.split('::');
                            let stock = current_stock_arr[0];
                            stock = parseInt(stock);
                            if(isNaN(stock)) stock = 0;
                            const wh_name = current_stock_arr[1];
                            const wh_id = current_stock_arr[2];
                            stock_str += '<p>' + wh_name + ': ' + stock + '</p>';

                        })

                        return stock_str;
                    }
				},
				{
                    "render": function ( data, type, row, meta ) {
                        let stock_info = row['stock_info'];
                        if(stock_info == null) return '';
						let batch_str = '';
						let all_stock = stock_info.split(',');
						let unique_batches = {};
                        all_stock.map(s => {
                            let current_stock_arr = s.split('::');
							if(current_stock_arr.length == 4) {
								unique_batches[current_stock_arr[3]] = current_stock_arr[3];
							}
                        });
						Object.keys(unique_batches).map(b => {
							batch_str += '<p>' + b + '</p>';
						})

                        //request to show per line
                        batch_str = '';
                        all_stock.map(s => {
                            let current_stock_arr = s.split('::');
                            if(current_stock_arr.length == 4) {
                                batch_str += '<p>' + current_stock_arr[3] + '</p>';
                            }
                        });
                        return batch_str;
                    }
                },
                {
                    "render": function ( data, type, row, meta ) {
                        let future_stock_info = row['future_stock_info'];
                        if(future_stock_info == null) return '';
                        let all_stock = future_stock_info.split(',');
                        let future_str = '';
                        all_stock.map(s => {
                            let current_stock_arr = s.split('::');
                            future_str += '<p>' + current_stock_arr[0]+" "+ current_stock_arr[1]+ '</p>';
                        });

                        return future_str;
                    }
                },
                {
                    "render": function ( data, type, row, meta ) {
                        let fitting = row['fitting_position'];
                        let fitting_text = '';
                        if(fitting == 1) fitting_text = 'Ε';
                        else if(fitting == 2) fitting_text = 'Π';
                        else fitting_text = 'Ε/Π';
                        return fitting_text;
                    }
                },
                {
                    "render": function ( data, type, row, meta ) {
                        var tt_type='TL';
                        if(row['tube_type'] == '1')
                            tt_type = "TT";
                        if(row['tube_type'] == '2')
                            tt_type = "TL/TT";

                        return tt_type;
                    }
                },
                {data: 'description'},
				{data: 'model'},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const productId = full.id;
						return `
							<a href="` + BASE_URL + `/products/` + productId + `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Προβολή Προϊόντος">
								<i class="la flaticon-interface-11"></i>
							</a>
							<a href="` + BASE_URL + `/products/` + productId + `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Επεξεργασία Προϊόντος">
								<i class="la la-edit"></i>
							</a>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md delete-product pointer" data-id="` + productId + `" title="Διαγραφή Προϊόντος">
								<i class="la la-trash"></i>
							</span>
							`;
					},
				}
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

$('body').on('click', '.delete-product', function() {
	let id = $(this).data('id');
	deleteModal("του προϊόντος", "Το προϊόν", id, "/products/");
});
