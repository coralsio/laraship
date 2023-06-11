<script type="text/javascript">
    var {{ $model->id }};
    $(function () {
        {{ $model->id }} = new Highcharts.Chart({
            chart: {
                renderTo: "{{ $model->id }}",
                @include('charts::_partials.dimension.js2')
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            tooltip: {
                pointFormat: '{point.y} <b>({point.percentage:.1f}%)</strong>'
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
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</strong>: {point.y} ({point.percentage:.1f}%)',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            @if($model->colors)
                colors: [
                    @foreach($model->colors as $c)
                        "{{ $c }}",
                    @endforeach
                ],
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
            success: function(data) {
                {{ $model->id }}.hideLoading();
                var {{ $model->id }}_values = data{{ $model->values_name ? '.' . $model->values_name : '' }};
                var {{ $model->id }}_labels = data.{{ $model->labels_name }};
                var {{ $model->id }}_new_values = [];
                for (var i = 0; {{ $model->id }}_labels.length > i; i++) {
                    {{ $model->id }}_new_values.push({
                        name: {{ $model->id }}_labels[i],
                        y: parseFloat({{ $model->id }}_values[i])
                    });
                }
                {{ $model->id }}.addSeries({
                    colorByPoint: true,
                    data: {{ $model->id }}_new_values,
                });
            },
            cache: false
        });
    });
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
