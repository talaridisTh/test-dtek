
@if (isset($data) && isset($data['notifications']))
    <div id="kt_quick_panel" class="kt-quick-panel">
        <a href="#" class="kt-quick-panel__close" id="kt_quick_panel_close_btn"><i class="flaticon2-delete"></i></a>
        <div class="kt-quick-panel__nav">
            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand  kt-notification-item-padding-x" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" data-toggle="tab" href="#kt_quick_panel_tab_notifications" role="tab">Ειδοποιήσεις</a>
                </li>
            </ul>
        </div>
        <div class="kt-quick-panel__content">
            <div class="tab-content">
                <div class="tab-pane fade show kt-scroll active" id="kt_quick_panel_tab_notifications" role="tabpanel">
                    <div class="kt-notification">
                    @foreach ($data['notifications']['orders'] as $order)
                        <a href="{{ route('orders.edit', ['order' => $order->order_id ]) }}" class="kt-notification__item">
                                <div class="kt-notification__item-icon">
                                    <i class="flaticon2-line-chart kt-font-success"></i>
                                </div>
                                <div class="kt-notification__item-details">
                                    <div class="kt-notification__item-title">
                                    Παραγγελία #{{ $order->order_id }}
                                    </div>
                                    <div class="kt-notification__item-time">
                                        {{ $order->created_at }}
                                    </div>
                                </div>
                            </a>
                    @endforeach
                    @foreach ($data['notifications']['future_quantities'] as $quantity)
                        <a href="{{ route('products.show', ['product' => $quantity->product_id ]) }}" class="kt-notification__item">
                                <div class="kt-notification__item-icon">
                                    <i class="flaticon2-line-chart kt-font-success"></i>
                                </div>
                                <div class="kt-notification__item-details">
                                    <div class="kt-notification__item-title">
                                    Μελοντική Ποσότητα Προϊόντος #{{ $quantity->product_id }}
                                    </div>
                                    <div class="kt-notification__item-time">
                                        Σήμερα
                                    </div>
                                </div>
                            </a>
                    @endforeach
                    @foreach ($data['notifications']['low_Stock'] as $stock)
                        <a href="{{ route('products.show', ['product' => $stock->id ]) }}" class="kt-notification__item">
                                <div class="kt-notification__item-icon">
                                    <i class="flaticon2-line-chart kt-font-success"></i>
                                </div>
                                <div class="kt-notification__item-details">
                                    <div class="kt-notification__item-title">
                                    Χαμηλή Ποσότητα Προϊόντος #{{ $stock->id }}
                                    </div>
                                    <div class="kt-notification__item-time">
                                        Κάτω από {{ $stock->notify_quantity }}
                                    </div>
                                </div>
                            </a>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
