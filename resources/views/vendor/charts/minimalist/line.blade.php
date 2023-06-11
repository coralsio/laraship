@include('charts::_partials.container.svg')

<script type="text/javascript">
    var {{ $model->id }};
    $(function() {
        @include('charts::minimalist._data.one')

        var xScale = new Plottable.Scales.Category()
        var yScale = new Plottable.Scales.Linear()

        {{ $model->id }} = new Plottable.Plots.Line()
            .addDataset(new Plottable.Dataset(data))
            .x(function(d) { return d.x; }, xScale)
            .y(function(d) { return d.y; }, yScale)
            @if($model->colors)
                .attr('stroke', "{{ $model->colors[0] }}")
            @endif
            .renderTo('svg#{{ $model->id }}')

        window.addEventListener('resize', function() {
            {{ $model->id }}.redraw()
        })
    });
</script>

