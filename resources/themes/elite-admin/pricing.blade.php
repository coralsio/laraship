@php \Assets::add(asset(\Theme::url('css/pricingTable.min.css')))  @endphp
@isset($product)
    <div class="row pricing-plan  justify-content-center">
        @foreach($product->activePlans as $plan)
            <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                <div class="pricing-box {{ $plan->recommended?'featured-plan':'' }}">
                    <div class="pricing-body b-l">
                        <div class="pricing-header">
                            @if($plan->recommended)
                                <h4 class="price-lable text-white bg-warning">
                                    @lang('corals-elite-admin::labels.pricing.recommended')
                                </h4>
                            @endif
                            <h4 class="text-center">{{ $plan->name }}</h4>
                            <h2 class="text-center">
                                <span class="price-sign">{{ \Payments::session_currency() }}</span>
                                @if($plan->free_plan)
                                    0.00
                                @else
                                    {{  \Payments::currency($plan->price, false) }}
                                @endif
                            </h2>
                            <p class="uppercase">{!! $plan->cycle_caption  !!}</p>
                        </div>
                        <div class="price-table-content">
                            @foreach($product->activeFeatures as $feature)
                                <div class="price-row">
                                    @if($plan_feature = $plan->features()->where('feature_id',$feature->id)->first())
                                        @if(!empty($plan_feature->pivot->plan_caption))
                                            {{ $plan_feature->pivot->plan_caption }}
                                        @else
                                            @if($feature->type=="boolean")
                                                @if($plan_feature->pivot->value)
                                                    <i class="fa fa-check"></i>
                                                @endif
                                            @else
                                                {{$plan_feature->pivot->value }} {{$feature->unit }}
                                            @endif
                                            {{ $feature->caption }}
                                        @endif
                                    @else
                                        <i class="fa fa-times"></i>
                                    @endif
                                    {{ $feature->caption }}
                                </div>
                            @endforeach
                            <div class="price-row">
                                @if(user() && user()->subscribed(null, $plan->id))
                                    @if($ends_at = user()->currentSubscription(null, $plan->id)->ends_at)
                                        <span class="text-danger" style="display: block;">
                                            @lang('corals-elite-admin::labels.pricing.ends_at',['ends_at'=>format_date_time($ends_at)])
                                        </span>
                                    @endif
                                    <a href="#" class="btn btn-info waves-effect waves-light m-t-20">
                                        @lang('corals-elite-admin::labels.pricing.current_package')
                                    </a>
                                @else
                                    <a class="btn {{ $plan->recommended?'btn-lg btn-info':'btn-success' }} waves-effect waves-light m-t-20"
                                       href="{{ url('subscriptions/checkout/'.$plan->hashed_id) }}">
                                        @lang('corals-elite-admin::labels.pricing.subscribe_now')
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/col-->
        @endforeach
    </div>
@else
    <p class="text-center text-danger">
        <strong>@lang('corals-elite-admin::labels.pricing.product_can_not_found')</strong></p>
@endisset
