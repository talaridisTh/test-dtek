@extends('layouts.app')

@section('custom_css')
@endsection

@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid">
    <div class="row mb-4">
        <div class="col-xl-12">
            <a href="{{ route('warehouses.edit', $warehouse['warehouse_id']) }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                <i class="la la-edit"></i> Επεξεργασία Αποθήκης
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Warehouse Details-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Λεπτομέρειες Αποθήκης
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget12">
                        <div class="kt-widget12__content">
                            <div class="kt-widget12__item">
                                <div class="kt-widget12__info">
                                    <span class="kt-widget12__desc">Όνομα</span>
                                    <span class="kt-widget12__value">{!! !empty($warehouse['name']) ? $warehouse['name'] : '-' !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Warehouse Details-->
        </div>
    </div>
</div>
@endsection

@section('custom_script')
@endsection