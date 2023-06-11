<script type="text/javascript">
    var {{ $model->id }};
    $(function () {
        Highcharts.setOptions({global: { useUTC: false } })

        {{ $model->id }} = new Highcharts.Chart({
            chart: {
                renderTo:  "{{ $model->id }}",
                events: {
                    load: update{{ $model->id }}
                },
                @include('charts::_partials.dimension.js2')
            },
            @if($model->title)
                title: {
                    text:  "{!! $model->title !!}",
                    x: -20 //center
                },
            @endif
            @if(!$model->credits)
                credits: {
                    enabled: false
                },
            @endif
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: "{!! $model->element_label !!}"
                },
                plotLines: [{
                    value: 0,
                    height: 0.5,
                    width: 1,
                    color: '#808080'
                }]
            },
            @if($model->colors)
                plotOptions: {
                    series: {
                        color: "{{ $model->colors[0] }}"
                    }
                },
            @endif
            legend: {
                @if(!$model->legend)
                    enabled: false,
                @endif
            },
            series: [{
                name: "{!! $model->element_label !!}",
                data: [],
                pointStart: new Date().getTime(),
                pointInterval: {{ ($model->interval / 1000) * 1000 }} // one day
            }]
        })

        function update{{ $model->id }}() {
            $.ajax({
                url:  "{!! $model->url !!}",
                success: function(point) {
                    var series = {{ $model->id }}.series[0],
                        shift = series.data.length >= {{ $model->max_values }}; // shift if the series is longer than 20

                    // add the point
                    {{ $model->id }}.series[0].addPoint(point[ "{{ $model->value_name }}"], true, shift)
                    {{ $model->id }}.xAxis.categories

                    // call it again after one second
                    setTimeout(update{{ $model->id }}, {{ $model->interval }})
                },
                cache: false
            })
        }
    });
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
