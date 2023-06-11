
{!! \Html::script('assets/corals/plugins/chartjs/Chart.min.js')  !!}
{!! \Html::script('assets/corals/plugins/chartjs/chartjs-plugin-colorschemes.js')  !!}


<div id="chart{{$chart->id }}">
    {!! $chart->container() !!}
</div>

{!! $chart->script() !!}
