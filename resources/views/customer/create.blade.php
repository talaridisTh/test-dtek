@extends('layouts.app')
@section('custom_css')
<link href="{{ asset('assets/app/custom/wizard/wizard-v3.demo11.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
	<div class="kt-portlet">
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3">
				<div class="kt-grid__item">
					<!--begin: Form Wizard Nav -->
					<div class="kt-wizard-v3__nav">
						<div class="kt-wizard-v3__nav-items">
							<a class="kt-wizard-v3__nav-item" href="#" data-ktwizard-type="step" data-ktwizard-state="current" id="customer-link">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										{!! !empty($data['customer']) ? 'Επεξεργασία Πελάτη' : 'Προσθήκη Πελάτη' !!}
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</a>
							<a class="kt-wizard-v3__nav-item" href="#" data-ktwizard-state="" id="customer-address-link">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										Διευθύνσεις
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</a>
						</div>
					</div>
					<!--end: Form Wizard Nav -->
				</div>
				<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper" id="customer-section">
					<!--begin::Form-->
						<form data-redirect="/customers{!! !empty($data['customer']['customer_id']) ? '/'.$data['customer']['customer_id'].'/edit' : '' !!}" enctype='multipart/form-data' class="kt-form kt-form--fit kt-form--label-right" id="kt_form" action="{{ $data['action'] }}" method="{{ $data['method'] }}" novalidate="novalidate">
							@csrf
							<input type="hidden" id="customer_id" name="customer_id" value="{!! !empty($data['customer']['customer_id']) ? $data['customer']['customer_id'] : '' !!}" />
							<div class="kt-portlet__body">
								<div class="row">
									<div class="col-xl-6">
										<div class="form-group">
											<label for="customer_name">Ονοματεπώνυμο</label>
											<input type="text" class="form-control" name="customer_name" id="customer_name" value="{!! !empty($data['customer']['customer_name']) ? $data['customer']['customer_name'] : '' !!}" aria-describedby="customer_name-error" required>
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="customer_group_id">Ομάδα Πελάτη</label>
											<select name="customer_group_id" class="form-control" id="customer_group_id" required>
												@foreach ($data['customer_groups'] as $group)
													@if ( isset($data['customer']['customer_group_id']) && $group['customer_group_id'] == $data['customer']['customer_group_id'])
														<option value="{{ $group['customer_group_id'] }}" selected>{{ $group['name'] }}</option>
													@else
														<option value="{{ $group['customer_group_id'] }}">{{ $group['name'] }}</option>
													@endif

												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-xl-6">
										<div class="form-group">
											<label for="phone">Τηλέφωνο</label>
											<input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{!! !empty($data['customer']['phone']) ? $data['customer']['phone'] : '' !!}" aria-describedby="phone-error" required>
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="mobile">Κινητό</label>
											<input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{!! !empty($data['customer']['mobile']) ? $data['customer']['mobile'] : '' !!}" aria-describedby="mobile-error" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-6">
										<div class="form-group">
											<label for="fax">Fax</label>
											<input type="text" class="form-control" name="fax" id="fax" placeholder="" value="{!! !empty($data['customer']['fax']) ? $data['customer']['fax'] : '' !!}" aria-describedby="fax-error" >
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
										<label id="tax_label_id" for="tax_id">Α.Φ.Μ.</label>
											<input onkeydown="checkVat()" type="text" class="form-control" name="tax_id" id="tax_id" placeholder="" value="{!! !empty($data['customer']['tax_id']) ? $data['customer']['tax_id'] : '' !!}" aria-describedby="tax_id-error">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-6">
										<div class="form-group">
											<label for="tax_office">Δ.Ο.Υ.</label>
											<input type="text" class="form-control" name="tax_office" id="tax_office" placeholder="" value="{!! !empty($data['customer']['tax_office']) ? $data['customer']['tax_office'] : '' !!}" aria-describedby="tax_office-error">
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="company_name">Όνομα Εταιρίας</label>
											<input type="text" class="form-control" name="company_name" id="company_name" placeholder="" value="{!! !empty($data['customer']['company_name']) ? $data['customer']['company_name'] : '' !!}" aria-describedby="company_name-error">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-6">
										<div class="form-group">
											<label for="company_kind">Είδος Εταιρίας</label>
											<input type="text" class="form-control" name="company_kind" id="company_kind" placeholder="" value="{!! !empty($data['customer']['company_kind']) ? $data['customer']['company_kind'] : '' !!}" aria-describedby="company_kind-error">
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="email">Email</label>
											<input type="text" class="form-control" name="email" id="email" placeholder="" value="{!! !empty($data['customer']['email']) ? $data['customer']['email'] : '' !!}" aria-describedby="email-error">
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="password">Password Για το Eshop</label>
											<input type="password" class="form-control" name="password" id="password" placeholder="" value="" aria-describedby="email-error" >
										</div>
									</div>
									<div class="col-xl-6">
										<div class="form-group">
											<label for="password2">Επανάληψη Password Για το Eshop</label>
											<input type="password" class="form-control" name="password2" id="password2" placeholder="" value="" aria-describedby="email-error" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-12">
										<div class="form-group">
											<label for="comments">Σχόλια</label>
											<textarea class="form-control" name="comments" id="comments" placeholder="" aria-describedby="comments-error">{!! !empty($data['customer']['comments']) ? $data['customer']['comments'] : '' !!}</textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="kt-portlet__foot kt-portlet__foot--fit-x">
								<div class="kt-form__actions">
									<div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
										{!! !empty($data['customer']) ? 'Αποθήκευση' : 'Δημιουργία' !!}
									</div>
									<a href="#" class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">Ακύρωση</a>
								</div>
							</div>
						</form>

						<!--end::Form-->
				</div>
				<div class="kt-grid__item" id="customer-addresses-section">
					<div class="kt-wizard-v3__content addresses-list">
						<div class="kt-portlet kt-portlet--mobile">
							<div class="kt-portlet__head kt-portlet__head--lg">
								<div class="kt-portlet__head-label">
									<span class="kt-portlet__head-icon">
										<i class="kt-font-brand flaticon2-line-chart"></i>
									</span>
									<h3 class="kt-portlet__head-title">
										Λίστα διευθύνσεων
									</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
									<div class="kt-portlet__head-wrapper">
										<div class="kt-portlet__head-actions">
											<a href="#" class="btn btn-brand btn-elevate btn-icon-sm" id="add-address-link">
												<i class="la la-plus"></i>
												Νέα Διεύθυνση
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="table-responsive">
									<!--begin: Datatable -->
									<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
										<thead>
											<tr>
												<th>No</th>
												<th>Ονομα</th>
												<th>Επώνυμο</th>
												<th>Εταιρία</th>
												<th>Διεύθυνση 1</th>
												<th>Διεύθυνση 2</th>
												<th>Πόλη</th>
												<th>Τ.Κ.</th>
												<th>Χώρα</th>
												<th>Ενέργειες</th>
											</tr>
										</thead>
									</table>
									<!--end: Datatable -->
								</div>
							</div>
						</div>
					</div>
					<div class="kt-wizard-v3__content addresses-add">
						<div class="kt-portlet">
							<div class="kt-portlet__body kt-portlet__body--fit">
								<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3_addr">
									<div class="kt-grid__item">
										<!--begin: Form Wizard Nav -->
										<div class="kt-wizard-v3__nav">
											<div class="kt-wizard-v3__nav-items">
												<a class="kt-wizard-v3__nav-item" href="#">
													<div class="kt-wizard-v3__nav-body">
														<div class="kt-wizard-v3__nav-label">
															Προσθήκη Διεύθυνσης
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
										<form data-redirect="/customers" enctype='multipart/form-data' class="kt-form kt-form--fit kt-form--label-right" id="kt_form_addr" action="{{ route('addresses.store') }}" method="POST" novalidate="novalidate">
											@csrf
											<input type="hidden" id="address_id" name="address_id" value="" />
											<input type="hidden" id="customer_id" name="customer_id" value="{!! !empty($data['customer']['customer_id']) ? $data['customer']['customer_id'] : '' !!}" />
											<div class="kt-portlet__body">
												<div class="row">
													<div class="col-xl-6">
														<div class="form-group">
															<label for="firstname">Όνομα</label>
															<input type="text" class="form-control" name="firstname" id="firstname" placeholder="Όνομα" value="" aria-describedby="firstname-error" >
														</div>
													</div>
													<div class="col-xl-6">
														<div class="form-group">
															<label for="lastname">Επώνυμο</label>
															<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Επώνυμο" value="" aria-describedby="lastname-error" >
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-12">
														<div class="form-group">
															<label for="company">Όνομα Εταιρίας</label>
															<input type="text" class="form-control" name="company" id="company" placeholder="Όνομα Εταιρίας" value="" aria-describedby="company-error" >
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-6">
														<div class="form-group">
															<label for="address_1">Διεύθυνση 1</label>
															<input type="text" class="form-control" name="address_1" id="address_1" placeholder="Διεύθυνση 1" value="" aria-describedby="address_1-error" >
														</div>
													</div>
													<div class="col-xl-6">
														<div class="form-group">
															<label for="address_2">Διεύθυνση 2</label>
															<input type="text" class="form-control" name="address_2" id="address_2" placeholder="Διεύθυνση 2" value="" aria-describedby="address_2-error">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-4">
														<div class="form-group">
															<label for="city">Πόλη</label>
															<input type="text" class="form-control" name="city" id="city" placeholder="Πόλη" value="">
														</div>
													</div>
													<div class="col-xl-4">
														<div class="form-group">
															<label for="postcode">Τ.Κ.</label>
															<input type="text" class="form-control" name="postcode" id="postcode" placeholder="Τ.Κ." value="">
														</div>
													</div>
													<div class="col-xl-4">
														<div class="form-group">
															<label for="country_id">Χώρα</label>
															<select name="country_id" id="country_id" class="form-control" required>
																@foreach($data['countries'] as $country)
																<option value="{{ $country['country_id'] }}">{{ $country['name'] }}</option>
																@endforeach
															</select>
															{{-- <input type="text" class="form-control" name="country_id" id="country_id" placeholder="Χώρα" value="" aria-describedby="country_id-error" required> --}}
														</div>
													</div>
												</div>
											</div>
											<div class="kt-portlet__foot kt-portlet__foot--fit-x">
												<div class="kt-form__actions">
													<div id="submit-address-form-btn" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
														Δημιουργία
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
				</div>
			</div>
		</div>
	</div>
