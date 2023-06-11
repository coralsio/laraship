@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('group_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-4">
            @component('components.box')
                {!! CoralsForm::openForm($group, ['files'=>true]) !!}
                {!! CoralsForm::text('name', 'User::attributes.group.name', true) !!}

                {!! CoralsForm::customFields($group) !!}

                {!! CoralsForm::formButtons() !!}

                {!! CoralsForm::closeForm($group) !!}
            @endcomponent
        </div>
    </div>
@endsection


