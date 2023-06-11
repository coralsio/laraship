<div class="row model-activity-modal">
    <div class="col-md-12">
        @forelse($activities as $activity)
            @include('Activity::activities.partials.activity_record',['activity'=>$activity])
        @empty
            <div>
                <div class="">No Records Found!!</div>
            </div>
        @endforelse
    </div>
</div>
