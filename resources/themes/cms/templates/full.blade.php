@extends('layouts.master')

@section('editable_content')
    @include('partials.page_header')
    @php \Actions::do_action('pre_content',$item, $home??null) @endphp
    {!! $item->rendered !!}
    @include('partials.news')
@stop