<div class="custom-field-form" id="custom-field-form-{{$index}}">
    @if($index>=1)
        <button class="remove-custom-field btn btn-sm btn-danger pull-right" type="button"
                title="Remove custom-field"><i class="fa fa-remove"></i> Remove Field
        </button>
    @endif
    @include('Settings::custom_fields.partials.custom_fields_form_fields', compact('index','has_field_config'))
</div>
