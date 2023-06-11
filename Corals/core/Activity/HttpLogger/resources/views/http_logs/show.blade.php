@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('http_log_show', $title_singular) }}
        @endslot
    @endcomponent
@endsection
@section('css')
    <style>
        .pre-wrapper {
            overflow: auto;
        }
    </style>
@endsection
@section('content')
    @component('components.box',['box_class'=>'box-success'])
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Uri</th>
                            <th>Method</th>
                            <th>IP Address</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Created at</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $httpLog->presentStripTags('uri') }}</td>
                            <td>{{ $httpLog->present('method') }}</td>
                            <td>{{ $httpLog->present('ip') }}</td>
                            <td>{!! $httpLog->present('user_id') !!}</td>
                            <td>{!! $httpLog->present('email') !!}</td>
                            <td>{!! $httpLog->present('created_at') !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="d-block m-b-10 mb-2">
                    Headers
                    <a href="#" onclick="event.preventDefault();" class="copy-button"
                       data-clipboard-target="#shortcode_headers"><i
                                class="fa fa-clipboard"></i></a>
                </label>
                <div class="pre-wrapper">
                    <pre id="shortcode_headers">
                        {!! json_encode($httpLog->headers, JSON_PRETTY_PRINT)!!}
                    </pre>
                </div>
            </div>
            <div class="col-md-4">
                <div>
                    <label class="d-block m-b-10 mb-2">
                        Body
                        <a href="#" onclick="event.preventDefault();" class="copy-button"
                           data-clipboard-target="#shortcode_body"><i
                                    class="fa fa-clipboard"></i></a>
                    </label>
                    <div class="pre-wrapper">
                        <pre id="shortcode_body">
                            {!! json_encode($httpLog->body, JSON_PRETTY_PRINT)!!}
                        </pre>
                    </div>
                </div>
                <div>
                    <label class="d-block m-b-10 mb-2">
                        Files
                        <a href="#" onclick="event.preventDefault();" class="copy-button"
                           data-clipboard-target="#shortcode_files"><i
                                    class="fa fa-clipboard"></i></a>
                    </label>
                    <div class="pre-wrapper">
                        <pre id="shortcode_files">
                            {!! json_encode($httpLog->files, JSON_PRETTY_PRINT)!!}
                        </pre>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="d-block m-b-10 mb-2">Response
                    <a href="#" onclick="event.preventDefault();" class="copy-button"
                       data-clipboard-target="#shortcode_response"><i
                                class="fa fa-clipboard"></i></a>
                </label>
                <div class="pre-wrapper">
                    <pre id="shortcode_response">
                        {!! json_encode($httpLog->response, JSON_PRETTY_PRINT)!!}
                    </pre>
                </div>
            </div>
        </div>
    @endcomponent
@endsection

@section('js')
    <script>
        let elements = ['shortcode_headers', 'shortcode_body', 'shortcode_files', 'shortcode_response']

        elements.forEach(function (elementId) {
            $("#" + elementId).text(JSON.stringify(JSON.parse($("#" + elementId).text()), null, 3));
        })
    </script>
@endsection
