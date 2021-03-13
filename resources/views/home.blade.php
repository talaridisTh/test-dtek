@extends('layouts.app')

@section('content')
							<!-- begin:: Content -->
							<div class="kt-content kt-grid__item kt-grid__item--fluid">

								<!--Begin::Dashboard 4-->

								<!--Begin::Section-->
								<div class="row">
									<div class="col-xl-6">

										<!--begin:: Widgets/Quick Stats-->
										<div class="row row-full-height">
											<div class="col-sm-12 col-md-12 col-lg-6">
												<div class="kt-portlet kt-portlet--height-fluid-half kt-portlet--border-bottom-brand">
													<div class="kt-portlet__body kt-portlet__body--fluid">
														<div class="kt-widget26">
															<div class="kt-widget26__content">
																<span class="kt-widget26__number">{{ $data['stats']['count_orders'] }}</span>
																<span class="kt-widget26__desc">Συνολικές Παραγγελίες</span>
															</div>
															<div class="kt-widget26__chart" style="height:100px; width: 230px;">
																<canvas id="kt_chart_quick_stats_1_custom"></canvas>
															</div>
														</div>
													</div>
												</div>
												<div class="kt-space-20"></div>
												<div class="kt-portlet kt-portlet--height-fluid-half kt-portlet--border-bottom-danger">
													<div class="kt-portlet__body kt-portlet__body--fluid">
														<div class="kt-widget26">
															<div class="kt-widget26__content">
																<span class="kt-widget26__number">{{ $data['stats']['count_orders'] }}</span>
																<span class="kt-widget26__desc">Ολοκληρωμένες Παραγγελίες</span>
															</div>
															<div class="kt-widget26__chart" style="height:100px; width: 230px;">
																<canvas id="kt_chart_quick_stats_2_custom"></canvas>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-6">
												<div class="kt-portlet kt-portlet--height-fluid-half kt-portlet--border-bottom-success">
													<div class="kt-portlet__body kt-portlet__body--fluid">
														<div class="kt-widget26">
															<div class="kt-widget26__content">
																<span class="kt-widget26__number">{{ $data['stats']['count_customers'] }}</span>
																<span class="kt-widget26__desc">Πελάτες</span>
															</div>
															<div class="kt-widget26__chart" style="height:100px; width: 230px;">
																<canvas id="kt_chart_quick_stats_3_custom"></canvas>
															</div>
														</div>
													</div>
												</div>
												<div class="kt-space-20"></div>
												<div class="kt-portlet kt-portlet--height-fluid-half kt-portlet--border-bottom-warning">
													<div class="kt-portlet__body kt-portlet__body--fluid">
														<div class="kt-widget26">
															<div class="kt-widget26__content">
																<span class="kt-widget26__number">{{ $data['stats']['total_orders'] }}€</span>
																<span class="kt-widget26__desc">Συνολικός Τζίρος</span>
															</div>
															<div class="kt-widget26__chart" style="height:100px; width: 230px;">
																<canvas id="kt_chart_quick_stats_4_custom"></canvas>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<!--end:: Widgets/Quick Stats-->
									</div>
									<div class="col-xl-6">

										<!--begin:: Widgets/Order Statistics-->
										<div class="kt-portlet kt-portlet--height-fluid">
											<div class="kt-portlet__head">
												<div class="kt-portlet__head-label">
													<h3 class="kt-portlet__head-title">
														Στοιχεία Μήνα
													</h3>
												</div>
												<div class="kt-portlet__head-toolbar">
													<a href="#" class="btn btn-label-brand btn-bold btn-sm dropdown-toggle" data-toggle="dropdown">
														Export
													</a>
													<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
														<ul class="kt-nav">
															<li class="kt-nav__section kt-nav__section--first">
																<span class="kt-nav__section-text">Choose an action:</span>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-graph-1"></i>
																	<span class="kt-nav__link-text">Export</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-calendar-4"></i>
																	<span class="kt-nav__link-text">Save</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-layers-1"></i>
																	<span class="kt-nav__link-text">Import</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-calendar-4"></i>
																	<span class="kt-nav__link-text">Update</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-file-1"></i>
																	<span class="kt-nav__link-text">Customize</span>
																</a>
															</li>
														</ul>
													</div>
												</div>
											</div>
											<div class="kt-portlet__body kt-portlet__body--fluid">
												<div class="kt-widget12">
													<div class="kt-widget12__content">
														<div class="kt-widget12__item">
															<div class="kt-widget12__info">
																<span class="kt-widget12__desc">Τζίρος</span>
																<span class="kt-widget12__value">{{ $data['stats']['order_stats_totals'] }} €</span>
															</div>
															<div class="kt-widget12__info">
																<span class="kt-widget12__desc">Ημερομηνία</span>
																<span class="kt-widget12__value">{{ date('d-m-Y') }}</span>
															</div>
														</div>
													</div>
													<div class="kt-widget12__chart" style="height:250px;">
														<canvas id="kt_chart_order_statistics_custom"></canvas>
													</div>
												</div>
											</div>
										</div>

										<!--end:: Widgets/Order Statistics-->
									</div>
								</div>

								<!--End::Section-->

								<!--End::Dashboard 4-->
							</div>

							<!-- end:: Content -->
						</div>
                    </div>
                    
				</div>
			</div>
		</div>

		<!-- end:: Page -->
