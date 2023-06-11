<script type="text/javascript">
    google.charts.setOnLoadCallback(drawPieChart)
    var {{ $model->id }};
    function drawPieChart() {
        var data = google.visualization.arrayToDataTable([
            ['Element', 'Value'],
            @for($i = 0; $i < count($model->values); $i++)
                ["{!! $model->labels[$i] !!}", {{ $model->values[$i] }}],
            @endfor
        ])

        var options = {
            @include('charts::_partials.dimension.js')
            fontSize: 12,
            @include('charts::google.titles')
            @include('charts::google.colors')
        };

        {{ $model->id }} = new google.visualization.PieChart(document.getElementById("{{ $model->id }}"))
        {{ $model->id }}.draw(data, options)
    }
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
