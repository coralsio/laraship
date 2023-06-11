@extends('layouts.master')

@section('editable_content')
    <div id="blog" class="container">
        {!! $blog->rendered !!}
    </div>
    <section class="container">
        @isset($title)
            <div class="text-left">
                <p>{{ $title }}</p>
                <hr/>
            </div>
        @endisset
        <div class="blog">
            <div class="row">
                <div class="{{ $blog->template != 'full'?'col-md-8':'col-md-12' }} {{ $blog->template =='left'?'col-md-push-4':'' }}">
                    @forelse($posts as $post)
                        <div class="blog-item">
                            <div class="row">
                                <div class="col-xs-12 col-sm-2 text-center">
                                    <div class="entry-meta">
                                        <span id="publish_date">{{ format_date($post->published_at) }}</span>
                                        <span><i class="fa fa-user"></i> {{ $post->author->full_name }}</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-10 blog-content">
                                    @if($post->featured_image)
                                        <a href="{{ url($post->slug) }}">
                                            <img class="img-responsive img-fluid img-blog" src="{{ $post->featured_image }}"
                                                 width="100%" alt=""/>
                                        </a>
                                    @endif
                                    <h2><a href="{{ url($post->slug) }}">{{ $post->title }}</a></h2>
                                    <p>
                                        {{ \Str::limit(strip_tags($post->rendered ),250) }}
                                    </p>
                                    @foreach($post->activeCategories as $category)
                                        <a href="{{ url('category/'.$category->slug) }}"><span
                                                    class="label label-success"><i
                                                        class="fa fa-folder-open"></i> {{ $category->name }} </span></a>
                                        &nbsp;
                                    @endforeach
                                    @foreach($post->activeTags as $tag)
                                        <a href="{{ url('tag/'.$tag->slug) }}"><span class="label label-primary"><i
                                                        class="fa fa-tag"></i> {{ $tag->name }} </span></a>&nbsp;
                                    @endforeach
                                    <a class="btn btn-primary readmore pull-right btn-xs" href="{{ url($post->slug) }}">
                                        @lang('corals-basic::labels.blog.read_more')
                                    </a>
                                </div>
                            </div>
                        </div><!--/.blog-item-->
                    @empty
                        <div class="alert alert-warning">
                            <h4><i class="fa fa-warning"></i>@lang('corals-basic::labels.blog.no_posts_found')</h4>
                        </div>
                    @endforelse
                    {{ $posts->links('partials.paginator') }}
                </div><!--/.col-md-8-->
                @if(in_array($blog->template, ['right', 'left']))
                    <aside class="{{ $blog->template =='right'? 'col-md-4':'col-md-pull-8 col-md-4' }}">
                        @include('partials.blog_sidebar')
                    </aside>
                @endif
            </div><!--/.row-->
        </div>
    </section>
@endsection