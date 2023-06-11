<ul class="nav nav-pills">
    @foreach($pills as $pill)
        <li class="{{ $pill['active']??'' }}"><a href="{{ $pill['href'] }}">{!! $pill['label'] !!}</a></li>
    @endforeach
</ul>