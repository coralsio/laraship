@if(!empty($actions))
    <div class="item-actions float-right">
        <div class="btn-group">
            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                <i class="fa fa-ellipsis-v" style="font-size: 1.2em;"></i>
            </button>
            <div class="dropdown-menu">
                @foreach($actions as $action)
                    @php
                        $dataAttribute = [];
                            foreach($action['data']??[] as $key=>$data){
                            $dataAttribute['data-'.$key]=$data;
                            }
                    @endphp
                    <a target="{{ $action['target']??'_self' }}" href="{{ $action['href'] }}"
                       class="dropdown-item" {!! \Html::attributes($dataAttribute) !!} >
                        <i class="{{ $action['icon']?? '' }}"></i> {!! $action['label'] !!}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
