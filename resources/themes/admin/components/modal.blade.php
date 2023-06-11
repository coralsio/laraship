<!-- Modal -->
<div id="{{ $id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header {{ isset($hideHeader)?'hidden':'' }}">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{!! $header ?? ''  !!}</h4>
            </div>

            <div class="modal-body" id="modal-body-{{ $id }}">
                {!! $slot??'' !!}
            </div>

            <div class="modal-footer {{ !empty($footer)?'':'hidden' }}">
                {!! $footer ?? '' !!}
            </div>
        </div>
    </div>
</div>