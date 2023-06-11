<div class="activity-record">
    <div class="meta-details">
        <ul class="list-inline">
            <li class="list-inline-item">
                <i class="fa fa-user-o fa-fw"></i> {!!  $activity->present('causer_id') !!}
            </li>
            <li class="list-inline-item">
                <i class="fa fa-clock-o fa-fw"></i> {!!  $activity->present('created_at') !!}
            </li>
            <li class="list-inline-item">
                <i class="fa fa-info-circle fa-fw"></i> {!!  $activity->present('log_name') !!}
            </li>
        </ul>
        <p>Description: {!!  ucwords($activity->description) !!}</p>
    </div>
    <div class="body">
        @php
            $attributes = $activity->getProperties()['attributes']??[];
            $old = $activity->getProperties()['old']??[];
        @endphp
        @if($attributes)
            @if($old)
                <strong class="text-primary">New Values</strong>
            @endif
            <div class="properties-table">
                {!! formatProperties($attributes) !!}
            </div>
        @endif
        @if($old)
            <strong class="text-danger">Old Values</strong>
            <div class="properties-table">
                {!! formatProperties($old) !!}
            </div>
        @endif
    </div>
    <hr/>
</div>
