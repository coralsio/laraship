@include('charts::_partials.container.svg')

<script type="text/javascript">
    var {{ $model->id }};
    $(function() {
        @include('charts::minimalist._data.one-indcolor')

        var xScale = new Plottable.Scales.Category()
        var yScale = new Plottable.Scales.Linear()

        var xAxis = new Plottable.Axes.Category(xScale, 'bottom')
        var yAxis = new Plottable.Axes.Numeric(yScale, 'left')

        {{ $model->id }} = new Plottable.Plots.Pie()
            .addDataset(new Plottable.Dataset(data))
            .sectorValue(function(d) { return d.y; }, yScale)
            @if($model->colors)
                .attr('fill', function(d) { return d.color; })
            @endif
            .outerRadius(500, yScale)
            .renderTo('svg#{{ $model->id }}')

        window.addEventListener('resize', function() {
            {{ $model->id }}.redraw()
        })
    });
</script>