</div>



@endsection

@section('custom_script')
<script src="{{ asset('assets/app/custom/wizard/wizard-v3.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/app/custom/customer_address/create.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/app/custom/delete-modal.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/app/custom/customer_address/list.js') }}" type="text/javascript"></script>
<script>
$( "#password" ).blur(function()
{
	if($('#password').val().length < 5)
	{
		window.alert('Κωδικός μικρότερος απο 5 χαρακτήρες.')
	}
});
$( "#password2" ).blur(function()
{
  if($('#password').val() != $('#password2').val())
  {
	  window.alert('Οι κωδικοί δεν ταιριάζουν.')
  }
});
function checkVat()
{
  var vat = document.getElementsByName("tax_id")[0].value;

  if(vat.length !== 9)
    return;

  var xhttp = new XMLHttpRequest();
  document.getElementById("tax_label_id").innerHTML += "Παρακαλώ περιμένετε.";

  xhttp.onreadystatechange = function()
  {
    if (this.readyState == 4 && this.status == 200)
    {
      console.log(xhttp.responseText);
      var wrong = xhttp.responseText.includes("Invalid VAT number") || xhttp.responseText.includes("Incorrect VAT number");
      if(wrong)
      {
        document.getElementById("tax_label_id").innerHTML = "Λάθος ΑΦΜ.";
        document.getElementById("tax_label_id").style = "color:red;";
      }
      else if(xhttp.responseText.includes("service unavailable") ||xhttp.responseText.includes("Service unavailable"))
      {
        document.getElementById("tax_label_id").innerHTML = "Προσωρινά εκτός λειτουργίας.";
        document.getElementById("tax_label_id").style = "color:red;";
      }
      else
      {
        document.getElementById("tax_label_id").innerHTML = "Σωστό ΑΦΜ.";
        document.getElementById("tax_label_id").style = "color:green;";
        var name = xhttp.responseText.split("<td class=\"result_td1\">Name")[1].split("result_td2\">")[1].split("</td>")[0];
        //var address = xhttp.responseText.split("Adres")[1].split("result_td2\">")[1].split("</td>")[0];
        name = decodeHTMLEntities(name);
        //address = decodeHTMLEntities(address);
        //document.getElementsByName("invoice_address")[0].value=address;
        document.getElementsByName("company_name")[0].value=name;
      }
    }
  };
  xhttp.open("POST", "https://cors-anywhere.herokuapp.com/http://www.vatcheck.eu/vatcheck.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("prefix=EL&vat="+vat);
}
function decodeHTMLEntities(text) {
    var entities = [
        ['amp', '&'],
        ['apos', '\''],
        ['#x27', '\''],
        ['#x2F', '/'],
        ['#39', '\''],
        ['#47', '/'],
        ['lt', '<'],
        ['gt', '>'],
        ['nbsp', ' '],
        ['quot', '"']
    ];

    for (var i = 0, max = entities.length; i < max; ++i)
        text = text.replace(new RegExp('&'+entities[i][0]+';', 'g'), entities[i][1]);

    return text;
}
	$(document).ready(() => {
		$('#country_id').select2();
		$('#customer-address-link').on('click', () => {
			$('#customer-address-link').attr('data-ktwizard-state', 'current');
			$('#customer-link').attr('data-ktwizard-state', '');
			$('#customer-section').css('display', 'none');
			$('#customer-addresses-section').css('display', 'block');
		});

		$('#customer-link').on('click', () => {
			$('#customer-link').attr('data-ktwizard-state', 'current');
			$('#customer-address-link').attr('data-ktwizard-state', '');
			$('#customer-section').css('display', 'block');
			$('#customer-addresses-section').css('display', 'none');
		});

		$('#add-address-link').on('click', () => {
			$('html, body').animate({
				scrollTop: $(".addresses-add").offset().top - 80
			}, 1000);
			clearAddressForm();
		});

		$('body').on('click', '.edit-address-link', function(e) {
			e.preventDefault();
			$('html, body').animate({
				scrollTop: $(".addresses-add").offset().top - 80
			}, 1000);

			fillAddressForm($(this));
		});

        $('body').on('click', '.delete-address-link', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            deleteModal("της Διεύθυνσης", "Η Διεύθυνσης", id, "/addresses/");
        });
	});

	function clearAddressForm() {
		$('.addresses-add #kt_form_addr #address_id').val('');
		$('.addresses-add #kt_form_addr #firstname').val('');
		$('.addresses-add #kt_form_addr #lastname').val('');
		$('.addresses-add #kt_form_addr #company').val('');
		$('.addresses-add #kt_form_addr #address_1').val('');
		$('.addresses-add #kt_form_addr #address_2').val('');
		$('.addresses-add #kt_form_addr #city').val('');
		$('.addresses-add #kt_form_addr #postcode').val('');
		$('.addresses-add #kt_form_addr #country_id').val('');
		$('.addresses-add #kt_form_addr').attr('method', 'POST');
		$('.addresses-add #kt_form_addr').attr('action', BASE_URL+'/addresses');
		$('.addresses-add .kt-wizard-v3__nav-label').text('Προσθήκη Διεύθυνσης');
		$('.addresses-add .kt-form__actions .btn-success').text('Δημιουργία');
	}

	function fillAddressForm(el) {
		const id = $(el).parents('tr').find('td:nth-child(1)').text();
		const firstname = $(el).parents('tr').find('td:nth-child(2)').text();
		const lastname = $(el).parents('tr').find('td:nth-child(3)').text();
		const company = $(el).parents('tr').find('td:nth-child(4)').text();
		const address_1 = $(el).parents('tr').find('td:nth-child(5)').text();
		const address_2 = $(el).parents('tr').find('td:nth-child(6)').text();
		const city = $(el).parents('tr').find('td:nth-child(7)').text();
		const postcode = $(el).parents('tr').find('td:nth-child(8)').text();
		const country_text = $(el).parents('tr').find('td:nth-child(9)').text();

		$('.addresses-add #kt_form_addr #address_id').val(id);
		$('.addresses-add #kt_form_addr #firstname').val(firstname);
		$('.addresses-add #kt_form_addr #lastname').val(lastname);
		$('.addresses-add #kt_form_addr #company').val(company);
		$('.addresses-add #kt_form_addr #address_1').val(address_1);
		$('.addresses-add #kt_form_addr #address_2').val(address_2);
		$('.addresses-add #kt_form_addr #city').val(city);
		$('.addresses-add #kt_form_addr #postcode').val(postcode);
		$('.addresses-add #kt_form_addr #country_id').val($(".addresses-add #kt_form_addr #country_id option:contains('"+country_text+"')").val()).trigger('change');
		$('.addresses-add #kt_form_addr').attr('method', 'PUT');
		$('.addresses-add #kt_form_addr').attr('action', BASE_URL+`/addresses/${id}`);
		$('.addresses-add .kt-wizard-v3__nav-label').text('Επεξεργασία Διεύθυνσης');
		$('.addresses-add .kt-form__actions .btn-success').text('Αποθήκευση');
	}
</script>
@endsection
