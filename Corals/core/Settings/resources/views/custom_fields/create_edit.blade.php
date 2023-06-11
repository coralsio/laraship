@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('custom_field_settings_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! CoralsForm::openForm($customFieldSetting) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! CoralsForm::select('model','Settings::attributes.custom_field.model', \Settings::getCustomFieldsModels(), true, null, $customFieldSetting->exists?['readonly']:[], $customFieldSetting->exists?'select':'select2') !!}
                    </div>
                </div>
                <h4>Fields</h4>
                <hr/>
                @forelse( \CustomFields::getSortedFields($customFieldSetting) ?? [] as $index => $field)
                    @include('Settings::custom_fields.partials.custom_fields_form',['index'=>$index,'field' => $field,'has_field_config'=>false])
                @empty
                    @include('Settings::custom_fields.partials.custom_fields_form',['index'=>0,'field'=>[],'has_field_config'=>false])
                @endforelse

                @include('Settings::custom_fields.partials.new_custom_field_btn')

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($customFieldSetting) !!}
            @endcomponent
        </div>
    </div>
@endsection
