<div id="{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}_modalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header {{ isset($hideHeader)?'invisible':'' }}">
                <h4 class="modal-title" id="{{ $id }}_modalLabel">{!! $header ?? ''  !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="modal-body-{{ $id }}">
                {!! $slot??'' !!}
            </div>
            <div class="modal-footer {{ !empty($footer)?'':'hidden' }}">
                {!! $footer ?? '' !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->