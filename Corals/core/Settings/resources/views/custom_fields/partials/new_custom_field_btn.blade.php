<div id="new-custom-field-button">
    <div class="form-group">
        <button type="button" class="btn btn-success btn-sm"
                id="add-new-custom-field">
            <i class="fa fa-plus"></i>
            Add New Field
        </button>
    </div>
</div>

@push('partial_js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#add-new-custom-field').on('click', function () {
                let customFieldFormIndex = $('.custom-field-form').length,
                    hasFieldConfig = !!'{{ isset($has_field_config) && $has_field_config }}';

                $.get('{{url('customer-fields/get-form?index=')}}' + customFieldFormIndex + '&has_field_config=' + hasFieldConfig, function (newCustomFieldForm) {
                    $('#new-custom-field-button').before(newCustomFieldForm);
                });
            });

            $(document).on('click', '.remove-custom-field', function () {
                $(this).closest('.custom-field-form').remove();
            });

            var $type = $(".field_type");
            var $options_source = $(".source_options");
            var optins_types = ['select', 'radio', 'multi_values'];

            $type.each(function () {
                let formIndex = $(this).data('form_index');

                if (_.includes(optins_types, $(this).val())) {
                    $(`#options-field-${formIndex}`).fadeIn();
                }
            });

            $options_source.each(function () {
                let formIndex = $(this).data('form_index');

                if ($(this).val()) {
                    $(`.options-source-${formIndex}`).fadeOut();
                    $(`.options-source-${formIndex}-${$(this).val()}`).fadeIn();

                }
            });
            $(document).on('change', '.field_type', function (event) {
                let formIndex = $(this).data('form_index');

                if (_.includes(optins_types, $(this).val())) {
                    $(`#options-field-${formIndex}`).fadeIn();
                } else {
                    $(`#options-field-${formIndex}`).fadeOut();
                }
            });

            $(document).on('change', '.source_options', function () {
                let formIndex = $(this).data('form_index');
                $(`.options-source-${formIndex}`).fadeOut();
                $(`.options-source-${formIndex}-${$(this).val()}`).fadeIn();
            });

        });
    </script>
@endpush
