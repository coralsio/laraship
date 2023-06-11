@php \Assets::add(asset(\Theme::url('css/pricingTable.min.css')))  @endphp
@isset($product)
    <div class="row">
        @foreach($product->activePlans as $plan)
            <div class="col-md-3 margin-b-30">
                <div class="price-box {{ $plan->recommended?'best-plan':'' }}">
                    <div class="price-header">
                        @if($plan->free_plan)
                            <h1>{{  \Payments::currency(0.00) }}</h1>
                        @else
                            <h1>{{  \Payments::currency($plan->price) }}
                                <span class="peroid">{!! $plan->cycle_caption  !!}</span></h1>
                        @endif
                        <h4>{{ $plan->name }}</h4>
                    </div>
                    <ul class="list-unstyled price-features">
                        @foreach($product->activeFeatures as $feature)
                            @if($plan_feature = $plan->features()->where('feature_id',$feature->id)->first())
                                <li>
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
                                </li>
                            @else
                                <li>
                                    <i class="fa fa-times"></i>
                                    {{ $feature->caption }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="price-footer">
                        @if(user() && user()->subscribed(null, $plan->id))
                            <a href="#"
                               class="btn btn-rounded {{ $plan->recommended?'btn-white-border':'btn-dark-border' }}">
                                @lang('corals-admin::labels.pricing.current_package')
                            </a>
                            <br/>
                            {{ user()->currentSubscription(null, $plan->id)->ends_at?('ends at: '.format_date_time(user()->currentSubscription(null, $plan->id)->ends_at)):'' }}
                        @else
                            <a class="btn btn-rounded {{ $plan->recommended?'btn-white-border':'btn-dark-border' }}"
                               href="{{ url('subscriptions/checkout/'.$plan->hashed_id) }}">
                                @lang('corals-admin::labels.pricing.subscribe_now')
                            </a>
                        @endif
                    </div>
                </div>
            </div><!--/col-->
        @endforeach
    </div>
@else
    <p class="text-center text-danger"><strong>@lang('corals-admin::labels.pricing.product_can_not_found')</strong></p>
@endisset
