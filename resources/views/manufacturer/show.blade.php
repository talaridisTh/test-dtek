@extends('layouts.app')

@section('custom_css')
@endsection

@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
    <div class="row mb-4">
        <div class="col-xl-12">
            <a href="{{ route('manufacturers.edit', $manufacturer['manufacturer_id']) }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                <i class="la la-edit"></i> Επεξεργασία Κατασκευαστή
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
                            Λεπτομέρειες Κατασκευαστή
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Όνομα</span>
                                    <span class="kt-widget12__value">{!! !empty($manufacturer['name']) ? $manufacturer['name'] : '-' !!}</span>
                                </div>
                            </div>
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Εικόνα</span>
                                    @if(!empty($manufacturer['image']))
                                        <span class="kt-widget12__value">
                                            <img class="view-entity-image" src="{{url('/images/'.$manufacturer["image"])}}" alt={{$manufacturer['name']}} />
                                        </span>
                                    @else
                                        <span class="kt-widget12__value">-</span>
                                    @endif
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