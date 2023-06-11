@isset($product)
    <div class="container">
        <div class="row">
            @foreach($product->activePlans as $plan)
                <div class="vpt_plan-container col-md-3 no-margin {{ $plan->recommended?'featured':'' }}">
                    <ul class="vpt_plan drop-shadow {{ $plan->recommended ?'bootstrap-vtp-orange featured':'bootstrap-vpt-green' }} hover-animate-position">
                        <li class="vpt_plan-name"><strong>{{ $plan->name }}</strong></li>

                        @if($plan->free_plan)
                            <li class="vpt_plan-price"><b>FREE</b>
                                <span class="vpt_year">{{  \Payments::currency(0.00) }}</span>
                            </li>
                        @else
                            <li class="vpt_plan-price">
                                {{  \Payments::currency($plan->price) }}
                                <span class="vpt_year">{!! $plan->cycle_caption  !!}</span>
                            </li>
                        @endif

                        @if(user() && user()->subscribed(null, $plan->id))
                            <li class="vpt_plan-footer">
                                <a href="#" class="pricing-select">
                                    Current Package
                                    <br/>
                                    {{ user()->currentSubscription(null, $plan->id)->ends_at?('ends at: '.format_date_time(user()->currentSubscription(null, $plan->id)->ends_at)):'' }}
                                </a>
                            </li>
                        @else
                            <li class="vpt_plan-footer">
                                <a href="{{ url('subscriptions/checkout/'.$plan->hashed_id) }}" class="pricing-select">
                                    Subscribe Now
                                </a>
                            </li>
                        @endif

                        @foreach($product->activeFeatures as $feature)
                            @if($plan_feature = $plan->features()->where('feature_id',$feature->id)->first())
                                <li class="{{ $loop->index%2?'vptbg':'' }}">
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
                                <li class="{{ $loop->index%2?'vptbg':'' }}">
                                    <b><i class="fa fa-times"></i></b>
                                    {{ $feature->caption }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
@else
    <p class="text-center text-danger"><strong> Product cannot be found</strong></p>
@endisset
