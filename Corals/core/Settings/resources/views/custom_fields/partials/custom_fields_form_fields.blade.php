<div class="row">
    <div class="col-md-4">
        {!! CoralsForm::text("fields[$index][name]","Settings::attributes.custom_field.name",true,data_get($field,'name')) !!}

        {!! CoralsForm::select("fields[$index][type]", "Settings::attributes.custom_field.type", get_array_key_translation(config("settings.models.custom_field_setting.supported_types")), true,\Illuminate\Support\Arr::get($field,"type"),
        [!empty($field)?"readonly":'','class'=>'field_type','data-form_index'=>$index]) !!}

        {!! CoralsForm::radio("fields[$index][status]","Corals::attributes.status", true, trans("Corals::attributes.status_options")) !!}
        @if(isset($has_field_config) && !!$has_field_config)
            {!! CoralsForm::checkboxes("fields[$index][field_config][full_text_search][]",
          'Settings::attributes.custom_field.field_config.full_text_search',
          false,
          trans('Settings::attributes.custom_field.field_config.full_text_search_options'),
          data_get($field,'field_config.full_text_search', [])) !!}

            {!! CoralsForm::checkbox("fields[$index][field_config][is_identifier]", 'Settings::attributes.custom_field.field_config.is_identifier',data_get($field,'field_config.is_identifier'),null,['class'=>'is_identifier','data-form_index'=>$index]) !!}
        @endif
    </div>
    <div class="col-md-4">
        {!! CoralsForm::text("fields[$index][label]","Settings::attributes.custom_field.label", true, data_get($field,'label')) !!}
        {!! CoralsForm::text("fields[$index][default_value]","Settings::attributes.custom_field.default_value",false,data_get($field,'default_value')) !!}
        {!! CoralsForm::text("fields[$index][validation_rules]","Settings::attributes.custom_field.validation_rules",false,data_get($field,'validation_rules'),['help_text'=>'Settings::attributes.custom_field.validation_rules_help']) !!}
        @if(isset($has_field_config) && $has_field_config)
            <div class="row">
                <div class="col-md-4">
                    {!! CoralsForm::checkbox("fields[$index][field_config][searchable]", 'Settings::attributes.custom_field.field_config.searchable',data_get($field,'field_config.searchable') ) !!}
                </div>
                <div class="col-md-4">
                    {!! CoralsForm::checkbox("fields[$index][field_config][sortable]", 'Settings::attributes.custom_field.field_config.sortable',data_get($field,'field_config.sortable')) !!}
                </div>

                <div class="col-md-4">
                    {!! CoralsForm::checkbox("fields[$index][field_config][show_in_list]", 'Settings::attributes.custom_field.field_config.show_in_list',data_get($field,'field_config.show_in_list')) !!}
                </div>

            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                {!! CoralsForm::text("fields[$index][field_config][grid_class]","Settings::attributes.custom_field.field_config.grid_class",false,data_get($field,'field_config.grid_class')) !!}
            </div>

            <div class="col-md-6">
                {!! CoralsForm::number("fields[$index][field_config][order]","Settings::attributes.custom_field.field_config.order",false,data_get($field,'field_config.order')) !!}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <label>@lang("Settings::attributes.custom_field.attribute")</label>

        @include("Corals::key_value",[
        "label"=>["key"=> trans("Corals::labels.key"), "value"=>trans("Corals::labels.value")],
        "name"=>"fields[$index][custom_attributes]",
        "options"=>\Illuminate\Support\Arr::get($field,"custom_attributes",[])
        ])
        <div style="display: none;" id="options-field-{{$index}}">
            {!! CoralsForm::select("fields[$index][options_setting][source]", "Settings::attributes.custom_field.options_source", ["static"=>"Static","database"=>"Database"], true,\Illuminate\Support\Arr::get($field,"options_setting.source") ,[
            !empty($field)?"readonly":'','class'=>'source_options','data-form_index'=>$index
            ]) !!}
            <div class="form-group options-source-{{$index}} options-source-{{$index}}-database"
                 style="@if(data_get($field,'option_settings.source')!='database')display: none; @endif">
                {!! CoralsForm::select("fields[$index][options_setting][source_model]","Settings::attributes.custom_field.options_source_model", \Settings::getCustomFieldsModels(), true, \Illuminate\Support\Arr::get($field,"options_setting.source_model"), !empty($field)?["readonly"]:[], !empty($field)?"select":"select2") !!}
                {!! CoralsForm::text("fields[$index][options_setting][source_model_column]","Settings::attributes.custom_field.options_source_model_column",true,\Illuminate\Support\Arr::get($field,"options_setting.source_model_column"), !empty($field)?["readonly"]:[]) !!}

            </div>
            <div class="form-group options-source-{{$index}} options-source-{{$index}}-static" style="display: none;">
                <span data-name="options"></span>
                {!! CoralsForm::label("fields[$index][options]", "Settings::attributes.custom_field.options") !!}
                @include("Corals::key_value",[
                "label"=>["key" => trans("Corals::labels.key"), "value" => trans("Corals::labels.value")],
                "name"=>"fields[$index][options]",
                "options"=>\Illuminate\Support\Arr::get($field,"options",[])
                ])
            </div>
        </div>
    </div>
</div>
