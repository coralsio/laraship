@extends('layouts.master')

@section('css')
    <style type="text/css">
        .m-t-30 {
            margin-top: 30px;
        }

        .p-t-50 {
            padding-top: 50px;
        }

        .p-b-40 {
            padding-bottom: 40px;
        }

    </style>
@endsection
@section('editable_content')
    @include('partials.page_header')
    @php \Actions::do_action('pre_content',$item, $home??null) @endphp
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="m-t-30 p-t-50 p-b-40">
                    <h2>{!! $item->title !!}</h2>
                    <br/>
                    {!! $item->rendered !!}
                </div>
            </div>
        </div>
    </div>
@stop