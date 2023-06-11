<script type="text/javascript">
    var {{ $model->id }};
    $(function () {
        {{ $model->id }} = new Highcharts.Chart({
            chart: {
                renderTo: "{{ $model->id }}",
                type: 'column',
                @include('charts::_partials.dimension.js2')
            },
            @if($model->title)
                title: {
                    text:  "{!! $model->title !!}",
                },
            @endif
            @if(!$model->credits)
                credits: {
                    enabled: false
                },
            @endif
            xAxis: {
                title: {
                    text: "{{ $model->x_axis_title }}"
                },
                categories: [],
            },
            yAxis: {
                title: {
                    text: "{!! $model->y_axis_title === null ? $model->element_label : $model->y_axis_title !!}"
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
                    },
                },
            @endif
            legend: {
                @if(!$model->legend)
                    enabled: false,
                @endif
            },
            series: [],
            loading: {
                showDuration: 250,
                hideDuration: 250,
                labelStyle: { "position": "relative", "top": "45%", "font-family": "sans-serif" },
            },
            lang: {
                loading: "{!! $model->loading_text !!}"
            }
        });
        {{ $model->id }}.showLoading();
        $.ajax({
            url: "{!! $model->url !!}",
            type: "{{ $model->method }}",
            dataType: "json",
            data : {!! $model->data !!},
            success: function (data) {
                var {{ $model->id }}_data = data{{ $model->values_name ? '.' . $model->values_name : '' }};
                var {{ $model->id }}_colors = {!! json_encode($model->colors) !!};
                var {{ $model->id }}_datasets = Object.keys({{ $model->id }}_data);
                {{ $model->id }}.xAxis[0].setCategories(data.{{ $model->labels_name }});
                {{ $model->id }}.hideLoading();
                for (var i = 0; i < {{ $model->id }}_datasets.length; i++) {
                    {{ $model->id }}.addSeries({
                        name: {{ $model->id }}_datasets[i],
                        color: {{ $model->id }}_colors[i],
                        data: {{ $model->id }}_data[{{ $model->id }}_datasets[i]].map(parseFloat),
                    });
                }
            },
            cache: false
        });
    });
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
