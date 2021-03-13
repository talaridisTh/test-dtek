@extends('layouts.app')
@section('custom_css')
<link href="{{ asset('assets/app/custom/wizard/wizard-v3.demo11.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
	<div class="kt-portlet">
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
				<div class="kt-grid__item">
					<!--begin: Form Wizard Nav -->
					<div class="kt-wizard-v3__nav">
						<div class="kt-wizard-v3__nav-items">
							<a class="kt-wizard-v3__nav-item" href="#" data-ktwizard-type="step" data-ktwizard-state="current">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										@if ($data['isEdit'])
											Επεξεργασία Ραφιού
										@else
											Προσθήκη Ραφιού
										@endif
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</a>
						</div>
					</div>
					<!--end: Form Wizard Nav -->
				</div>
				<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">
					<!--begin::Form-->
						<form data-redirect="/shelves" enctype='multipart/form-data' class="kt-form kt-form--fit kt-form--label-right" id="kt_form" action="{{ $data['action'] }}" method="{{ $data['method'] }}" novalidate="novalidate">
							@csrf
							<div class="kt-portlet__body">
								@if ($data['isEdit'])
								<div class="form-group row">
									<div class="col-lg-6 offset-lg-3 text-center">
										<div class="border barcode-section">
											<h3 class="text-center">Barcode</h3>
											<div id="barcode_wrapper">
												<img id="barcode" class="d-block m-0 mx-auto printable">
											</div>
											<div class="mt-2">
												<button class="btn btn-primary kt-font-bold kt-font-transform-u d-block rounded-0 w-100" id="print_barcode"><i class="la la-print"></i> ΕΚΤΥΠΩΣΗ</button>
											</div>
										</div>
									</div>
								</div>
								@endif
								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="name">Όνομα:</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" name="name" value="{{ (!empty($data['shelf']['name'])) ? $data['shelf']['name'] : '' }}" id="name" placeholder="Όνομα ραφιού" aria-describedby="name-error" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="warehouse_id">Αποθήκη</label>
									<div class="col-lg-8">
                                    <select class="custom-select form-control" name="warehouse_id" id="warehouse_id" required>
										<option disabled selected="">Διάλεξε την αποθήκη που ανήκει</option>
										@foreach ($data['warehouses'] as $warehouse)
											<option value="{{ $warehouse['warehouse_id'] }}"
												@if (!empty($data['shelf']['warehouse']) && $data['shelf']['warehouse']['warehouse_id'] == $warehouse['warehouse_id'])
													selected
												@endif
											>{{ $warehouse['name'] }}</option>
										@endforeach
                                    </select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-2 col-form-label">Τύπος Ραφιού</label>
									<div class="col-8">
										<div class="kt-radio-inline">
										<label class="kt-radio kt-radio--solid">
											<input type="radio" name="is_product_shelf" value="1"
												@if ( (!empty($data['shelf']) && $data['shelf']['is_product_shelf'] == 1) || !$data['isEdit'] )
													checked 
												@endif
											> Ράφι Προϊόντος
											<span></span>
										</label>
										<label class="kt-radio kt-radio--solid">
											<input type="radio" name="is_product_shelf" value="0"
												@if (!empty($data['shelf']) && $data['shelf']['is_product_shelf'] == 0)
													checked 
												@endif
											> Ράφι Αναμονής
											<span></span>
										</label>
										</div>
									</div>
								</div>
							</div>
							<div class="kt-portlet__foot kt-portlet__foot--fit-x">
								<div class="kt-form__actions">
									<div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
										{!! $data['isEdit'] ? 'Αποθήκευση' : 'Δημιουργία' !!}
									</div>
									<a href="{{ route('shelves.index') }}" class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">Ακύρωση</a>
								</div>
							</div>
						</form>
						<!--end::Form-->
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('custom_script')
<script src="{{ asset('assets/app/custom/wizard/wizard-v3.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/JsBarcode.all.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
		$('#warehouse_id').select2();
        @if (!empty($data['shelf']))
            JsBarcode("#barcode", "shelf::{{ $data['shelf']['shelf_id'] }}", {
                displayValue: false,
            });
            function printElem(divId) {
                var content = document.getElementById(divId).innerHTML;
                var mywindow = window.open('', 'Print', 'height=600,width=800');

                mywindow.document.write('<html><head><title>Print</title>');
                mywindow.document.write('</head><body >');
                mywindow.document.write(content);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus()
                mywindow.print();
                mywindow.close();
                return true;
            }
            $('#print_barcode').click(function() {
                printElem('barcode_wrapper');
            });
        @endif
    });
</script>
@endsection