@extends('layouts.app')

@section('custom_css')
@endsection

@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
    <div class="row mb-4">
        <div class="col-xl-12">
            <a href="{{ route('taxclasses.edit', $taxclass['tax_class_id']) }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                <i class="la la-edit"></i> Επεξεργασία Φορολογικού Συντελεστή
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Widgets/Invoice Details-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Λεπτομέρειες Φορολογικού Συντελεστή
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="row">
                                <div class="kt-widget12__item col-lg-4 col-md-4 col-sm-12">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Όνομα</span>
                                        <span class="kt-widget12__value">{!! !empty($taxclass['name']) ? $taxclass['name'] : '-' !!}</span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-4 col-sm-12">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Τύπος</span>
                                        <span class="kt-widget12__value">
                                            @if($taxclass['type'] == 0) 
                                                Ποσοστό
                                            @elseif($taxclass['type'] == 1)
                                                Σταθερό
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="kt-widget12__item col-lg-4 col-md-4 col-sm-12">
                                    <div class="kt-widget12__info">
                                        <span class="kt-widget12__desc">Τιμή</span>
                                        <span class="kt-widget12__value">{{ $taxclass['amount'] }} &euro;</span>
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
@endsection