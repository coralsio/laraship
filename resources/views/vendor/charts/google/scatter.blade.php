<script type="text/javascript">
    chart = google.charts.setOnLoadCallback(drawChart)
    var {{ $model->id }};
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            [
                'Element', "{!! $model->element_label !!}"],
                @for ($i = 0; $i < count($model->values); $i++)
                    ["{!! $model->labels[$i] !!}", {{ $model->values[$i] }}],
                @endfor
        ])

        var options = {
            @include('charts::_partials.dimension.js')
            fontSize: 12,
            @include('charts::google.titles')
            @include('charts::google.colors')
            @if(!$model->legend)
            legend: null
            @else
            legend: { position: 'top', alignment: 'end' }
            @endif
        };

        {{ $model->id }} = new google.visualization.ScatterChart(document.getElementById("{{ $model->id }}"))

        {{ $model->id }}.draw(data, options)
    }
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
