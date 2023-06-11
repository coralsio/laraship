<div class="widget">
    <form role="form" action="{{ url('blog') }}">
        <input type="text" name='query' class="form-control search_box" autocomplete="off"
               placeholder="Search Here">
    </form>
</div><!--/.search-->

<div class="widget">
    <h3><i class="fa fa-folder-open"></i>@lang('corals-basic::labels.partial.categories')</h3>
    <div class="row">
        <div class="col-sm-6">
            <ul class="blog_category">
                @foreach(\CMS::getCategoriesList(true, 'active') as $category)
                    <li><a href="{{ url('category/'.$category->slug) }}">{{ $category->name }} <span
                                    class="badge">{{ \CMS::getCategoryPostsCount($category) }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div><!--/.categories-->

<div class="widget">
    <h3><i class="fa fa-tags"></i> @lang('corals-basic::labels.partial.tag_cloud')</h3>
    <ul class="tag-cloud">
        @foreach(\CMS::getTagsList(true, 'active') as $tag)
            <li><a class="btn btn-xs btn-primary"
                   href="{{ url('tag/'.$tag->slug) }}">{{ $tag->name }}</a></li>
        @endforeach
    </ul>
</div><!--/.tags-->