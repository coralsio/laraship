<script type="text/javascript">
    google.charts.setOnLoadCallback(draw{{ $model->id }})
    var {{ $model->id }};
    function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            ['Element', 'Value'],
            @for ($l = 0; $l < count($model->values); $l++)
                ["{!! $model->labels[$l] !!}", {{ $model->values[$l] }}],
            @endfor
        ])

        var options = {
            @include('charts::_partials.dimension.js')
            fontSize: 12,
            pieHole: 0.4,
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
