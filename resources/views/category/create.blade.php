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
										{!! !empty($data['category']) ? 'Επεξεργασία Κατηγορίας' : 'Προσθήκη Κατηγορίας' !!}
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
						<form data-redirect="/categories" enctype='multipart/form-data' class="kt-form kt-form--fit kt-form--label-right" id="kt_form" action="{{ $data['action'] }}" method="{{ $data['method'] }}" novalidate="novalidate">
							@csrf
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="name">Όνομα:</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" value="{!! !empty($data['category']['name']) ? $data['category']['name'] : '' !!}" name="name" id="name" placeholder="Όνομα Κατηγορίας" aria-describedby="address1-error" required>
									</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label" for="parent">Μητρική κατηγορία:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="parent" id="parent" aria-describedby="parent-error" required>
											<option value="-1">Καμία</option>
											@foreach ($data['categories'] as $category)
												@if ( isset($data['category']['parent_id']) && $category['category_id'] == $data['category']['parent_id'])
													<option value="{{ $category['category_id'] }}" selected>{{ $category['name'] }}</option>
												@else
													<option value="{{ $category['category_id'] }}">{{ $category['name'] }}</option>
												@endif
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="catImage">Εικόνα</label>
									<div class="col-lg-8">
										<div class="kt-input-icon">
											
											@if(!empty($data['category']['image']))
												<div class="image-holder">
													<img src="{{url('/images/' . $data["category"]["image"])}}" alt="{!! !empty($data['category']['name']) ? $data['category']['name'] : '' !!}" />
												</div>
												<input type="file" class="custom-file-input" name="image" id="catImage">
											@else
												<input type="file" class="custom-file-input" name="image" id="catImage">
												<label style="text-align:left;" class="custom-file-label" for="catImage">Ανέβασε μία εικόνα</label>
											@endif
										</div>
									</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label" for="name">Περιγραφή</label>
                                    <div class="col-lg-8">
                                            <textarea class="form-control" name="comments" id="comments" cols="30" rows="10" aria-describedby="comments-error">{{ (!empty($data['product'])) ? $data['product']['comments'] : '' }}</textarea>
                                    </div>
                                </div>
							</div>
							<div class="kt-portlet__foot kt-portlet__foot--fit-x">
								<div class="kt-form__actions">
									<div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
										{!! !empty($data['category']) ? 'Αποθήκευση' : 'Δημιουργία' !!}
									</div>
									<a href="#" class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">Ακύρωση</a>
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