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
										{!! !empty($data['manufacturer']) ? 'Επεξεργασία Κατασκευαστή' : 'Προσθήκη Κατασκευαστή' !!}
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
						<form data-redirect="/manufacturers" enctype='multipart/form-data' class="kt-form kt-form--fit kt-form--label-right" id="kt_form" action="{{ $data['action'] }}" method="{{ $data['method'] }}" novalidate="novalidate">
							@csrf
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="name">Όνομα:</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" value="{!! !empty($data['manufacturer']['name']) ? $data['manufacturer']['name'] : '' !!}" name="name" id="name" placeholder="Όνομα Κατασκευαστή" aria-describedby="address1-error" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="manuImage">Εικόνα</label>
									<div class="col-lg-8">
										<div class="kt-input-icon">
											
											@if(!empty($data['manufacturer']['image']))
												<div class="image-holder">
													<img src="{{url('/images/' . $data["manufacturer"]["image"])}}" alt="{!! !empty($data['manufacturer']['name']) ? $data['manufacturer']['name'] : '' !!}" />
												</div>
												<input type="file" class="custom-file-input" name="image" id="manuImage">
											@else
												<input type="file" class="custom-file-input" name="image" id="manuImage">
												<label style="text-align:left;" class="custom-file-label" for="manuImage">Ανέβασε μία εικόνα</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="kt-portlet__foot kt-portlet__foot--fit-x">
								<div class="kt-form__actions">
									<div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
										{!! !empty($data['manufacturer']) ? 'Αποθήκευση' : 'Δημιουργία' !!}
									</div>
									<a href="{{ route('manufacturers.index') }}" class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">Ακύρωση</a>
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
@endsection