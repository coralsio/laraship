@extends('layouts.master')

@section('editable_content')
    <div id="blog" class="container">
        <div class="blog">
            <div class="row">
                <div class="{{ $blog->template != 'full'?'col-md-8':'col-md-12' }} {{ $blog->template =='left'?'col-md-push-4':'' }}">
                    @if($featured_image)
                        <div class="text-center wow fadeIn" style="margin-bottom: 10px;">
                            <img src="{{ $featured_image }}" alt="{{ $item->title }}" width="100%"/>
                        </div>
                    @endif
                    <div class="blog-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-2 text-center">
                                <div class="entry-meta">
                                    <span id="publish_date">{{ format_date($item->published_at) }}</span>
                                    <span><i class="fa fa-user"></i> {{ $item->author->full_name }}</span>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-10 blog-content">
                                <h2>{{ $item->title }}</h2>
                                <div>
                                    {!! $item->rendered !!}
                                </div>
                                @foreach($item->post->activeCategories as $category)
                                    <a href="{{ url('category/'.$category->slug) }}"><span
                                                class="label label-success"><i
                                                    class="fa fa-folder-open"></i> {{ $category->name }} </span></a>
                                    &nbsp;
                                @endforeach
                                @foreach($item->post->activeTags as $tag)
                                    <a href="{{ url('tag/'.$tag->slug) }}"><span class="label label-primary"><i
                                                    class="fa fa-tag"></i> {{ $tag->name }} </span></a>
                                    &nbsp;
                                @endforeach
                            </div>
                        </div>
                    </div><!--/.blog-item-->
                </div>
                @if($blog->template != 'full')
                    <aside class="{{ $blog->template =='right'? 'col-md-4':'col-md-pull-8 col-md-4' }}">
                        @include('partials.blog_sidebar')
                    </aside>
                @endif
            </div>
        </div>
    </div>
@stop