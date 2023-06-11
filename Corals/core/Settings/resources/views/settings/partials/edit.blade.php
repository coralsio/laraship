<div class="row">
    <div class="col-md-12">
        @component('components.box')
            {!! CoralsForm::openForm($setting, ['files'=>true,'data-page_action'=>"site_reload"]) !!}
            @include('Settings::settings.partials.shared_fields',['setting' => $setting])

            @include('Settings::settings.types_value.'.strtolower($setting->type))

            {!! CoralsForm::customFields($setting,'col-md-12') !!}

            {!! CoralsForm::formButtons('<i class="fa fa-save"></i> ' . $title_singular, [], ['show_cancel' => false])  !!}

            {!! CoralsForm::closeForm($setting) !!}
        @endcomponent
    </div>
</div>