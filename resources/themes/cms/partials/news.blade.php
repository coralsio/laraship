@section('css')
    {!! Theme::css('plugins/news/news-ticker.css') !!}
    <style type="text/css">
        #news {
            position: fixed;
            z-index: 9999;
            bottom: 0px;
            left: 0px;
            border: solid 1px #000000;
        }

        .bn-label {
            background-color: #000000;
        }

        .bn-news ul li a {
            display: flex;
            align-items: center;
        }

        .bn-seperator {
            width: 66px;
        }

        ul {
            margin-left: 0px;
        }

        @media (min-width: 1900px) {
            .bn-label {
                width: 320px;
            }
        }
    </style>
@endsection
@php
    $news = \Corals\Modules\CMS\Models\News::all();
@endphp
@if(\Settings::get('enable_news_ticker',true) == true && !\Settings::get('feed_url_rss') && count($news) > 0)
    <div class="breaking-news-ticker" id="news">
        <div class="bn-label">@lang('corals-basic::labels.template.news')</div>
        <div class="bn-news">
            <ul>
                @foreach(\CMS::getLatestNews(\Settings::get('number_of_news_item_show',4)) as $news)
                    <li>
                        <a href="{{ url($news->slug) }}">
                        <span class="bn-seperator"
                              style="background-image: url({{ \Settings::get('site_logo') }}); height: 38px;"></span>
                            {{$news->title}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="bn-controls">
            <button><span class="bn-arrow bn-prev"></span></button>
            <button><span class="bn-action"></span></button>
            <button><span class="bn-arrow bn-next"></span></button>
        </div>
    </div>
@elseif(\Settings::get('enable_news_ticker',true) == true &&  (\Settings::get('feed_url_rss')))
    <div class="breaking-news-ticker" id="news">
        <div class="bn-news">

            <ul>
                <li><span class="bn-loader-text">@lang('corals-basic::labels.template.loading')</span></li>
            </ul>

        </div>
        <div class="bn-controls">
            <button><span class="bn-arrow bn-prev"></span></button>
            <button><span class="bn-action"></span></button>
            <button><span class="bn-arrow bn-next"></span></button>
        </div>
    </div>
@endif
@section('js')
    @parent
    {!! Theme::js('plugins/news/news-ticker.min.js') !!}
    <script type="">
        @if((\Settings::get('feed_url_rss')))
        $('#news').breakingNews({
            source: {
                type: 'rss',
                usingApi: 'rss2json',
                rss2jsonApiKey: '{{\Settings::get('rss_to_json_Api_Key')}}',
                url: '{{\Settings::get('feed_url_rss')}}',
                limit: '{{\Settings::get('number_of_news_item_show',4)}}',
                showingField: 'title',
                linkEnabled: true,
                target: '_blank',
                seperator: '<span class="bn-seperator" style="background-image:url({{ \Settings::get('site_logo') }});"></span>',
                errorMsg: '@lang('corals-basic::labels.template.rss_feed_not_load')'
            }
        });
        @else
        $('#news').breakingNews();
        @endif
    </script>
@endsection
