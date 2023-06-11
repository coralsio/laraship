<!-- Default box -->
<div class="card {{ $box_class??'' }}">
    <div class="card-header with-border" style="{{ empty($box_title) && empty($box_actions)?'display:none;':'' }}">
        <div class="float-left">
            {{ $box_title ?? '' }}
        </div>
        <div class="card-tools float-right">
            {{ $box_actions ?? '' }}
        </div>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
    <!-- /.box-body -->
    <div class="card-footer" style="{{ empty($box_footer)?'display:none;':'' }}">
        {{ $box_footer ?? '' }}
    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->