@include('charts::_partials.container.chartist')

<script type="text/javascript">
    var data = {
        labels: [
            @foreach($model->labels as $label)
                "{!! $label !!}",
            @endforeach
        ],
        series: [
            @foreach($model->values as $value)
                "{{ $value }}",
            @endforeach
        ]
    };

    var options = {
        chartPadding: 20,
        labelDirection: 'explode',
        @include('charts::_partials.dimension.js')
    };

    var {{ $model->id }} = new Chartist.Pie('#{{ $model->id }}', data, options);
</script>
