<ul class="nav nav-pills">
    @foreach($pills as $pill)
        <li class="nav-item">
            <a href="{{ $pill['href'] }}" class="nav-link {{ $pill['active']??'' }}">
                {!! $pill['label'] !!}
            </a>
        </li>
    @endforeach
</ul>