@endsection

@section('custom_script')
<script>
	$( document ).ready(function() {
		var container = KTUtil.getByID('kt_chart_order_statistics_custom');

	if (!container) {
		return;
	}

	var MONTHS = [
		@foreach ($data['stats']['order_stats'] as $order)
			'{{ $order->created_at }}',
		@endforeach
		];

	var color = Chart.helpers.color;
	var barChartData = {
		labels: [@foreach ($data['stats']['order_stats'] as $order)
			'{{ $order->created_at }}',
		@endforeach],
		datasets : [
			{
				fill: true,
				//borderWidth: 0,
				backgroundColor: color(KTApp.getStateColor('brand')).alpha(0.6).rgbString(),
				borderColor : color(KTApp.getStateColor('brand')).alpha(0).rgbString(),
				
				pointHoverRadius: 4,
				pointHoverBorderWidth: 12,
				pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
				pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
				pointHoverBackgroundColor: KTApp.getStateColor('brand'),
				pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),

				data: [@foreach ($data['stats']['order_stats'] as $order)
			'{{ $order->total }}',
		@endforeach,]
			}
		]
	};

	var ctx = container.getContext('2d');
	var chart = new Chart(ctx, {
		type: 'line',
		data: barChartData,
		options: {
			responsive: true,
			maintainAspectRatio: false,
			legend: false,
			scales: {
				xAxes: [{
					categoryPercentage: 0.35,
					barPercentage: 0.70,
					display: true,
					scaleLabel: {
						display: false,
						labelString: 'Month'
					},
					gridLines: false,
					ticks: {
						display: true,
						beginAtZero: true,
						fontColor: KTApp.getBaseColor('shape', 3),
						fontSize: 13,
						padding: 10
					}
				}],
				yAxes: [{
					categoryPercentage: 0.35,
					barPercentage: 0.70,
					display: true,
					scaleLabel: {
						display: false,
						labelString: 'Value'
					},
					gridLines: {
						color: KTApp.getBaseColor('shape', 2),
						drawBorder: false,
						offsetGridLines: false,
						drawTicks: false,
						borderDash: [3, 4],
						zeroLineWidth: 1,
						zeroLineColor: KTApp.getBaseColor('shape', 2),
						zeroLineBorderDash: [3, 4]
					},
					ticks: {
						max: {{ $data['stats']['order_stats_max'] }},                            
						stepSize: {{ $data['stats']['order_stats_step'] }},
						display: true,
						beginAtZero: true,
						fontColor: KTApp.getBaseColor('shape', 3),
						fontSize: 13,
						padding: 10
					}
				}]
			},
			title: {
				display: false
			},
			hover: {
				mode: 'index'
			},
			tooltips: {
				enabled: true,
				intersect: false,
				mode: 'nearest',
				bodySpacing: 5,
				yPadding: 10,
				xPadding: 10, 
				caretPadding: 0,
				displayColors: false,
				backgroundColor: KTApp.getStateColor('brand'),
				titleFontColor: '#ffffff', 
				cornerRadius: 4,
				footerSpacing: 0,
				titleSpacing: 0
			},
			layout: {
				padding: {
					left: 0,
					right: 0,
					top: 5,
					bottom: 5
				}
			}
		}
});
var quickStats = function() {
        _initSparklineChart($('#kt_chart_quick_stats_1_custom'), [10, 14, 18, 11, 9, 12, 14, 17, 18, 14], KTApp.getStateColor('brand'), 3);
        _initSparklineChart($('#kt_chart_quick_stats_2_custom'), [11, 12, 18, 13, 11, 12, 15, 13, 19, 15], KTApp.getStateColor('danger'), 3);
        _initSparklineChart($('#kt_chart_quick_stats_3_custom'), [12, 12, 18, 11, 15, 12, 13, 16, 11, 18], KTApp.getStateColor('success'), 3);
        _initSparklineChart($('#kt_chart_quick_stats_4_custom'), [11, 9, 13, 18, 13, 15, 14, 13, 18, 15], KTApp.getStateColor('success'), 3);
    }
	});
</script>
@endsection
