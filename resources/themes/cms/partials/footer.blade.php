<div>
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="social">
                        <ul class="social-share">
                            @foreach(\Settings::get('social_links',[]) as $key=>$link)
                                <li><a href="{{ $link }}" target="_blank"><i class="fa fa-{{ $key }}"></i></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div><!--/.container-->
    </div>
    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    {!! \Settings::get('footer_text','') !!}
                </div>
                <!--
                    All links in the footer should remain intact.
                    Licenseing information is available at: http://bootstraptaste.com/license/
                    You can buy this theme without footer links online at: http://bootstraptaste.com/buy/?theme=Gp
                -->
                <div class="col-sm-6">
                    <ul class="pull-right">
                        @include('partials.menu.menu_item', ['menus' => Menus::getMenu('frontend_footer','active')])
                    </ul>
                </div>
            </div>
        </div>

    </footer>
</div>

