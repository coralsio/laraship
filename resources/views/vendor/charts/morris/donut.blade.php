@include('charts::_partials/container.div-titled')

<script type="text/javascript">
    var {{ $model->id }};
    $(function (){
        {{ $model->id }} = Morris.Donut({
            element: "{{ $model->id }}",
            resize: true,
            data: [
                @for($i = 0; $i < count($model->values); $i++)
                    {
                        label: "{!! $model->labels[$i] !!}",
                        value: "{{ $model->values[$i] }}"
                    },
                @endfor
            ],
            @if($model->colors)
                colors: [
                    @foreach($model->colors as $c)
                        "{{ $c }}",
                    @endforeach
                ]
            @endif
        })
    });
</script>
