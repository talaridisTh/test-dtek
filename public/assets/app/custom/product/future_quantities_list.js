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
				"url": BASE_URL + "/products/futureQuantities",
                "dataSrc": "data.data",
				"data": function ( d ) {
					d.date_filter = $('#date_filter').val();
				}
			},
			columns: [
				{
					"render": function ( data, type, row, meta )
                    {
						let radial = '';
                        let p = row.product_details;
                        let fittingPosotion = 'E'
                        if(p['fitting_position'] == 2)
                            fittingPosotion = 'Π';
                        if(p['fitting_position'] == 3)
                            fittingPosotion = 'Ε/Π';
                        let tybe_type='TL';
                        if(p['tube_type'] == 1)
                            tybe_type = 'TT';
                        if(p['tube_type'] == 2)
                            tybe_type = 'TL/TT';

                        return row['manufacturer_name']+' '+p['name']+' '+p['width']+'/'+p['height_percentage']+p['radial_structure']+p['diameter']+" "+p['weight_flag']+p['speed_flag']+' '+fittingPosotion+' '+tybe_type+' '+p['model'];
                    }
				},
				{data: 'stock'},
				{
					"render": function ( data, type, row, meta ) {
                        let arrival_date = row['arrival_date'];
                        if(arrival_date != undefined && arrival_date != null && arrival_date.length == 10) {
                            arrival_date = arrival_date.split('-').reverse().join('/');
                        }
						return arrival_date;
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
						return `
							<span class="btn btn-sm btn-default apply-future-qty pointer" data-stock="${full.stock}" data-id="${full.product_future_quantity_id}" title="Εφαρμογή Μελλοντικής Τιμής">
								Ήρθε
							</span>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md delete-future-qty pointer" data-id="${full.product_future_quantity_id}" title="Διαγραφή Μελλοντικής Ποσότητας">
								<i class="la la-trash"></i>
							</span>
							`;
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

$('body').on('click', '.delete-future-qty', function() {
	let id = $(this).data('id');
	Swal.fire({
		title: 'Είστε σίγουρος?',
		text: "Διαγραφή μελλοντικής ποσότητας?",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Ναι, διαγραφή!',
		cancelButtonText: 'Ακύρωση'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: window.BASE_URL + '/products/deleteFutureQuantity',
				method: 'POST',
				data: {
					product_future_quantity_id: id
				},
				dataType: 'json'
			})
			.done(function(res) {
				if(res.status == 'success') {
					Swal.fire(
						'Διαγραφή',
						'Η μελλοντική ποσότητα διαγράφθηκε',
						'success'
					);
				}
			})
			.fail(function(err) {
				console.log(err);
			})
			.always(function(r) {
				$('#kt_table_1').DataTable().ajax.reload();
			})
		}
	})
});

$('body').on('click', '.apply-future-qty', function() {
    let id = $(this).data('id');
	let stock = $(this).data('stock');
	let warehouses = window.warehouses;
	let warehouse_options = '<option value="-1">Επιλέξτε Αποθήκη</option>';
	warehouses.map(w => warehouse_options += "<option value='" + w.warehouse_id + "'>" + w.name + "</option>");

	let weekOfYear = moment().week();
	weekOfYear += '';
	if(weekOfYear.length == 1) weekOfYear = '0' + weekOfYear;

	let year = moment().year();
	year += '';
	year = year.substr(2);

	let batch = year + weekOfYear;

	Swal.fire({
		title: 'Εφαρμογή Μελλοντικής Ποσότητας',
		html: `
		<div class="row">
				<div class="col-md-12">
					<label for="warehouse_id">Αποθήκη</label>
					<select class="form-control custom-select2" id="warehouse_id" name="warehouse_id">${warehouse_options}</select>
				</div>
				<div class="col-md-12">
					<label for="shelf_id">Ράφι</label>
					<select class="form-control custom-select2" id="shelf_id" name="shelf_id"></select>
				</div>
				<div class="col-md-12">
					<label for="batch">Batch</label>
					<input type="number" class="form-control" id="batch" name="batch" value="${batch}">
				</div>
				<div class="col-md-12">
					<p>Stock: ${stock}</p>
				</div>
			</div>
		`,
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Εφαρμογή',
		onOpen: function() {
			$('.custom-select2').select2({
				dropdownCssClass: 'increasedzindexclass'
			});

			$('#warehouse_id').change(function() {
				let warehouse_id = $(this).val();
				if(warehouse_id == -1) {
					$('#shelf_id').html('');
					return;
				}

				$.ajax({
					url: window.BASE_URL + '/warehouses/findShelves',
					method: 'GET',
					data: {
						warehouse_id
					},
					dataType: 'json'
				})
				.done(function(res) {
					if(res.shelves) {
						let shelves_options = '';
						res.shelves.map(s => {
							shelves_options += "<option value='" + s.shelf_id + "'>" + s.name + "</option>";
						})
						$('#shelf_id').html(shelves_options);
					} else {
						$('#shelf_id').html('');
					}
				})
				.fail(function(err) {
					$('#shelf_id').html('');
				})
			})
		},
		preConfirm: function() {
			let warehouse_id = Swal.getPopup().querySelector('#warehouse_id').value;
			warehouse_id = parseInt(warehouse_id);
			if(isNaN(warehouse_id) || warehouse_id < 1) {
				Swal.showValidationMessage(`Επιλέξτε μία αποθήκη`);
				return;
			}

			let shelf_id = Swal.getPopup().querySelector('#shelf_id').value;
			shelf_id = parseInt(shelf_id);
			if(isNaN(shelf_id) || shelf_id < 1) {
				Swal.showValidationMessage(`Επιλέξτε ένα ράφι`);
			}

			let batch = Swal.getPopup().querySelector('#batch').value;
			batch = parseInt(batch);
			if(isNaN(batch) || batch < 1) {
				Swal.showValidationMessage(`Εισάγεται batch`);
			}

			return {
				warehouse_id,
				shelf_id,
				batch,
			};
		}
	}).then((result) => {
		if(result.value) {
			let warehouse_id = result.value.warehouse_id;
			let shelf_id = result.value.shelf_id;
			let batch = result.value.batch;
			let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			let data = {
				_token: CSRF_TOKEN,
				warehouse_id,
				shelf_id,
				batch,
				product_future_quantity_id: id
			};

			let formdata = new FormData();
			Object.keys(data).map(o => {
				formdata.append(o, data[o]);
			})

			$.ajax({
				url: BASE_URL + '/products/applyFutureQuantity',
				method: 'POST',
				data: formdata,
				contentType: false,
				processData: false
			})
			.done(function(res) {
				if(res.success || res.status == 'success') {
					$('#kt_table_1').DataTable().draw();
				}
			})
			.fail(function(err) {
				console.log(err);
			})
		}
	});
});
