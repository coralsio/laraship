@php $currentLocal = app()->getLocale() ; @endphp
<ul class="{{ $ul_class??'' }}">
    @foreach (\Language::allowed() as $code => $name)
        <li class="{{ $li_class??'' }} {{ $currentLocal== $code ? 'active' : ''}}">
            <a href="{{ \Language::getLocaleUrl($code) }}">
                {!! \Language::flag($code) !!} {!! $name !!}
            </a>
        </li>
    @endforeach
</ul>