@extends('layouts.app')

@section('custom_css')
<link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
    <div class="row mb-4">
        <div class="col-xl-12">
            <a href="{{ route('customers.edit', $customer['customer_id']) }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                <i class="la la-edit"></i> Επεξεργασία Πελάτη
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-5">
            <!--begin:: Widgets/Invoice Details-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Λεπτομέρειες Πελάτη
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Ονοματεπώνυμο</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['company_name']) ? $customer['company_name'] : '-' !!}</span>
                                </div>
                            </div>
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Τηλέφωνο</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['phone']) ? $customer['phone'] : '-' !!}</span>
                                </div>
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Κινητό</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['mobile']) ? $customer['mobile'] : '-' !!}</span>
                                </div>
                            </div>
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Fax</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['fax']) ? $customer['fax'] : '-' !!}</span>
                                </div>
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Α.Φ.Μ.</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['tax_id']) ? $customer['tax_id'] : '-' !!}</span>
                                </div>
                            </div>
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Δ.Ο.Υ.</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['tax_office']) ? $customer['tax_office'] : '-' !!}</span>
                                </div>
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Όνομα Εταιρίας</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['company_name']) ? $customer['company_name'] : '-' !!}</span>
                                </div>
                            </div>
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Είδος Εταιρίας</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['company_kind']) ? $customer['company_kind'] : '-' !!}</span>
                                </div>
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Email</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['email']) ? $customer['email'] : '-' !!}</span>
                                </div>
                            </div>
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Σχόλια</span>
                                    <span class="kt-widget12__value">{!! !empty($customer['comments']) ? $customer['comments'] : '-' !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Invoice Details-->
        </div>
        <div class="col-xl-7">

            <!--begin:: Widgets/Contact Details-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Διευθύνσεις Πελάτη
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Ονοματεπώνυμο</th>
                                    <th scope="col">Εταιρία</th>
                                    <th scope="col">Διεύθυνση 1</th>
                                    <th scope="col">Διεύθυνση 2</th>
                                    <th scope="col">Πόλη</th>
                                    <th scope="col">Τ.Κ.</th>
                                    <th scope="col">Χώρα</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addresses as $key=>$address)
                                    <tr>
                                        <th scope="row">{{ ++$key}}</th>
                                        <th>{{ $address['firstname'] }} {{ $address['lastname'] }}</th>
                                        <th>{{ $address['company'] }}</th>
                                        <th>{{ $address['address_1'] }}</th>
                                        <th>{{ $address['address_2'] }}</th>
                                        <th>{{ $address['city'] }}</th>
                                        <th>{{ $address['postcode'] }}</th>
                                        <th>{{ $address['country_id'] }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Contact Details-->
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Σύνολα
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-md-3 col-sm-4">
                            <div class="kt-widget26">
                                <div class="kt-widget26__content">
                                    <span class="kt-widget26__desc">Σύνολο Παραγγελιών</span>
                                    <span class="kt-widget26__number">{{ $balances['orders_total'] }} €</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="kt-widget26">
                                <div class="kt-widget26__content">
                                    <span class="kt-widget26__desc">Υπόλοιπο Παραγγελιών</span>
                                    <span class="kt-widget26__number">{{ $balances['orders_total'] - $balances['orders_paid']  }} €</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="kt-widget26">
                                <div class="kt-widget26__content">
                                    <span class="kt-widget26__desc">Πιστωτικό Υπόλοιπο</span>
                                    <span class="kt-widget26__number">{{ $balances['credit'] - ($balances['orders_total'] - $balances['orders_paid']) }} €</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="kt-widget26">
                                <div class="kt-widget26__content">
                                    <span class="kt-widget26__desc">Συνολικός Τζίρος</span>
                                    <span class="kt-widget26__number">{{ $balances['whole_total'] }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Invoice Details-->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Πληρωμές Πελάτη
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="order_payments_table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Ποσό</th>
                                                    <th scope="col">Ημ/νία Πληρωμής</th>
                                                    <th scope="col">Παραγγελία</th>
                                                    <th scope="col">Περιγραφή</th>
                                                    <th scope="col">Ενέργειες</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
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
            <!--end:: Widgets/Invoice Details-->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Παραγγελίες Πελάτη
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped- table-bordered table-hover table-checkable" id="customer_orders_table">
                                            <thead>
                                                <tr>
                                                    <th>ID Παραγγελίας</th>
                                                    <th>Πελάτης</th>
                                                    <th>Σύνολο</th>
                                                    <th>Πληρωμένο ποσό</th>
                                                    <th>Υπόλοιπο</th>
                                                    <th>Κατάσταση</th>
                                                    <th>Ημ/νία</th>
                                                    <th>Ενέργειες</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Invoice Details-->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Τιμολόγια Πελάτη
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped- table-bordered table-hover table-checkable" id="customer_invoices_table">
                                            <thead>
                                                <tr>
                                                    <th>ID Τιμολογίου</th>
                                                    <th>Παραγγελία</th>
                                                    <th>Κατάσταση</th>
                                                    <th>Ημ/νία</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Invoice Details-->
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Πιστωτικά Τιμολόγια Πελάτη
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped- table-bordered table-hover table-checkable" id="customer_creditinvoices_table">
                                            <thead>
                                                <tr>
                                                    <th>ID Τιμολογίου</th>
                                                    <th>Παραγγελία</th>
                                                    <th>Κατάσταση</th>
                                                    <th>Ημ/νία</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Invoice Details-->
        </div>
    </div>
    
</div>
@endsection

@section('custom_script')
<script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datatable/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/app/custom/order/list.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let orders = [];
    $.ajax({
        url: BASE_URL + '/customers/getOrders',
        method: 'GET',
        data: {
            customer_id: {{ $customer['customer_id'] }}
        },
        dataType: 'json'
    })
    .done(function(res) {
        if(res.orders) {
            orders = res.orders;
        }
    })
    .fail(function(err) {
        console.log(err);
    })

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
                d.customer_id = {{ $customer['customer_id'] }};
            }
        },
        columns: [
            {data: 'payment_id'},
            {data: 'amount'},
            {data: 'date_of_payment'},
            {data: 'order_id'},
            {data: 'description'},
            {data: 'Ενέργειες', responsivePriority: -1},
        ],
        columnDefs: [
            {
                targets: 3,
                render: function(data, type, full, meta) {
                    const order_id = full.order_id;
                    if(order_id == null) return '';
                    return `<a class="btn btn-secondary btn-sm" target="_blank" href="${BASE_URL}/orders/${order_id}/edit"><i class="la flaticon-interface-11"></i> ${order_id}</a>`;
                },
            },
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

    let customer_invoices_table = $('#customer_invoices_table').DataTable({
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
                d.customer_id = {{ $customer['customer_id'] }};
            }
        },
        columns: [
            {data: 'invoice_id'},
            {data: 'order_id'},
            {data: 'invoice_date'},
            {data: 'invoice_status'},
        ],
        columnDefs: [
            {
                targets: 0,
                render: function(data, type, full, meta) {
                    const invoice_id = full.invoice_id;
                    if(full.invoice_status == 0) return `<a class="btn btn-secondary btn-sm" target="_blank" href="${BASE_URL}/invoices/${invoice_id}"><i class="la flaticon-interface-11"></i> ${invoice_id}</a>`;
                    return `${invoice_id}`;
                },
            },
            {
                targets: 1,
                render: function(data, type, full, meta) {
                    const order_id = full.order_id;
                    if(order_id == null) return '';
                    return `<a class="btn btn-secondary btn-sm" target="_blank" href="${BASE_URL}/orders/${order_id}/edit"><i class="la flaticon-interface-11"></i> ${order_id}</a>`;
                },
            },
            {
                targets: 2,
                render: function(data, type, full, meta) {
                    const invoice_status = full.invoice_status;
                    if(invoice_status == 0) {
                        return 'Ενεργό';
                    }
                    return 'Ακυρωμένο';
                },
            },
        ],
    });

    let customer_creditinvoices_table = $('#customer_creditinvoices_table').DataTable({
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
                d.customer_id = {{ $customer['customer_id'] }};
            }
        },
        columns: [
            {data: 'invoice_id'},
            {data: 'order_id'},
            {data: 'invoice_date'},
            {data: 'invoice_status'},
        ],
        columnDefs: [
            {
                targets: 0,
                render: function(data, type, full, meta) {
                    const credit_invoice_id = full.credit_invoice_id;
                    if(full.invoice_status == 0) return `<a class="btn btn-secondary btn-sm" target="_blank" href="${BASE_URL}/creditinvoices/${credit_invoice_id}"><i class="la flaticon-interface-11"></i> ${credit_invoice_id}</a>`;
                    return `${credit_invoice_id}`;
                },
            },
            {
                targets: 1,
                render: function(data, type, full, meta) {
                    const order_id = full.order_id;
                    if(order_id == null) return '';
                    return `<a class="btn btn-secondary btn-sm" target="_blank" href="${BASE_URL}/orders/${order_id}/edit"><i class="la flaticon-interface-11"></i> ${order_id}</a>`;
                },
            },
            {
                targets: 2,
                render: function(data, type, full, meta) {
                    const invoice_status = full.invoice_status;
                    if(invoice_status == 0) {
                        return 'Ενεργό';
                    }
                    return 'Ακυρωμένο';
                },
            },
        ],
    });
    
    function addEditPaymentModal(edit = false, amount = '', date_payment = '', description='' ,payment_id = 0) {
        let ajaxUrl = BASE_URL + '/payments';
        let title = 'Προσθήκη';
        let value_payment_amount = '';
        let value_description = '';
        let value_payment_date = '{{ date('d/m/Y') }}';
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
                    <div class="col-md-12" ${ edit ? ` style="display: none;"` : ''}>
                        <label for="payment_order_id">Παραγγελία</label>
                        <select class="form-control custom-select2" id="payment_order_id" name="payment_order_id"></select>
                    </div>
                    <div class="col-md-12">
                        <label for="description">Περιγραφή</label>
                        <input type="text" class="form-control" id="payment_descr" value="${value_description}">
                    </div>
                    <div class="col-md-12">
                        <label for="payment_amount">Ποσό</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="payment_amount" value="${value_payment_amount}">
                    </div>
                    <div class="col-md-12">
                        <label for="payment_date">Ημ/νία</label>
                        <input type="text" class="form-control" id="payment_date" value="${value_payment_date}">
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

                $('#payment_order_id').html('');
                let options = '<option value="">Καμία</option>';
                orders.map(o => {
                    let created = o.created_at;
                    created = created.split(' ');
                    if(created.length > 0) {
                        created = created[0].split('-').reverse().join('/');
                    } else {
                        creaeted = '';
                    }
                    options += "<option value='" + o.order_id + "'>" + o.order_id + " (" + created + ")</option>";
                });
                $('#payment_order_id').html(options);
                $('#payment_order_id').select2({
                    dropdownCssClass: 'increasedzindexclass'
                });
            },
            preConfirm: function() {
                let payment_amount = Swal.getPopup().querySelector('#payment_amount').value;
                payment_amount = parseFloat(payment_amount);
                
                let payment_date = Swal.getPopup().querySelector('#payment_date').value
                if(isNaN(payment_amount)) {
                    Swal.showValidationMessage(`Το ποσό πληρωμής πρέπει να είναι αριθμός`)
                }

                let order_id = Swal.getPopup().querySelector('#payment_order_id').value
                let description = Swal.getPopup().querySelector('#payment_descr').value
                return {
                    payment_amount,
                    payment_date,
                    order_id,
                    description
                };
            }
        }).then((result) => {
            if(result.value) {
                let payment_amount = result.value.payment_amount;
                let payment_date = result.value.payment_date;
                let description = result.value.description;
                payment_date = payment_date.split('/').reverse().join('-');
                let customer_id = {{ $customer['customer_id'] }};
                let data = {
                    _token: CSRF_TOKEN,
                    customer_id,
                    amount: payment_amount,
                    date_of_payment: payment_date,
                    description:description
                };

                if(!edit) {
                    let order_id = result.value.order_id;
                    data['order_id'] = order_id;
                }
                console.log(data);
                let formdata = new FormData();
                console.log(data);
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
        desc = d.description;
        addEditPaymentModal(true, amount, dop,desc ,id);
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

    var table = $('#customer_orders_table');

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
            "data": {
                "customer_id": {{ $customer['customer_id'] }}
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
                render: function(data, type, full, meta) {
                    const created_at = full.created_at;
                    let date_split = created_at.split(' ');                        
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
                    const orderId = full.order_id;
                    return `
                        <a target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-md" href="` + BASE_URL + `/orders/` + orderId + `/edit" title="Επεξεργασία Παραγγελίας">
                            <i class="la la-edit"></i>
                        </a>`;
                },
            },
        ],
    });

});
</script>
@endsection