@if(!empty($actions))
    <div class="btn-group">
        <button type="button" class="{{ $class }} dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            {!! $label !!}
        </button>
        <div class="dropdown-menu">
            @foreach($actions as $action)
                <a target="{{ $action['target']??'_self' }}" href="{{ $action['href'] }}" class="dropdown-item"
                @foreach($action['data']??[] as $key=>$data) {{ 'data-'.$key.'='.$data.' ' }} @endforeach >
                    <i class="{{ $action['icon'] }}"></i> {!! $action['label'] !!}
                </a>
            @endforeach
        </div>
    </div>
@endif
