@extends('layouts.master')

@section('editable_content')
    @include('partials.page_header')
    
    <div class="container">
        <div class="row">
            <aside class="col-md-4">
                @include('partials.sidebar')
            </aside>
            <div class="col-md-8">
                @if($featured_image)
                    <section style="padding-bottom: 0;">
                        <div class="container">
                            <div class="text-center wow fadeIn">
                                <img src="{{ $featured_image }}" alt="{{ $item->title }}" width="100%"/>
                            </div>
                        </div>
                    </section>
                @endif
                {!! $item->rendered !!}
            </div>
        </div>
    </div>
@stop