@if(!isset($home) || !$home)
    <div class="{{ isset($featured_image)?'':'page_header' }}">
        @if(isset($featured_image))
            <section style="padding-bottom: 0;">
                <div class="container">
                    <div class="text-center wow fadeIn">
                        <img src="{{ $featured_image }}" alt="{{ $item->title }}" width="100%"
                             style="max-height: 400px;"/>
                    </div>
                </div>
            </section>
        @elseif(isset($content))
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="space-20"></div>
                        {!! $content !!}
                    </div>
                </div>
            </div>
        @else
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="space-20"></div>
                        <h4>{!! optional($item)->title  !!}</h4>
                        <hr style="width: 40%;text-align:left;margin-left:0"/>
                        <div class="space-10"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif