<div class="activity-record text-sm">
    <div class="meta-details">
        <ul class="list-inline mb-0 m-b-0">
            <li class="list-inline-item">
                <i class="fa fa-info-circle fa-fw"
                   title="{!!  $activity->present('log_name') !!}"></i> {!!  ucwords($activity->description) !!}
            </li>
            <li class="list-inline-item">
                <i class="fa fa-clock-o fa-fw"></i> {!! $activity->present('created_at') !!}
            </li>
            <li class="list-inline-item">
                By: <i class="fa fa-user-o fa-fw"></i> {!!  $activity->present('causer_id') !!}
            </li>
        </ul>
    </div>
    <div class="body">
        <div class="row">
            @php
                $attributes = $activity->getProperties()['attributes']??[];
                $old = $activity->getProperties()['old']??[];
            @endphp
            <div class="{{ $old?'col-md-6':'col-md-12' }}">
                @if($attributes)
                    @if($old)
                        <span class="text-info">After</span>
                    @endif
                    <div class="properties-table">
                        {!! formatProperties($attributes, $activity->subject) !!}
                    </div>
                @endif
            </div>
            @if($old)
                <div class="col-md-6">
                    <span class="d-block text-primary text-right">Before</span>
                    <div class="properties-table">
                        {!! formatProperties($old, $activity->subject) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <hr/>
</div>
