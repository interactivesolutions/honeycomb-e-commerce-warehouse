@extends('HCCoreUI::admin.layout')

@if ( isset( $config['title'] ) &&  ! empty($config['title']))
    @section('content-header',  $config['title'] )
@endif

@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua-active"><i class="fa fa-product-hunt" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-number"> {{ get_translation_name('label', app()->getLocale(), $config['summary']->good->translations->toArray()) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua-active"><i class="fa fa-bank" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-number"> {{ $config['summary']->warehouse->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua-active"><i class="fa fa-eur" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.total_sold') }}</span>
                            <span class="info-box-number"> {{ $config['goods_sold'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-4 col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-gray">
                        <div class="inner">
                            <h3>{{ $config['summary']->on_sale }}</h3>

                            <p>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.on_sale') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-gray">
                        <div class="inner">
                            <h3>{{ $config['summary']->reserved }}</h3>

                            <p>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.reserved') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-gray">
                        <div class="inner">
                            <h3>{{ $config['summary']->ready_for_shipment }}</h3>

                            <p>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.ready_for_shipment') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-gray">
                        <div class="inner">
                            <h3>{{ $config['summary']->total }}</h3>

                            <p>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.total') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-gray">
                        <div class="inner">
                            <h3>{{ $config['summary']->pre_ordered or '0' }}</h3>

                            <p>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.pre_ordered') }}</p>
                        </div>
                    </div>
                </div>


                @if($config['summary']->good->allow_pre_order)
                    <div class="col-sm-4 col-md-2">
                        <!-- small box -->
                        <div class="small-box bg-gray">
                            <div class="inner">
                                <h3>{{ $config['summary']->good->pre_order_count }}</h3>

                                <p>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.available_to_pre_order') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header no-border">
                    <h3 class="box-title">{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.page_title') }}</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_summary.time') }}</th>
                            <th>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.user_id') }}</th>
                            <th>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.amount') }}</th>
                            <th>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.action_id') }}</th>
                            <th>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.prime_cost') }}</th>
                            <th>{{ trans('HCECommerceWarehouse::e_commerce_warehouses_stock_history.comment') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($config['history']->isNotEmpty())

                            @foreach($config['history'] as $history)
                                <tr>
                                    <td style="width: 140px;vertical-align: middle;">{{ $history->created_at }}</td>
                                    <td>
                                        {{ $history->user->email or '-' }}
                                    </td>
                                    <td>
                                        {{ $history->amount }}
                                    </td>
                                    <td>
                                        {{ $history->action->title }}
                                    </td>
                                    <td>
                                        {{ $history->price_cost }}
                                    </td>
                                    <td>
                                        {{ $history->comment }}
                                    </td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection
