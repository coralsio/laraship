@if(!empty($actions))
    <div class="item-actions">
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-sm btn-default dropdown-toggle" style="padding: 2px 8px 0 8px;"
                    data-toggle="dropdown"
                    aria-expanded="false"><i class="fa fa-ellipsis-v" style="font-size: 1.2em;"></i></button>
            <ul class="dropdown-menu" role="menu">
                @foreach($actions as $action)
                    <li>
                        @php
                            $dataAttribute = [];
                                foreach($action['data']??[] as $key=>$data){
                                $dataAttribute['data-'.$key]=$data;
                                }
                        @endphp
                        <a target="{{ $action['target']??'_self' }}" href="{{ $action['href'] }}"
                                {!! \Html::attributes($dataAttribute) !!} >
                            <i class="{{ $action['icon']?? '' }}"></i> {!! $action['label'] !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
