@extends('layouts.app')
@section('custom_css')
<link href="{{ asset('assets/app/custom/wizard/wizard-v3.demo11.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
.table th, .table td {
	padding-right: 5px;
	padding-left: 5px;
}

.td-order-product-price {
	width: 110px;
}

.td-order-product-tax {
	width: 110px;
}
</style>
@endsection
@section('content')
<script src="{{ asset('assets/app/custom/quagga.min.js') }}"></script>
<div class="kt-content kt-grid__item kt-grid__item--fluid" id="create-order-page">
	<div class="kt-portlet">
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="d-flex justify-content-between">
				<div>
					<button class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">Αποθήκευση</button>
				</div>
				@if ($data['isEdit'] && ($data['order']['order_status'] == 1 || $data['order']['order_status'] == 2) )
				<div>
					<button class="btn btn-info btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u d-flex align-items-center" data-ktwizard-type="action-submit" data-send-offset="1"><i class="la la-envelope"></i>Αποστολή Προσφοράς</button>
				</div>
				@endif
			</div>
			<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
			<div class="kt-grid__item">
				<!--begin: Form Wizard Nav -->
				<div class="kt-wizard-v3__nav">
					<div class="kt-wizard-v3__nav-items kt-wizard-v3__nav-items--clickable">
						<!--doc: Replace A tag with SPAN tag to disable the step link click -->
						<a class="kt-wizard-v3__nav-item" href="#" data-ktwizard-type="step" data-ktwizard-state="current">
							<div class="kt-wizard-v3__nav-body">
								<div class="kt-wizard-v3__nav-label">
									<span>1</span> Στοιχεία Πελάτη
								</div>
								<div class="kt-wizard-v3__nav-bar"></div>
							</div>
						</a>
						<a class="kt-wizard-v3__nav-item" href="#" @if($data['isEdit']) data-ktwizard-type="step" data-ktwizard-state="pending" @endif>
							<div class="kt-wizard-v3__nav-body">
								<div class="kt-wizard-v3__nav-label">
									<span>2</span> Στοιχεία Προϊόντων
								</div>
								<div class="kt-wizard-v3__nav-bar"></div>
							</div>
						</a>
						<a class="kt-wizard-v3__nav-item" href="#" @if($data['isEdit']) data-ktwizard-type="step" data-ktwizard-state="pending" @endif>
							<div class="kt-wizard-v3__nav-body">
								<div class="kt-wizard-v3__nav-label">
									<span>3</span> @if(isset($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] != 2) Αποδείξεις Παραγγελίας @else Τιμολόγια Παραγγελίας @endif
								</div>
								<div class="kt-wizard-v3__nav-bar"></div>
							</div>
						</a>
					</div>
				</div>
				<!--end: Form Wizard Nav -->
			</div>
			<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">
				<!--begin: Form Wizard Form-->
				<form action="{{ $data['action'] }}" data-redirect="/orders" class="kt-form" id="kt_form" method="{{ $data['method'] }}" novalidate="novalidate">
					@if($data['isEdit'])
					<input type="hidden" id="order_id" value="{{ $data['order']['order_id'] }}">
					@endif
					<!--begin: Form Wizard Step 1-->
					<div class="kt-wizard-v3__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
						<div class="kt-form__section kt-form__section--first">
							<div class="kt-wizard-v3__form mt-0">
								<div class="row mb-5 customer-selection">
									<label class="col-sm-2 d-flex align-items-center mb-0">Αναζήτηση Πελάτη</label>
									<div class="form-group col-sm-7 mb-0">
										@if (!$data['isEdit'])
										<select id="customer_id" name="customer_id" required aria-describedby="customer_id-error"></select>
										@else
										<select id="customer_id" name="customer_id" required aria-describedby="customer_id-error" disabled>
											<option value="{{ $data['customer']['customer_id'] }}">{{ $data['customer']['customer_name'] }}</option>
										</select>
										@endif
									</div>
								</div>

								<div class="row mb-5 address-selection disabled">
									<label class="col-sm-2 d-flex align-items-center mb-0">Διεύθυνση Πελάτη</label>
									<div class="form-group col-sm-7 mb-0">
										<select id="address_id" name="address_id" required aria-describedby="address_id-error">
												<option value="-1" selected disabled>Επιλογή Διεύθυνσης</option>
												<option value="0">Διεύθυνση 1</option>
												<option value="1">Διεύθυνση 2</option>
										</select>
									</div>
									<div class="col-sm-7 offset-sm-2 mt-2">
										<a href="#" class="new-address-btn btn btn-success disabled">
											<i class="flaticon-add-circular-button"></i>Προσθήκη Διεύθυνσης
										</a>
									</div>
								</div>
								<div class="separator my-5"></div>
								<div class="kt-widget12">
									<div class="kt-widget12__content">
										<div class="row">
											<div class="col-sm-12">
												<h3 class="mb-5">Στοιχεία Διεύθυνσης</h3>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-8">
												<div class="kt-widget12__item">
													<div class="kt-widget12__info">
														<span class="kt-widget12__desc">Ονοματεπώνυμο</span>
														<span class="kt-widget12__value" id="address_fullname">-</span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="kt-widget12__item">
													<div class="kt-widget12__info">
														<span class="kt-widget12__desc">Εταιρία</span>
														<span class="kt-widget12__value" id="address_company">-</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-8">
												<div class="kt-widget12__item">
													<div class="kt-widget12__info">
														<span class="kt-widget12__desc">Διεύθυνση 1</span>
														<span class="kt-widget12__value" id="address_address_1">-</span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="kt-widget12__item">
													<div class="kt-widget12__info">
														<span class="kt-widget12__desc">Διεύθυνση 2</span>
														<span class="kt-widget12__value" id="address_address_2">-</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-4">
												<div class="kt-widget12__item">
													<div class="kt-widget12__info">
														<span class="kt-widget12__desc">Πόλη</span>
														<span class="kt-widget12__value" id="address_city">-</span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="kt-widget12__item">
													<div class="kt-widget12__info">
														<span class="kt-widget12__desc">Τ.Κ.</span>
														<span class="kt-widget12__value" id="address_postcode">-</span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="kt-widget12__item">
													<div class="kt-widget12__info">
														<span class="kt-widget12__desc">Χώρα</span>
														<span class="kt-widget12__value" id="address_country_name">-</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="kt-widget12">
									<div class="kt-widget12__content">
										<div class="row">
											<div class="col-sm-12">
												<h3 class="mb-5">Στοιχεία Παραγγελίας</h3>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3">
												<div class="form-group">
													<label for="order_status">Κατάσταση</label>
													<select class="form-control" name="order_status" id="order_status"
														@if (!$data['isEdit'])
														disabled
														@endif
													>
														<option value="1" {{ (!empty($data['order']['order_status']) && $data['order']['order_status'] == 1) ? 'selected' : '' }}>Δημιουργία</option>
														<option value="2" {{ (!empty($data['order']['order_status']) && $data['order']['order_status'] == 2) ? 'selected' : '' }}>Ανοιχτή για προϊόντα / έκπτωση</option>
														<option value="3" {{ (!empty($data['order']['order_status']) && $data['order']['order_status'] == 3) ? 'selected' : '' }}>Ράφι Αναμονής</option>
														<option value="4" {{ (!empty($data['order']['order_status']) && $data['order']['order_status'] == 4) ? 'selected' : '' }}>Αποστολή</option>
														<option value="5" {{ (!empty($data['order']['order_status']) && $data['order']['order_status'] == 5) ? 'selected' : '' }}>Ολοκληρώθηκε</option>
														<option value="6" {{ (!empty($data['order']['order_status']) && $data['order']['order_status'] == 6) ? 'selected' : '' }}>Επιστράφηκε</option>
														<option value="7" {{ (!empty($data['order']['order_status']) && $data['order']['order_status'] == 7) ? 'selected' : '' }}>Ακυρώθηκε</option>
													</select>
													@if (!empty($data['order']['order_status']) && $data['order']['order_status'] == 3 && $data['order']->waittingShelf)
														Στο ράφι: {{ $data['order']->waittingShelf->name }}
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3">
												<div class="form-group">
													<label for="type_of_receipt">Τύπος Παραστατικού</label>
													<select class="form-control" name="type_of_receipt" id="type_of_receipt">
														<option value="1" {{ (!empty($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] == 1) ? 'selected' : '' }}>Απόδειξη</option>
														<option value="2" {{ (!empty($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] == 2) ? 'selected' : '' }}>Τιμολόγιο</option>
														<option value="3" {{ (!empty($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] == 3) ? 'selected' : '' }}>Άλλο</option>
													</select>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="payment_type">Τρόπος Πληρωμής</label>
													<select class="form-control" name="payment_type" id="payment_type">
														<option value="1" {{ (!empty($data['order']['payment_type']) && $data['order']['payment_type'] == 1) ? 'selected' : '' }}>Μετρητά</option>
														<option value="2" {{ (!empty($data['order']['payment_type']) && $data['order']['payment_type'] == 2) ? 'selected' : '' }}>Επιταγή</option>
														<option value="3" {{ (!empty($data['order']['payment_type']) && $data['order']['payment_type'] == 3) ? 'selected' : '' }}>Πιστωτική Κάρτα</option>
														<option value="5" {{ (!empty($data['order']['payment_type']) && $data['order']['payment_type'] == 5) ? 'selected' : '' }}>Με κατάθεση</option>
														<option value="4" {{ (!empty($data['order']['payment_type']) && $data['order']['payment_type'] == 4) ? 'selected' : '' }}>Αντικαταβολή</option>
														<option value="6" {{ (!empty($data['order']['payment_type']) && $data['order']['payment_type'] == 6) ? 'selected' : '' }}>Paypal</option>
													</select>
												</div>
												<div class="form-group">
													<label for="payment_type_number">Αριθμός Πληρωμής</label>
													<input type="text" name="payment_type_number" id="payment_type_number" class="form-control" placeholder="Αριθμός Πληρωμής" value="{{ (!empty($data['order']['payment_type_number'])) ? $data['order']['payment_type_number'] : '' }}">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="shipping_method">Τρόπος Αποστολής</label>
													<select name="shipping_method" id="shipping_method" class="form-control">
														<option value="1" {{ (!empty($data['order']['shipping_method']) && $data['order']['shipping_method'] == 1) ? 'selected' : '' }}>Μεταφορική</option>
														<option value="2" {{ (!empty($data['order']['shipping_method']) && $data['order']['shipping_method'] == 2) ? 'selected' : '' }}>Courier</option>
														<option value="3" {{ (!empty($data['order']['shipping_method']) && $data['order']['shipping_method'] == 3) ? 'selected' : '' }}>Παραλαβή από το κατάστημα</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="comments">Σχόλια</label>
													<textarea class="form-control" id="comments" name="comments" placeholder="Σχόλια Παραγγελίας" rows="5">{{ (!empty($data['order']['comments'])) ? $data['order']['comments'] : '' }}</textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end: Form Wizard Step 1-->

					<!--begin: Form Wizard Step 2-->
					<div class="kt-wizard-v3__content add-product-order-section" data-ktwizard-type="step-content">
						<div class="kt-heading kt-heading--md m-0 d-flex align-items-center justify-content-between">
							<h3>Προσθέστε προϊόντα στην παραγγελία</h3>
							<div style="display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex;">
								<a style="margin-right: 10px;" id="new-order-product" href="#" class="btn btn-primary">Νέο προϊόν</a>
								<a style="display:none;" id="new-product-scan" href="#" class="btn btn-primary">Scan</a>
							</div>
						</div>
						<div class="kt-form__section kt-form__section--first">
							<div style="overflow-x: scroll;" class="kt-wizard-v3__form">
								<table class="table table-hover" id="order_products_table">
									<thead>
										<tr>
										<th scope="col">#</th>
										<th scope="col">Προϊόν</th>
										<th scope="col">Ποσότητα | Παρτίδα | Ράφι</th>
										<th scope="col">Τιμή Μονάδος</th>
										<th scope="col">Φ.Π.Α. προϊόντος</th>
										<th scope="col">Σύνολο</th>
										<th scope="col">% Επιστροφής</th>
										<th scope="col">% Έκπτωσης</th>
										<th scope="col">Ενέργειες</th>
                                        <th scope="col">Φ.Π.Α.</th>
                                        <th scope="col">Οικ. Τέλος</th>
										</tr>
									</thead>
									<tbody>
										@foreach($data['order_products'] as $product)
										<tr data-row="{{ $loop->index }}">
											<th scope="row">{{ $loop->iteration }}
												<div>
													<input type="checkbox" class="return-check" value="1" name="return_products[{{ $product['product_id'] }}][checked]">
													<input
														style="display: none"
														type="number" min="0" step="1"
														max="{{ (!empty($product['quantity'])) ? $product['quantity'] : '' }}"
														class="form-control return-qty"
														name="return_products[{{ $product['product_id'] }}][quantity]"
														placeholder="Επιστραμμένη ποσότητα"
														>
													<input type="hidden" name="return_products[{{ $product['product_id'] }}][product_quantity_id]" value="{{ $product['product_quantity_id'] }}">
												</div>
											</th>
											<td>
												<select class="form-control order-product-select" name="order_product[{{ $loop->index }}][product_id]">
													@if($data['isEdit'])

													<option value="{{ $product['product_id'] }}" selected>{{ $product['manufacturer_name'] }} {{ $product['details']['name'] }}  {{ $product['details']['width'] }}/{{ $product['details']['height_percentage'] }}{{ $product['details']['radial_structure'] }}{{ $product['details']['diameter'] }} {{ $product['details']['weight_flag'] }}{{ $product['details']['speed_flag'] }} @if($product['details']['fitting_position'] == 1) E @elseif($product['details']['fitting_position'] == 2) Π @else Ε/Π @endif @if($product['details']['tube_type'] == 0) TL @elseif($product['details']['tube_type'] == 1) TT @else TL/TT @endif {{ $product['details']['model'] }}
													</option>
													@endif
												</select>
											</td>
											<td>
												<div class="form-group">
													<select class="form-control order-product-shelf-select" name="order_product[{{ $loop->index }}][product_quantity_id]" data-preselect="{{ $product['product_quantity_id'] }}" required></select>
													<input type="number" min="0" step="1" class="form-control order-product-quantity" name="order_product[{{ $loop->index }}][quantity]" value="{{ (!empty($product['quantity'])) ? $product['quantity'] : '' }}" placeholder="Ποσότητα" required/>
												</div>
											</td>
											<td class="td-order-product-price">
												<input type="number" min="0" step="0.01" class="form-control order-product-price" name="order_product[{{ $loop->index }}][price]" value="{{ (!empty($product['price'])) ? $product['price'] : '' }}" @if (!empty($product['price'])) data-load-from-db="1" @endif placeholder="Τιμή" required/>
											</td>
											<td class="td-order-product-tax">
												<input type="number" min="0" step="0.01" class="form-control order-product_tax" name="order_product[{{ $loop->index }}][product_tax]" value="{{ (!empty($product['product_tax'])) ? $product['product_tax'] : '' }}" placeholder="Φ.Π.Α. προϊόντος" readonly required/>
											</td>
											<td>
												<span class="order-product-total"></span>
											</td>
											<td>
												<span class="order-product-return-perc">{{ $product['discounts']['return'] }}</span>%
												<br><span class="order-product-return-total">{{ $product['discounts_totals']['return'] }}</span>&euro;
											</td>
											<td>
												<span class="order-product-discount-perc">{{ $product['discounts']['discount'] }}</span>%
												<br><span class="order-product-discount-total">{{ $product['discounts_totals']['discount'] }}</span>&euro;
											</td>
											<td>
												<a href="#" class="btn delete-order-product"><i class="la la-trash"></i></a>
											</td>
                                            <td>
                                                <select class="form-control order-product-taxclass-select" name="order_product[{{ $loop->index }}][tax_class_id]">
                                                    @foreach ($data['taxclasses'] as $taxclass)
                                                        <option
                                                            value="{{ $taxclass['tax_class_id'] }}"
                                                            data-taxclass-type="{{ $taxclass['type'] }}"
                                                            data-taxclass-amount="{{ $taxclass['amount'] }}"
                                                            {{ (!empty($product['tax_class_id']) && $product['tax_class_id'] == $taxclass['tax_class_id'] ) ? 'selected' : '' }}
                                                        >{{ $taxclass['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="checkbox" value="{{ $data['environmental_tax'] }}" name="order_product[{{ $loop->index }}][environmental_tax]" class="environmental_tax"
                                                       @if(!empty($product['environmental_tax']) && $product['environmental_tax'] > 0)
                                                       checked
                                                    @endif
                                                >
                                            </td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							@if (!empty($data['order_returned_products']))
							<h3>Επιστραμμένα προϊόντα</h3>
							<div style="overflow-x: scroll;" class="kt-wizard-v3__form">
								<table class="table table-hover" id="order_returned_products_table">
									<thead>
										<tr>
										<th scope="col">#</th>
										<th scope="col">Προϊόν</th>
										<th scope="col">Ράφι | Ποσότητα</th>
										<th scope="col">Τιμή</th>
										<th scope="col">Φ.Π.Α. προϊόντος</th>
										<th scope="col">Φ.Π.Α.</th>
										<th scope="col">Οικ. Τέλος</th>
										<th scope="col">Σύνολο</th>
										</tr>
									</thead>
									<tbody>
										@foreach($data['order_returned_products'] as $product)
										<tr data-row="{{ $loop->index }}">
											<th scope="row">{{ $loop->iteration }}</th>
											<td>
												{{ $product['details']['manufacturer_name'] }} {{ $product['details']['model'] }} {{ $product['details']['width'] }}/{{ $product['details']['height_percentage'] }}{{ $product['details']['radial_structure'] }}{{ $product['details']['diameter'] }}
											</td>
											<td>
												<div class="form-group">
													{{ $product['shelf_name'] }} | {{ $product['quantity'] }}
												</div>
											</td>
											<td>
												{{ $product['price'] }}
											</td>
											<td>
												{{ $product['product_tax'] }}
											</td>
											<td>
												@foreach ($data['taxclasses'] as $taxclass)
													@if ($product['tax_class_id'] == $taxclass['tax_class_id'])
													{{ $taxclass['name'] }}
													@endif
												</option>
												@endforeach
											</td>
											<td>
												{{ $product['environmental_tax'] }}
											</td>
											<td>
												{{ ($product['price'] + $product['product_tax'] + $product['environmental_tax']) * $product['quantity'] }}
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							@endif
						</div>
					</div>
					<!--end: Form Wizard Step 2-->

					<!--begin: Form Wizard Step 3-->
					<div class="kt-wizard-v3__content add-product-order-section" data-ktwizard-type="step-content">
						<div class="kt-heading kt-heading--md m-0 d-flex align-items-center justify-content-between">
							<h3>@if(isset($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] != 2) Αποδείξεις @else Τιμολόγια @endif</h3>
						</div>
						<div class="kt-form__section kt-form__section--first">
							<div class="kt-portlet" id="invoices">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
                                            @if(isset($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] != 2) Αποδείξεις @else Τιμολόγια @endif
										</h3>
									</div>
								</div>
								<div class="kt-portlet__body">
									<div class="kt-widget12">
										<div class="kt-widget12__content">
											<div class="row">
												<div class="col-md-12">
													<table class="table table-hover" id="order_invoices_table">
														<thead>
															<tr>
																<th scope="col">#</th>
																<th scope="col">Ημ/νία</th>
																<th scope="col">Κατάσταση</th>
																<th scope="col">Ενέργειες</th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<button type="button" class="btn btn-primary btn-md btn-wide kt-font-bold kt-font-transform-u btn-icon-md pointer" id="add_invoice" {{ !$data['can_issue_invoice'] ? 'disabled="disabled"' : ''}}><i class="la la-plus"></i>@if(isset($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] != 2) Έκδοση Απόδειξης  @else  Έκδοση Τιμολογίου @endif </button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="kt-portlet" id="creditinvoices">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											Πιστωτικά Τιμολόγια
										</h3>
									</div>
								</div>
								<div class="kt-portlet__body">
									<div class="kt-widget12">
										<div class="kt-widget12__content">
											<div class="row">
												<div class="col-md-12">
													<table class="table table-hover" id="order_credit_invoices_table">
														<thead>
															<tr>
																<th scope="col">#</th>
																<th scope="col">Ημ/νία</th>
																<th scope="col">Κατάσταση</th>
																<th scope="col">Ενέργειες</th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<button type="button" class="btn btn-primary btn-md btn-wide kt-font-bold kt-font-transform-u btn-icon-md pointer" id="add_credit_invoice" {{ !$data['can_issue_credit_invoice'] ? 'disabled="disabled"' : ''}}><i class="la la-plus"></i> Έκδοση Πιστωτικού Τιμολογίου</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end: Form Wizard Step 3-->

					<div class="kt-portlet col-md-6">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									Σύνολα
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body">
							<div class="kt-widget12">
								<div class="kt-widget12__content">
									<table class="table table-hover table-bordered">
										<tbody>
											<tr>
												<td><label for="shipping_cost">Κόστος Μεταφορικών</label></td>
												<td><input type="number" class="form-control" step="0.01" id="shipping_cost" name="shipping_cost" min="0" value="{{ (!empty($data['order']['shipping_cost'])) ? $data['order']['shipping_cost'] : '0' }}" required></td>
											</tr>
											<tr>
												<td><label for="payment_cost">Κόστος Πληρωμής</label></td>
												<td><input type="number" class="form-control" step="0.01" id="payment_cost" name="payment_cost" min="0" value="{{ (!empty($data['order']['payment_cost'])) ? $data['order']['payment_cost'] : '0' }}" required></td>
											</tr>
											@if($data['isEdit'])
											<tr>
												<td><label for="discount_type">Τύπος Έκπτωσης</label></td>
												<td>
													<div class="form-group">
														<select name="discount_type" id="discount_type" class="form-control">
															<option value="-1" {{ (empty($data['order']['discount_type']) || $data['order']['discount_type'] == -1) ? 'selected' : '' }}>Κανένα</option>
															<option value="1" {{ (!empty($data['order']['discount_type']) && $data['order']['discount_type'] == 1) ? 'selected' : '' }}>Ποσοστό</option>
															<option value="2" {{ (!empty($data['order']['discount_type']) && $data['order']['discount_type'] == 2) ? 'selected' : '' }}>Σταθερό Ποσό</option>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												<td><label for="discount_amount">Ποσό Έκπτωσης</label></td>
												<td>
													<div class="form-group">
														<input type="number" min='0' step="0.01" class="form-control" name="discount_amount" id="discount_amount"
															value="{{ (!empty($data['order']['discount_amount'])) ? $data['order']['discount_amount'] : '' }}"
														>
													</div>
												</td>
											</tr>
											<tr>
												<td><label for="discount_amount_from_group">Ποσό Έκπτωσης από Ομάδα Πελάτη</label></td>
												<td>
													<div class="form-group">
														<input type="number" min='0' step="0.01" class="form-control" name="discount_amount_from_group" id="discount_amount_from_group" value="0" disabled>
													</div>
												</td>
											</tr>
											<tr>
												<td><label for="order-paid">Πληρωμένο Ποσό</label></td>
												<td><span id="order-paid">{{ $data['payed'] }}€</span></td>
											</tr>
											@endif
											<tr>
												<td><label for="manage_cost">Κόστος Διαχείρισης</label></td>
												<td>
													<div class="form-group">
														<input type="number" min='0' step="0.01" class="form-control" name="manage_cost" id="manage_cost"
															value="{{ (!empty($data['order']['manage_cost'])) ? $data['order']['manage_cost'] : '0' }}"
														>
													</div>
												</td>
											</tr>
											<tr>
												<td><label>Υποσύνολο</label></td>
												<td><span id="order-subtotal"></span></td>
											</tr>
											<tr>
												<td><label>Φ.Π.Α.</label></td>
												<td><span id="order-tax_total"></span></td>
											</tr>
											<tr>
												<td><label>Οικολογικό Τέλος</label></td>
												<td><span id="order-environmental_tax_total"></span></td>
											</tr>
											<tr>
												<td><label>Σύνολο</label></td>
												<td><span id="order-total"></span></td>
											</tr>
											<tr>
												<td><label for="order_remainder">Υπόλοιπο</label></td>
												<td><span id="order-remainder"></span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					@if ($data['isEdit'])
					<div class="kt-portlet" id="payments">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									Πληρωμές
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body">
							<div class="kt-widget12">
								<div class="kt-widget12__content">
									<div class="row">
										<div class="col-md-12">
											<table class="table table-hover" id="order_payments_table">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col">Ποσό</th>
														<th scope="col">Ημ/νία Πληρωμής</th>
														<th scope="col">Περιγραφή</th>
														<th scope="col">Ενέργειες</th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button type="button" class="btn btn-primary btn-md btn-wide kt-font-bold kt-font-transform-u btn-icon-md pointer" id="add_payment"><i class="la la-plus"></i> Προσθήκη Πληρωμής</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					<!--begin: Form Actions -->
					<div class="kt-form__actions">
						<button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
							Προηγούμενο
						</button>
						<button style="display: block;" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
							Αποθήκευση
						</button>
						<button id="next-button" class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
							Επόμενο Βήμα
						</button>
					</div>
					<!--end: Form Actions -->
				</form>
				<!--end: Form Wizard Form-->
			</div>
		</div>
		</div>
	</div>
</div>

@if ($data['isEdit'])
<div id="camera-input" style="
	width: 100%;
    display: none !important;
    z-index: 99998;
    height: 100%;
    top: 0px;
    left: 0px;
	right: 0;
	bottom: 0;
	position: fixed;
	background:white;
	display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex;
    -webkit-align-items: center; -moz-align-items: center; -ms-align-items: center; align-items: center;
	-webkit-flex-direction: column-reverse; -moz-flex-direction: column-reverse; -ms-flex-direction: column-reverse; flex-direction: column-reverse;">
	<div id="camera-feedback"></div>
	<div id="bottom-scanner" style="position: absolute; bottom: 10px; left: 0; right: 0; z-index: 99999; display: flex; justify-content: center;">
		<button id="close-scan" style="width: 120px; " class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
			Κλείσιμο
		</button>
	</div>
</div>
@endif
@endsection

@section('custom_script')
<script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/buttons.html5.min.js') }}" type="text/javascript"></script>
<script>
	@if($data['isEdit'])
		window.order = <?php echo json_encode($data['order']); ?>;
	@else
		window.order = null;
	@endif
</script>
<script src="{{ asset('assets/app/custom/wizard/wizard-v3.js') }}?v=1" type="text/javascript"></script>
<script>
	@if (!$data['isEdit'])
		var order_created = false;
		var method = '<?php echo $data['method']; ?>';
		var action = '<?php echo $data['action']; ?>';
		var update_action = '<?php echo $data['update_action']; ?>';
		$( "#next-button" ).click(function(e) {
			var wizard = $("#kt_form"); // cache the form element selector
			if(!wizard.valid()){ // validate the form
				e.stopPropagation();
				wizard.validate().focusInvalid(); //focus the invalid fields
				return;
            }
			if(order_created)
				return;

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			e.preventDefault();
			let customer = $('#customer_id').val();
			let address = $('#address_id').val();
			let type_of_receipt = $('#type_of_receipt').val();
			let shipping_method = $('#shipping_method').val();

			let shipping_cost = parseFloat($('#shipping_cost').val());
			if(isNaN(shipping_cost) || shipping_cost < 0) shipping_cost = 0;

			let payment_cost = parseFloat($('#payment_cost').val());
			if(isNaN(payment_cost) || payment_cost < 0) payment_cost = 0;

			let payment_type = $('#payment_type').val();
			let payment_type_number = $('#payment_type_number').val();
			let comments = $('#comments').val();
			let order_status = 1;

			$.ajax({
				url: action,
				method,
				data: {
					_token: CSRF_TOKEN,
					customer_id: customer,
					address_id:address,
					type_of_receipt:type_of_receipt,
					shipping_method:shipping_method,
					shipping_cost:shipping_cost,
					payment_cost:payment_cost,
					payment_type:payment_type,
					payment_type_number:payment_type_number,
					comments:comments,
					order_status:order_status
				}
			}).done(function (response, textStatus, jqXHR)
			{
				if(response.success)
				{
					order_created = true;
					$('<input>').attr({
						type: 'hidden',
						id: 'order_id',
						name: 'order_id',
						value: response.order_id
					}).appendTo('#kt_form');
					update_action = update_action.replace("-1", response.order_id);
					$('#kt_form').attr('action', update_action);
					$('#order_status').prop('disabled', false);
					window.location.href = BASE_URL + '/orders/' + response.order_id + '/edit';
				}
				if(response.message)
					window.alert(response.message)

			}).fail(function (jqXHR, textStatus, errorThrown)
			{
				console.error('The following error occurred: '+textStatus, errorThrown);
			}).always(function ()
			{

			});
		});
	@endif
	$(document).ready(() => {
		$('.return-check').change(function() {
			let checked = $(this).is(':checked');
			let qty_el = $(this).parent().find('.return-qty');
			if(checked) {
				$(qty_el).show();
			} else {
				$(qty_el).val('');
				$(qty_el).hide();
			}
		});

		let order_paid = "{{ (!empty($data['payed'])) ? $data['payed'] : 0 }}";
		updateTotals();
		updateProductTotals();
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		// Init order product select2
		$('.order-product-select').select2({
			placeholder: 'Κωδικός/Όνομα/Διάσταση προιόντος',
			minimumInputLength: 2,
			ajax: {
				url: BASE_URL + '/products/search/',
				dataType: 'json',
				data: function (params) {
					return {
						_token: CSRF_TOKEN,
						q: $.trim(params.term),
                        stock_order: 1,
						instock: 1
					};
				},
				processResults: function (data) {

					let products = data['products'];
					let processed = [];
					products.map(p => {
                        var tt_type='TL';
                        if(p.tube_type == '1')
                            tt_type = "TT";
                        if(p.tube_type == '2')
                            tt_type = "TL/TT";

                        var fitting='Ε';
                        if(p.fitting_position == 2)
                            fitting='Π';
                        if(p.fitting_position == 3)
                            fitting='Ε/Π';

                        let height_percentage = '';
                        if(p.height_percentage != null && p.height_percentage != '')
                            height_percentage = p.height_percentage;

                        let stock_info = p['stock_info'];

                        const stock_per_wh = {};
                        if(stock_info != null)
                        {
                            let all_stock = stock_info.split(',');
                            all_stock.map(s => {
                                let current_stock_arr = s.split('::');
                                let stock = current_stock_arr[0];
                                stock = parseInt(stock);
                                if(isNaN(stock)) stock = 0;
                                const wh_name = current_stock_arr[1];
                                const wh_id = current_stock_arr[2];

                                if(stock_per_wh[wh_id] == null || stock_per_wh[wh_id] == undefined) {
                                    stock_per_wh[wh_id] = {
                                        wh_name,
                                        stock
                                    };
                                } else {
                                    stock_per_wh[wh_id]['stock'] += stock;
                                }
                            });
                        }

                        var stocksum=0;
                        for (const property in stock_per_wh) {
                            stocksum += stock_per_wh[property].stock;
                        }

                        let description = '';
                        if(p.description != null && p.description != '')
                            description = p.description;

						processed.push({
							'id': p.id,
							'text': p.manufacturer_name+ ' '+p.name+' ' + ' ' + p.width + '/' + height_percentage + p.radial_structure + p.diameter+' '+p.weight_flag+p.speed_flag+' '+fitting+' '+tt_type+' '+p.model+ ' ' + description +'  Ποσότητα: '+stocksum,
							'is_samprela': p.is_samprela
						});
					});
					return {
						results: processed
					};
				},
				cache: true
			}
		});

		// Init customer select2
		$('#customer_id').select2({
			// @todo - Get customer search results
			placeholder: "Επιλογή πελάτη",
			minimumInputLength: 2,
			ajax: {
				url: BASE_URL + '/customers/search',
				dataType: 'json',
				data: function (params) {
					return {
						_token: CSRF_TOKEN,
						q: $.trim(params.term)
					};
				},
				processResults: function (data) {
					let customers = data['customers'];
					return {
						results: customers
					};
				},
				cache: true
			}
		});

		// Init address select2
		$('#address_id').select2({});

		// On load address select2 is disabled.
		// At first, customer needs to be selected
		if($('#customer_id').val() == -1 || $('#customer_id').val() == null) {
			$('#address_id').prop('disabled', true);
		}else {
			$('.address-selection').removeClass('disabled');
			$('.new-address-btn').removeClass('disabled');
			$('#address_id').prop('disabled', false);
		}

		$('#address_id').on('change', function() {
			let address_id = $(this).val();
			address_id = parseInt(address_id);
			if(isNaN(address_id) || address_id == -1) return;

			let current_address = customer_addresses.find(address => address.address_id == address_id);
			if(!customer_addresses) return;

			$('#address_fullname').html(current_address.firstname + ' ' + current_address.lastname);
			$('#address_country_name').html(current_address.country_name);
			$('#address_company').html(current_address.company);
			$('#address_address_1').html(current_address.address_1);
			$('#address_address_2').html(current_address.address_2);
			$('#address_postcode').html(current_address.postcode);
			$('#address_city').html(current_address.city);
		});

		@if ($data['isEdit'])
		let customer_addresses = {!! $data['customer_addresses'] !!};
		buildCustomerAddresses(customer_addresses, {{ $data['order']['address_id'] }});
		$('#address_id').trigger('change');
		@else
		let customer_addresses = [];
		@endif
		// Handle on customer selection event
		$('#customer_id').on('change', function() {
			const value = $(this).val();
			if(value !== "-1") {
				$('.address-selection').removeClass('disabled');
				$('#address_id').prop('disabled', false);
				$('.new-address-btn').removeClass('disabled');

				// Maybe open after the results fill address select2
				$.ajax({
					url: BASE_URL + '/addresses/getCustomerAddresses',
					data: {
						_token: CSRF_TOKEN,
						customer_id: value
					},
					method: 'GET',
					dataType: 'json'
				})
				.done(function(res) {
					if(res.customer_addresses != null && res.customer_addresses != undefined) {
						customer_addresses = res.customer_addresses;
						buildCustomerAddresses(customer_addresses, '');
						$('#address_id').trigger('change');
						$('#address_id').select2('open');
					}
				})
				.fail(function() {});
			}else {
				$('.address-selection').addClass('disabled');
				$('#address_id').prop('disabled', true);
				$('.new-address-btn').addClass('disabled');
			}
		});

		function buildCustomerAddresses(customer_addresses, selected) {
			$('#address_id').html('');
			$('#address_id').append('<option value="-1" disabled>Επιλογή Διεύθυνσης</option>');
			customer_addresses.map(address => {
				let str = `
				<option value='${address.address_id}'`;
				if(selected == address.address_id) {
					str += ` selected`;
				}
				str += `>
					${address.firstname} ${address.lastname}, ${address.address_1}, ${address.postcode}, ${address.city}
				</option>
				`;
				$('#address_id').append(str);
			});
			if(selected != '') {
				$('#address_id').val(selected);
			}
		}

		// On create new address, show modal with new address form
		$('.new-address-btn').on('click', function() {
			Swal.fire({
				title: '<h3 class="mb-3">Συμπλήρωσε τα στοιχεία της νέας διεύθυνσης</h3>',
				width: 760,
				showCloseButton: false,
				showConfirmButton: false,
				html: `
				<div class="alert alert-danger" id="address-error" style="display: none;">
					<p>Τα πεδία με κόκκινο είναι υποχρεωτικά!</p>
				</div>
				<div class="kt-form kt-form--fit kt-form--label-right" id="kt_form" action="#" method="#" novalidate="novalidate" autocomplete="off">
					<div class="kt-portlet__body">
						<div class="form-group row">
							<div class="col-sm-6">
								<input type="text" class="form-control" value="" name="firstname" id="firstname" placeholder="Όνομα" aria-describedby="firstname-error" required autocomplete="off">
							</div>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="" name="lastname" id="lastname" placeholder="Επώνυμο" aria-describedby="lastname-error" required autocomplete="off">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6">
								<input type="text" class="form-control" value="" name="company" id="company" placeholder="Εταιρία" aria-describedby="company-error" required autocomplete="off">
							</div>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="" name="address_1" id="address_1" placeholder="Διεύθυνση 1" aria-describedby="address_1-error" required autocomplete="off">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6">
								<input type="text" class="form-control" value="" name="address_2" id="address_2" placeholder="Διεύθυνση 2" aria-describedby="address_2-error" autocomplete="off">
							</div>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="" name="city" id="city" placeholder="Πόλη" aria-describedby="city-error" required autocomplete="off">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6">
								<input type="text" class="form-control" value="" name="postcode" id="postcode" placeholder="Τ.Κ." aria-describedby="postcode-error" required autocomplete="off">
							</div>
							<div class="col-sm-6">
								<select class="form-control kt-select2" name="country_id" id="country_id" aria-describedby="country_id-error" required autocomplete="off">
									@foreach($data['countries'] as $country)
										<option value="{{ $country['country_id'] }}">{{ $country['name'] }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="kt-portlet__foot kt-portlet__foot--fit-x">
						<div class="kt-form__actions">
							<div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" id="save-new-address">
								Δημιουργία
							</div>
							<a href="#" class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" onclick="swal.closeModal(); return false;">Ακύρωση</a>
						</div>
					</div>
				</div>
				`,
				onOpen: function() {
					$('#country_id').select2({
						width: '100%',
						dropdownCssClass: "increasedzindexclass",
					});
				}
			})
		});

		$('body').on('click', '#save-new-address', function() {
			let firstname = $('#firstname').val();
			let lastname = $('#lastname').val();
			let company = $('#company').val();
			let address_1 = $('#address_1').val();
			let address_2 = $('#address_2').val();
			let city = $('#city').val();
			let postcode = $('#postcode').val();
			let country_id = $('#country_id').val();

			let new_address = {
				firstname,
				lastname,
				company,
				address_1,
				address_2,
				city,
				postcode,
				country_id
			}

			let hasError = false;
			Object.keys(new_address).map(key => {
				if(key != 'address_2') {
					let value = new_address[key];
					if(value == "") {
						$('#' + key).addClass('is-invalid');
						hasError = true;
					} else {
						$('#' + key).removeClass('is-invalid');
					}
				}
			});

			if(hasError) $('#address-error').show();
			else $('#address-error').hide();

			var	formData = new FormData();
			formData.append('_token', CSRF_TOKEN);
			formData.append('customer_id', $('#customer_id').val());
			Object.keys(new_address).map(key => {
				let value = new_address[key];
				formData.append(key, value);
			});

			$.ajax({
				url: BASE_URL + '/addresses',
				method: 'POST',
				data:formData,
				contentType: false,
				processData: false
			})
			.done(function(res) {
				if(res.success != undefined) {
					let address_id = res.address_id;
					new_address['address_id'] = address_id;
					new_address['country_name'] = $('#country_id option:selected').text();
					customer_addresses.push(new_address);
					buildCustomerAddresses(customer_addresses, address_id);
					$('#address_id').trigger('change');
					swal.closeModal();
				}
			})
			.fail(function(err) {
				console.log(err);
			})

		});

		$('#order_status').change(function() {
			let status = $(this).val();
			if(status == '3' || status == '4' || status == '5') {
				$('#payments').show();
				$('#add_payment').prop('disabled', false);
				$('.edit-payment').prop('disabled', false);
				$('.delete-payment').prop('disabled', false);
			} else {
				$('#payments').hide();
				$('#add_payment').prop('disabled', true);
				$('.edit-payment').prop('disabled', true);
				$('.delete-payment').prop('disabled', true);
			}
		});
		@if ($data['isEdit'])
		let order_id = $('#order_id').val();
		let customer_id = $('#customer_id').val();
		let discount_type = $('#discount_type').val();
		if(discount_type == -1) {
			$('#discount_amount').prop('disabled', true);
		}
		$('#discount_type').change(function() {
			let discount_type = $(this).val();
			if(discount_type == -1) {
				$('#discount_amount').val('');
				$('#discount_amount').prop('disabled', true);
			} else if(discount_type == 1) {
				$('#discount_amount').prop('disabled', false);
				$('#discount_amount').prop('max', '100');
				let discount_amount = $('#discount_amount').val();
				if(discount_amount > 100) {
					$('#discount_amount').val('');
				}
			} else if(discount_type == 2) {
				$('#discount_amount').prop('disabled', false);
				$('#discount_amount').prop('max', false);
			}

			$('#discount_amount').trigger('change');
		});
        @if(isset($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] == 2)
		let order_invoices_table = $('#order_invoices_table').DataTable({
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
					d.order_id = order_id;
				}
			},
			columns: [
				{data: 'invoice_id'},
				{data: 'invoice_date'},
				{data: 'invoice_status'},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: 2,
					title: 'Κατάσταση',
					render: function(data, type, full, meta) {
						const invoice_status = full.invoice_status;
						if(invoice_status == 0) return '<span>Ενεργό</span>';
						return '<span>Ακυρωμένο</span>';
					},
				},
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const invoiceId = full.invoice_id;
						let str = ``;
						if(full.invoice_status == 0) {
							str = `
								<a class="btn btn-sm btn-clean btn-icon btn-icon-md show-invoice pointer" data-id="` + invoiceId + `" title=" @if(isset($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] != 2) Προβολή Απόδειξη @else Προβολή Τιμολογίου @endif"
									href="${BASE_URL}/invoices/${invoiceId}"
									target="_blank"
								>
									<i class="la flaticon-interface-11"></i>
								</a>
								<span class="btn btn-sm btn-clean btn-icon btn-icon-md cancel-invoice pointer" data-id="` + invoiceId + `" title="Ακύρωση Τιμολογίου">
									<i class="la la-trash"></i>
								</span>`;
						}
						return str;
					},
				},
			],
		});
        @endif
		$('#add_invoice').click(function() {

			@if(isset($data['order']['type_of_receipt']) && $data['order']['type_of_receipt'] == 2)
                let invoice_status = 0;
                let self = this;
                Swal.fire({
                    title: 'Έκδοση Τιμολογίου',
                    text: 'Είστε σίγουρος ότι θέλετε να εκδόσετε τιμολόγιο για την παραγγελία;',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ναι, έκδοση'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: BASE_URL + '/invoices',
                            method: 'POST',
                            data: {
                                order_id,
                                invoice_status
                            },
                            dataType: 'json'
                        })
                            .done(function(res) {
                                if(res || res.status == 'success') {
                                    $('#add_invoice').prop('disabled', true);
                                }
                                order_invoices_table.ajax.reload();
                            })
                            .fail(function(err) {
                                console.log(err);
                            })
                    }
                })
            @else
                let invoice_status = 0;
                let self = this;
                Swal.fire({
                    title: 'Έκδοση Απόδειξης',
                    text: 'Είστε σίγουρος ότι θέλετε να εκδόσετε Απόδειξη για την παραγγελία;',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ναι, έκδοση'
                }).then((result) => {
                    if (result.value) {
                        window.open(BASE_URL + '/receipt/'+order_id);
                    }
                })
            @endif

		});

		$('body').on('click', '.cancel-invoice', function() {
			let invoice_id = $(this).data('id');
			let self = this;
			let invoice_status = 1;
			Swal.fire({
				title: 'Ακύρωση Τιμολογίου',
				text: 'Είστε σίγουρος ότι θέλετε να ακυρώσετε το τιμολόγιο;',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ναι, ακύρωση'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: BASE_URL + '/invoices/' + invoice_id,
						method: 'POST',
						data: {
							_method: 'PUT',
							invoice_id,
							order_id,
							invoice_status
						},
						dataType: 'json'
					})
					.done(function(res) {
						if(res || res.status == 'success') {
							$('#add_invoice').prop('disabled', false);
						}
						order_invoices_table.ajax.reload();
					})
					.fail(function(err) {
						console.log(err);
					})
				}
			})
		});

		let order_credit_invoices_table = $('#order_credit_invoices_table').DataTable({
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
				"url": BASE_URL + "/creditinvoices",
				"dataSrc": "data.data",
				"data": function ( d ) {
					d.order_id = order_id;
				}
			},
			columns: [
				{data: 'credit_invoice_id'},
				{data: 'invoice_date'},
				{data: 'invoice_status'},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: 2,
					title: 'Κατάσταση',
					render: function(data, type, full, meta) {
						const invoice_status = full.invoice_status;
						if(invoice_status == 0) return '<span>Ενεργό</span>';
						return '<span>Ακυρωμένο</span>';
					},
				},
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const invoiceId = full.credit_invoice_id;
						let str = ``;
						if(full.invoice_status == 0) {
						str = `
							<a class="btn btn-sm btn-clean btn-icon btn-icon-md show-credit-invoice pointer" data-id="` + invoiceId + `" title="Προβολή Πιστωτικού Τιμολογίου"
								href="${BASE_URL}/creditinvoices/${invoiceId}"
								target="_blank"
							>
								<i class="la flaticon-interface-11"></i>
							</a>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md cancel-credit-invoice pointer" data-id="` + invoiceId + `" title="Ακύρωση Πιστωτικού Τιμολογίου">
								<i class="la la-trash"></i>
							</span>`;
						}
						return str;
					},
				},
			],
		});

		$('#add_credit_invoice').click(function() {
			let invoice_status = 0;
			let self = this;
			Swal.fire({
				title: 'Έκδοση Πιστωτικού Τιμολογίου',
				text: 'Είστε σίγουρος ότι θέλετε να εκδόσετε πιστωτικό τιμολόγιο για την παραγγελία;',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ναι, έκδοση'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: BASE_URL + '/creditinvoices',
						method: 'POST',
						data: {
							order_id,
							invoice_status
						},
						dataType: 'json'
					})
					.done(function(res) {
						if(res || res.status == 'success') {
							$('#add_credit_invoice').prop('disabled', true);
						}
						order_credit_invoices_table.ajax.reload();
					})
					.fail(function(err) {
						console.log(err);
					})
				}
			})
		});

		$('body').on('click', '.cancel-credit-invoice', function() {
			let invoice_id = $(this).data('id');
			let self = this;
			let invoice_status = 1;
			Swal.fire({
				title: 'Ακύρωση Τιμολογίου',
				text: 'Είστε σίγουρος ότι θέλετε να ακυρώσετε το πιστωτικό τιμολόγιο;',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ναι, ακύρωση'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: BASE_URL + '/creditinvoices/' + invoice_id,
						method: 'POST',
						data: {
							_method: 'PUT',
							invoice_id,
							order_id,
							invoice_status
						},
						dataType: 'json'
					})
					.done(function(res) {
						if(res || res.status == 'success') {
							$('#add_credit_invoice').prop('disabled', false);
						}
						order_credit_invoices_table.ajax.reload();
					})
					.fail(function(err) {
						console.log(err);
					})
				}
			})
		});

		let order_payments_table = $('#order_payments_table').DataTable({
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
				"url": BASE_URL + "/payments",
				"dataSrc": "data.data",
				"data": function ( d ) {
					d.order_id = order_id;
				}
			},
			columns: [
				{data: 'payment_id'},
				{data: 'amount'},
				{data: 'date_of_payment'},
				{data: 'description'},
				{data: 'Ενέργειες', responsivePriority: -1},
			],
			columnDefs: [
				{
					targets: -1,
					title: 'Ενέργειες',
					orderable: false,
					render: function(data, type, full, meta) {
						const paymentId = full.payment_id;
						return `
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md edit-payment pointer" data-id="` + paymentId + `" title="Επεξεργασία Πληρωμής">
								<i class="la la-edit"></i>
							</span>
							<span class="btn btn-sm btn-clean btn-icon btn-icon-md delete-payment pointer" data-id="` + paymentId + `" title="Διαγραφή Πληρωμής">
								<i class="la la-trash"></i>
							</span>`;
					},
				},
			],
		});

		function addEditPaymentModal(edit = false, amount = '', date_payment = '', description = ``, payment_id = 0) {
			let ajaxUrl = BASE_URL + '/payments';
			let title = 'Προσθήκη';
			let value_payment_amount = '';
			let value_payment_date = '{{ date('d/m/Y') }}';
			let order_id = $('#order_id').val();
			let value_description = '';
			if(edit) {
				ajaxUrl = BASE_URL + '/payments/' + payment_id;
				title = 'Επεξεργασία';
				value_payment_amount = amount;
				value_payment_date = date_payment;
				value_description = description;
			}

			Swal.fire({
				title: title + ' Πληρωμής',
				html: `
					<div class="row">
						<div class="col-md-12">
							<label for="payment_amount">Ποσό</label>
							<input type="number" step="0.01" min="0" class="form-control" id="payment_amount" value="${value_payment_amount}">
						</div>
						<div class="col-md-12">
							<label for="payment_date">Ημ/νία</label>
							<input type="text" class="form-control" id="payment_date" value="${value_payment_date}">
						</div>
						<div class="col-md-12">
							<label for="description">Περιγραφή</label>
							<textarea type="text" class="form-control" id="description">${value_description}</textarea>
						</div>
					</div>
				`,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: title + ' Πληρωμής',
				onOpen: function() {
					$('#payment_date').datepicker({
						format: 'dd/mm/yyyy'
					});
				},
				preConfirm: function() {
					let payment_amount = Swal.getPopup().querySelector('#payment_amount').value;
					payment_amount = parseFloat(payment_amount);

					let payment_date = Swal.getPopup().querySelector('#payment_date').value
					if(isNaN(payment_amount)) {
						Swal.showValidationMessage(`Το ποσό πληρωμής πρέπει να είναι αριθμός`)
					}

					let description = Swal.getPopup().querySelector('#description').value;

					return {
						payment_amount,
						payment_date,
						description
					}
				}
			}).then((result) => {
				if(result.value) {
					let payment_amount = result.value.payment_amount;
					let payment_date = result.value.payment_date;
					payment_date = payment_date.split('/').reverse().join('-');
					let description = result.value.description;
					let customer_id = $('#customer_id').val();
					let data = {
						_token: CSRF_TOKEN,
						order_id,
						customer_id,
						amount: payment_amount,
						date_of_payment: payment_date,
						description
					};
					let formdata = new FormData();
					Object.keys(data).map(o => {
						formdata.append(o, data[o]);
					})
					if(edit) {
						formdata.append('_method', 'PUT');
					}
					$.ajax({
						url: ajaxUrl,
						method: 'POST',
						data: formdata,
						contentType: false,
        				processData: false
					})
					.done(function(res) {
						if(res.success || res.status == 'success') {
							order_payments_table.ajax.reload();
						}
					})
					.fail(function(err) {
						console.log(err);
					})
				}
			})
		}

		$('#add_payment').click(function() {
			addEditPaymentModal();
		});

		$('body').on('click', '.edit-payment', function() {
			let self = this;
			let id = $(this).data('id');
			let tr = $(this).parent().parent();
			let d = order_payments_table.row( $(tr) ).data();
			let dop = d.date_of_payment
			dop = dop.substr(0, 10).split('-').reverse().join('/');
			let amount = d.amount;
			let description = d.description;
			addEditPaymentModal(true, amount, dop, description, id);
		});

		$('body').on('click', '.delete-payment', function() {
			let self = this;
			let id = $(this).data('id');
			Swal.fire({
				title: 'Διαγραφή',
				text: "Είστε σίγουρος ότι θελετε να διαγράψετε την πληρωμή από την παραγγελία?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ναι, διαγραφή',
				cancelButtonText: 'Ακύρωση'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: BASE_URL + '/payments/' + id,
						type: 'POST',
						data: {
							_method: "DELETE",
							_token: CSRF_TOKEN
						},
						dataType: 'json'
					})
					.done(function(res) {
						order_payments_table.ajax.reload();
					})
					.fail(function(err) {
						console.log(err);
					})
				}
			})
		});
		@endif

		$('#new-order-product').on('click', function(e) {
			e.preventDefault();
			var current_row_id = parseInt($('#order_products_table tbody tr').last().attr('data-row'));
			var row_counter = $('#order_products_table tbody tr').length + 1;
			if(isNaN(current_row_id)) current_row_id = -1;
			const new_row = `
			<tr data-row="${current_row_id + 1}">
				<th scope="row">${row_counter}</th>
				<td>
					<select class="form-control order-product-select" name="order_product[${current_row_id + 1}][product_id]"></select>
				</td>
				<td>
					<div class="form-group">
						<select class="form-control order-product-shelf-select" name="order_product[${current_row_id + 1}][product_quantity_id]" required></select>
						<input type="number" min="0" step="1" class="form-control order-product-quantity" name="order_product[${current_row_id + 1}][quantity]" placeholder="Ποσότητα" required/>
					</div>
				</td>
				<td class="td-order-product-price">
					<input type="number" min="0" step="0.01" class="form-control order-product-price" name="order_product[${current_row_id + 1}][price]" placeholder="Τιμή" required/>
				</td>
				<td class="td-order-product-tax">
					<input type="number" min="0" step="0.01" class="form-control order-product_tax" name="order_product[${current_row_id + 1}][product_tax]" placeholder="Φ.Π.Α. προϊόντος" readonly required/>
				</td>
				<td>
					<span class="order-product-total"></span>
				</td>
				<td>
					<span class="order-product-return-perc">0</span>%
					<br><span class="order-product-return-total">0</span>&euro;
				</td>
				<td>
					<span class="order-product-discount-perc">0</span>%
					<br><span class="order-product-discount-total">0</span>&euro;
				</td>
				<td>
					<a href="#" class="btn delete-order-product"><i class="la la-trash"></i></a>
				</td>
            <td>
                <select class="form-control order-product-taxclass-select" name="order_product[${current_row_id + 1}][tax_class_id]">
                @foreach ($data['taxclasses'] as $taxclass)
                <option value="{{ $taxclass['tax_class_id'] }}" data-taxclass-type="{{ $taxclass['type'] }}" data-taxclass-amount="{{ $taxclass['amount'] }}">{{ $taxclass['name'] }}</option>
                @endforeach
                </select>
            </td>
            <td>
                <input type="checkbox" value="{{ $data['environmental_tax'] }}" name="order_product[${current_row_id + 1}][environmental_tax]" class="environmental_tax" checked>
            </td>
			</tr>
			`;
			$('#order_products_table tbody').append(new_row);

			$('#order_products_table tbody tr[data-row="'+ (current_row_id + 1) +'"] .order-product-select').select2({
				placeholder: 'Κωδικός/Όνομα/Διάσταση προιόντος',
				minimumInputLength: 2,
				ajax: {
					url: BASE_URL + '/products/search/',
					dataType: 'json',
					data: function (params) {
						return {
							_token: CSRF_TOKEN,
							q: $.trim(params.term),
                            stock_order: 1,
							instock: 1
						};
					},
					processResults: function (data) {
						let products = data['products'];
						let processed = [];
						products.map(p => {
						    var tt_type='TL'
						    if(p.tube_type == '1')
                                tt_type = "TT";
                            if(p.tube_type == '2')
                                tt_type = "TL/TT";

                            var fitting='Ε';
                            if(p.fitting_position == 2)
                                fitting='Π';
                            if(p.fitting_position == 3)
                                fitting='Ε/Π';
                            let stock_info = p['stock_info'];

                            const stock_per_wh = {};
                            if(stock_info != null)
                            {
                                let all_stock = stock_info.split(',');
                                all_stock.map(s => {
                                    let current_stock_arr = s.split('::');
                                    let stock = current_stock_arr[0];
                                    stock = parseInt(stock);
                                    if(isNaN(stock)) stock = 0;
                                    const wh_name = current_stock_arr[1];
                                    const wh_id = current_stock_arr[2];

                                    if(stock_per_wh[wh_id] == null || stock_per_wh[wh_id] == undefined) {
                                        stock_per_wh[wh_id] = {
                                            wh_name,
                                            stock
                                        };
                                    } else {
                                        stock_per_wh[wh_id]['stock'] += stock;
                                    }
                                });
                            }

                            var stocksum=0;
                            for (const property in stock_per_wh) {
                                stocksum += stock_per_wh[property].stock;
                            }

							let description = p['description'];
							if(description == null) description = '';

                            processed.push({
								'id': p.id,
								'text':p.manufacturer_name+ ' '+p.name+' ' + ' ' + p.width + '/' + p.height_percentage + p.radial_structure + p.diameter+' '+p.weight_flag+p.speed_flag+' '+fitting+' '+tt_type+' '+p.model+ ' ' + description + '  Ποσότητα: '+stocksum,
								'is_samprela': p.is_samprela
							});
						});
						return {
							results: processed
						};
					},
					cache: true
				}
			});

			current_row_id++;
		});

		// Handle delete order product row
		$('body').on('click', '.delete-order-product', function(e) {
			e.preventDefault();
			let self = this;
			Swal.fire({
				title: 'Διαγραφή',
				text: "Είστε σίγουρος ότι θελετε να διαγράψετε το προϊόν από την παραγγελία?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ναι, διαγραφή',
				cancelButtonText: 'Ακύρωση'
			}).then((result) => {
				if (result.value) {
					$(self).parent().parent().remove();
					$('#order_products_table tbody tr > th').each(function(i, el) {
						$(this).text(i + 1);
					})
					updateTotals();
				}
			})
		})

		$('body').on('change', '.order-product-select', function() {
			let pid = $(this).val();
			let is_samprela = 0;
			let select_data = $(this).select2('data');
			if(select_data.length > 0) {
				select_data = select_data[0];
				is_samprela = select_data.is_samprela;
			}
			if(is_samprela == 1) {
				$(this).parents('tr').find('.environmental_tax').prop('checked', false);
			}
			let self = this;
			let order_id = $('#order_id').val();

			$.ajax({
				url: BASE_URL + '/products/getProductOrder',
				method: 'GET',
				data: {
					_token: CSRF_TOKEN,
					product_id: pid,
					order_id
				},
				dataType: 'json'
			})
			.done(function(res) {
				let order_product_price_el = $(self).parent().parent().find('.order-product-price');
				let order_product_return_el = $(self).parent().parent().find('.order-product-return-perc');
				let order_product_discount_el = $(self).parent().parent().find('.order-product-discount-perc');

				let loadFromDB = $(order_product_price_el).data('load-from-db');
				loadFromDB = (loadFromDB == 1);
				let old_price = $(order_product_price_el).val();
				let changePrice = false;

				if(!loadFromDB) {
					changePrice = true;
				} else {
					if(old_price == '') changePrice = true;
					loadFromDB = false;
					$(order_product_price_el).data('load-from-db', '0');
				}

				if(changePrice) {
					let price = 0;
					if(res.prices != null) {
                       price = res.prices.general_price;
					}
					$(order_product_price_el).val(price).trigger('change');
				}
				let shelf_select = $(self).parent().parent().find('.order-product-shelf-select');
				let preselect = shelf_select.data('preselect');
				let options = '';

				let tmpqtyarr = res.quantities.sort(function(a, b) {
					return a.shelf.name.localeCompare(b.shelf.name, undefined, {numeric: true});
				});
				tmpqtyarr.map(qty => {
					if(preselect != '' && preselect == qty.product_quantity_id) {
						options += '<option value="' + qty.product_quantity_id + '" data-stock="' + qty.stock + '" selected>' + qty.stock + ' | ' + qty.batch + ' | ' + qty.shelf.name + '</option>';
					} else {
						options += '<option value="' + qty.product_quantity_id + '" data-stock="' + qty.stock + '">' + qty.stock + ' | ' + qty.batch + ' | ' + qty.shelf.name + '</option>';
					}
				});
				shelf_select.html(options);
				shelf_select.trigger('change');

				let discounts = res.discounts;
				$(order_product_return_el).text(discounts.return);
				$(order_product_discount_el).text(discounts.discount);
			})
			.fail(function(err) {
				console.log(err);
			});
		});

		@if ($data['isEdit'])
		$('.order-product-select').trigger('change');
		@endif

		var skipRows = parseInt($('#order_products_table tbody tr').last().attr('data-row'));

		//Change shelf => update max attribute on product quantity
		$('body').on('change', '.order-product-shelf-select', function() {
			let maxStock = $(this).find('option:selected').data('stock');
			let qtyEl = $(this).parent().parent().parent().find('.order-product-quantity');
			if(skipRows >= 0)
			{
				skipRows--;
				return;
			}
			let current_qty = qtyEl.val();

			if(current_qty > maxStock) {
				qtyEl.val(maxStock);
			}

			qtyEl.prop('max', maxStock);
			qtyEl.trigger('change');
		});

		//Change price -> update tax for product
		$('body').on('change', '.order-product-price', function() {
			let price = $(this).val();
			let row = $(this).parent().parent();
			let taxselect = $(row).find('.order-product-taxclass-select');
			let taxselected = $(taxselect).find('option:selected');

			let taxtype = $(taxselected).data('taxclass-type');
			let taxamount = $(taxselected).data('taxclass-amount');

			//wait for element to appear
            setTimeout(function(){

                let discount_perc = $(row).find('.order-product-discount-perc').html();
                let discount_total = 0;
                if(discount_perc > 0)
                    discount_total = (price * discount_perc) / 100;

                var discountElement = $(row).find('.order-product-discount-perc')[0];
                console.log('found discount'+discount_perc);
                console.log(discountElement);
                console.log(discountElement.innerHTML);

                if(taxtype == 1) {
                    //Stathero poso
                    $(row).find('.order-product_tax').val(taxamount.toFixed(2));
                } else {
                    let tax_perc = taxamount / 100;
                    let tax = (price-discount_total) * tax_perc;
                    $(row).find('.order-product_tax').val(tax.toFixed(2));
                }
                updateTotals();
                updateProductTotal(row);

            }, 250);

		});
		//Change taxclass -> update tax for product
		$('body').on('change', '.order-product-taxclass-select', function() {
			let taxselect = $(this);
			let row = $(this).parent().parent();
			let price = $(row).find('.order-product-price').val();
			let taxselected = $(taxselect).find('option:selected');
			let taxtype = $(taxselected).data('taxclass-type');
			let taxamount = $(taxselected).data('taxclass-amount');
			if(taxtype == 1) {
				//Stathero poso
				$(row).find('.order-product_tax').val(taxamount.toFixed(2));
			} else {
				let tax_perc = taxamount / 100;
				let tax = (price-discount_total) * tax_perc;
				$(row).find('.order-product_tax').val(tax.toFixed(2));
			}
			updateTotals();
			updateProductTotal(row);
		});

		$('body').on('change', '.order-product-quantity', function() {
			let row = $(this).parent().parent().parent();

			updateTotals();
			updateProductTotal(row);
		});

		$('body').on('change', '.environmental_tax', function() {
			const rowEl = $(this).parent().parent();

			updateProductTotal(rowEl);
			updateTotals();
		});

		function updateProductTotals() {
			$('#order_products_table tr').each(function(i, el) {
				updateProductTotal(el);
			})
		}

		function updateProductTotal(rowEl) {
			let price = $(rowEl).find('.order-product-price').val();
			let tax = $(rowEl).find('.order-product_tax').val();
			let qty = $(rowEl).find('.order-product-quantity').val();

			let return_perc = $(rowEl).find('.order-product-return-perc').text();
			let discount_perc = $(rowEl).find('.order-product-discount-perc').text();

			price = parseFloat(price);
			if(isNaN(price) || price < 0) price = 0;

			tax = parseFloat(tax);
			if(isNaN(tax) || tax < 0) tax = 0;

			qty = parseInt(qty);
			if(isNaN(qty) || qty < 0) qty = 0;

			return_perc = parseInt(return_perc);
			if(isNaN(return_perc) || return_perc < 0) return_perc = 0;

			discount_perc = parseInt(discount_perc);
			if(isNaN(discount_perc) || discount_perc < 0) discount_perc = 0;

			let environmental_tax = 0;

			if($(rowEl).find('.environmental_tax').is(':checked')) {
				environmental_tax = parseFloat($(rowEl).find('.environmental_tax').val());
				if(isNaN(environmental_tax) || environmental_tax < 0) environmental_tax = 0;
			}

            let discount_total = (qty * price) * (discount_perc / 100);
            let return_total = (qty * price) * (return_perc / 100);

			let total = (qty * (price + tax + environmental_tax))- discount_total;
			$(rowEl).find('.order-product-total').html('<b>' + total.toFixed(2) + '€</b>');

			$(rowEl).find('.order-product-discount-total').text(discount_total.toFixed(2));
			$(rowEl).find('.order-product-return-total').text(return_total.toFixed(2));
		}

		$('#shipping_cost').change(function() {
			updateTotals();
		});

		$('#payment_cost').change(function() {
			updateTotals();
		});

		$('#manage_cost').change(function() {
			updateTotals();
		});

		$('#discount_amount').change(function() {
			updateTotals();
		});

		function updateTotals() {
			let total_discount_from_product = 0;
			let paid = parseFloat(order_paid);
			if(isNaN(paid)) paid = 0;
			let subtotal = 0;
			let tax_total = 0;
			let environmental_tax_total = 0;

			$('#order_products_table tr').each(function(i, el) {
				let current_price = $(this).find('.order-product-price').val();
				let current_tax = $(this).find('.order-product_tax').val();
				let current_qty = $(this).find('.order-product-quantity').val();

				current_qty = parseInt(current_qty);
				if(isNaN(current_qty)) current_qty = 0;

				current_price = parseFloat(current_price);
				if(!isNaN(current_price)) subtotal += (current_price * current_qty);

				current_tax = parseFloat(current_tax);
				if(!isNaN(current_tax)) tax_total += (current_tax * current_qty);

				let environmental_tax = 0;
				if($(this).find('.environmental_tax').is(':checked')) {
					environmental_tax = parseFloat($(this).find('.environmental_tax').val());
					if(isNaN(environmental_tax) || environmental_tax < 0) environmental_tax = 0;
					environmental_tax_total += (environmental_tax * current_qty);
				}

				let discount_perc = $(this).find('.order-product-discount-perc').text();
				discount_perc = parseFloat(discount_perc);
				if(isNaN(discount_perc) || discount_perc < 0) discount_perc = 0;
				let discount_total = current_price * current_qty * (discount_perc / 100);

				total_discount_from_product += ((isNaN(discount_total)) ? 0 : discount_total * 100)/100;
			});
            total_discount_from_product = Math.round(total_discount_from_product*100)/100;
			$('#discount_amount_from_group').val(total_discount_from_product);

			let shipping_cost = $('#shipping_cost').val();
			shipping_cost = parseFloat(shipping_cost);
			if(isNaN(shipping_cost)) shipping_cost = 0;

			let payment_cost = $('#payment_cost').val();
			payment_cost = parseFloat(payment_cost);
			if(isNaN(payment_cost)) payment_cost = 0;

			let total = subtotal + tax_total + shipping_cost + payment_cost + environmental_tax_total;

			let discount_amount = $('#discount_amount').val();
			discount_amount = parseFloat(discount_amount);
			if(isNaN(discount_amount)) discount_amount = 0;
			let final_discount = 0;
			let discount_type = $('#discount_type').val();
			if(discount_type == 1) {
				//percentage
				let percentage = discount_amount / 100;
				final_discount = total * percentage;
			} else if(discount_type == 2) {
				final_discount = discount_amount;
			}

			let manage_cost = $('#manage_cost').val();
			manage_cost = parseFloat(manage_cost);
			if(isNaN(manage_cost) || manage_cost < 0) manage_cost = 0;
			total += manage_cost;

			let total_with_discount = total - final_discount - total_discount_from_product;
			let remainder = total_with_discount - paid;
			subtotal = subtotal.toFixed(2);
			tax_total = tax_total.toFixed(2);

			$('#order-subtotal').text(subtotal + '€');
			$('#order-tax_total').text(tax_total + '€');
			$('#order-environmental_tax_total').text(environmental_tax_total.toFixed(2) + '€');
			if(final_discount > 0) {
				$('#order-total').html(total.toFixed(2) + '€ - ' + final_discount.toFixed(2) + '€ = <b>' + total_with_discount.toFixed(2) + '€</b>');
			} else {
				$('#order-total').html(total.toFixed(2) + '€');
			}

			$('#order-remainder').text(remainder.toFixed(2) + '€');

		}
	});
	var _scannerIsRunning = false;
	var App = {
		init: function() {
			var self = this;
			Quagga.init({
				inputStream: {
					name: "Live",
					type: "LiveStream",
					target: document.querySelector('#camera-feedback'),
					constraints: {
						facingMode: "environment"
					},
				},
				decoder: {
					readers: [
						"code_128_reader"
					],
					debug: {
						showCanvas: true,
						showPatches: true,
						showFoundPatches: true,
						showSkeleton: true,
						showLabels: true,
						showPatchLabels: true,
						showRemainingPatchLabels: true,
						boxFromPatches: {
							showTransformed: true,
							showTransformedBox: true,
							showBB: true
						}
					}
				},

			}, function (err) {
				if (err) {
					window.alert(err);
					return
				}
			});
	}};
	$( document ).ready(function() {
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
		|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))
		)
		{
			$('#new-product-scan').show();
			App.init();
			Quagga.onProcessed(function (result) {
				var drawingCtx = Quagga.canvas.ctx.overlay,
				drawingCanvas = Quagga.canvas.dom.overlay;

				if (result) {
					if (result.boxes) {
						drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
						result.boxes.filter(function (box) {
							return box !== result.box;
						}).forEach(function (box) {
							Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
						});
					}

					if (result.box) {
						Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
					}

					if (result.codeResult && result.codeResult.code) {
						Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
					}
				}
			});

			Quagga.onDetected(function (result)
			{
				let result_code = result.codeResult.code;
				if(result_code.includes("product::"))
				{
					closeQuagga()
					let product_id = result_code.split("::")[1];
					addBarCodeProduct(product_id);
				}
				else if(result_code.includes("shelf::"))
				{
					closeQuagga()
					let shelf_id = result_code.split("::")[1];
					addWaittingShelfToOrder(shelf_id);
				}
				else
					window.alert('Λάθος Barcode')
			});

			function closeQuagga() {
				Quagga.stop();
				_scannerIsRunning = false;
				$( "#camera-input" ).hide();
				$('#camera-feedback').html('');
			}
		}
	});
	$( "#new-product-scan , #close-scan" ).click(function()
	{
		if (_scannerIsRunning)
		{
			$("#camera-input").hide();
			$('#camera-feedback').html('');
			Quagga.stop();
			_scannerIsRunning = false;
		}
		else
		{
			App.init();
			Quagga.start();
			$( "#camera-input" ).show();
			_scannerIsRunning = true;
		}
	});

	function addBarCodeProduct(product_id)
	{
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$('#new-order-product').click();
		var request;
		request = $.ajax({
		url: BASE_URL + '/products/search',
		method: 'get',
		data: {
			product_id:product_id,
			_token: CSRF_TOKEN
		},
		cache:false
		});
		request.done(function (response, textStatus, jqXHR)
		{
			var current_row_id = parseInt($('#order_products_table tbody tr').last().attr('data-row'));
            var tt_type='TL';
            if(response.products[0].tube_type == '1')
                tt_type = "TT";
            if(response.products[0].tube_type == '2')
                tt_type = "TL/TT";

            var fitting='Ε';
            if(response.products[0].fitting_position == 2)
                fitting='Π';
            if(response.products[0].fitting_position == 3)
                fitting='Ε/Π';

            $( "select[name='order_product["+current_row_id+"][product_id]']" ).append( '<option value="'+response.products[0].id+'" selected>'+response.products[0].manufacturer_name+ ' '+response.products[0].name+' ' + ' ' + response.products[0].width + '/' + response.products[0].height_percentage + response.products[0].radial_structure + response.products[0].diameter+' '+response.products[0].weight_flag+response.products[0].speed_flag+' '+fitting+' '+tt_type+' '+response.products[0].model+'</option>' );
			$( "select[name='order_product["+current_row_id+"][product_id]']" ).trigger('change');
		});
		request.fail(function (jqXHR, textStatus, errorThrown)
		{
			window.alert('Το προϊόν δεν βρέθηκε.');
		});
		request.always(function ()
		{

		});
	}

	function addWaittingShelfToOrder(shelf_id)
	{
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$('#new-order-product').click();
		var request;
		request = $.ajax({
		url: BASE_URL + '/order/addWaittingShelf',
		method: 'get',
		data: {
			shelf_id:shelf_id,
			order_id: {{ (!empty($data['order']['order_id'])) ? $data['order']['order_id'] : '-1' }},
			_token: CSRF_TOKEN
		},
		cache:false
		});
		request.done(function (response, textStatus, jqXHR)
		{
			if(response.success)
			{
				$("#order_status option:selected").prop("selected", false);
				$('#order_status .selDiv option:eq(3)').prop('selected', true);
				window.alert('Η παραγγελία αντιστοιχίστηκε στο ράφι αναμονής');
			}
			else if(response.error)
				window.alert(response.error);
			else
				window.alert(response);
		});
		request.fail(function (jqXHR, textStatus, errorThrown)
		{
			window.alert('Το ράφι δεν βρέθηκε.');
		});
		request.always(function ()
		{

		});
	}
</script>
@endsection
