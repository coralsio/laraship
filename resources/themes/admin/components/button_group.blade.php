@if(!empty($actions))
    <div class="btn-group pull-right">
        <button type="button" class="{{ $class }} dropdown-toggle" data-toggle="dropdown"
                aria-expanded="false">{!! $label !!}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            @foreach($actions as $action)
                <li>
                    <a target="{{ $action['target']??'_self' }}" href="{{ $action['href'] }}"
                    @foreach($action['data']??[] as $key=>$data) {{ 'data-'.$key.'='.$data.' ' }} @endforeach >
                        <i class="{{ $action['icon'] }}"></i> {!! $action['label'] !!}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
