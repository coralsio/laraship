@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('notification') }}
        @endslot
    @endcomponent
@endsection

@section('js')
    @parent
    <script type="text/javascript">
        $("#global-modal").on('hide.bs.modal', function () {
            refreshDataTable("#NotificationDataTable");
        });
    </script>
@endsection