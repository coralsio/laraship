@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('settings_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-4">
            @component('components.box')
                {!! CoralsForm::openForm($setting) !!}
                {{ Form::hidden('type', $type) }}
                @include('Settings::settings.partials.shared_fields',['setting' => $setting])
                {!! CoralsForm::formButtons() !!}
                {!! CoralsForm::closeForm($setting) !!}
            @endcomponent
        </div>
    </div>
@endsection