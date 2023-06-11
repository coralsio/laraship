<!-- Default box -->
<div class="block block-rounded">
    <div class="block-header with-border {{ empty($box_title) && empty($box_actions)?'hidden':'' }}">
        <h3 class="block-title {{ !empty($box_title) || !empty($box_actions)?'':'hidden' }}">{{ $box_title ?? '' }}</h3>

        <div class="box-tools pull-right">
            {{ $box_actions ?? '' }}
        </div>
    </div>
    <div class="block-content">
        {{ $slot }}
    </div>
    <!-- /.box-body -->
    <div class="box-footer {{ !empty($box_footer)?'':'hidden' }}">{{ $box_footer ?? '' }}</div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->