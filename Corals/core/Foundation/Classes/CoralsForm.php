<?php

namespace Corals\Foundation\Classes;

use Carbon\Carbon;
use Collective\Html\FormFacade as Form;
use Collective\Html\HtmlFacade as Html;
use Corals\Foundation\Traits\Language\Translatable;
use Corals\Settings\Facades\CustomFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CoralsForm
{
    const CONTROLS_CLASS = 'form-control ';
    const INPUT_GROUP_CLASS = 'input-group ';
    const INPUT_GROUP_ADDON_CLASS = 'input-group-addon ';
    const INPUT_GROUP_ADDON_LEFT_CLASS = 'input-group-prepend';
    const INPUT_GROUP_ADDON_RIGHT_CLASS = 'input-group-append';
    const ERROR_SPAN_CLASS = 'help-block form-control-feedback';
    const HELP_TEXT_CLASS = 'text-muted text-sm';
    const REQUIRED_FIELD_CLASS = 'required-field ';
    const FORM_GROUP_CLASS = 'form-group ';
    const FORM_GROUP_ERROR_CLASS = 'has-danger ';
    const SELECT2_CLASS = 'select2-normal ';
    const FILE_CLASS = '';
    const SPACER = '&nbsp;&nbsp;';

    protected $skipValueTypes = ['file', 'password'];
    protected $isCheckboxRadio = ['checkbox', 'radio'];
    protected $selectTypes = ['select', 'select2', 'select2-tree'];

    public function __construct()
    {
    }

    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    public function label($key, $label, $attributes = [])
    {
        return $this->inputLabel($key, $label, $attributes);
    }

    public function inputLabel($key, $label, $attributes = [])
    {
        if (empty($label)) {
            return '';
        }
        return Form::label($key, trans($label), $attributes);
    }

    public function helpText($text)
    {
        return '<div class="' . self::HELP_TEXT_CLASS . '">' . trans($text) . '</div>';
    }

    public function inputAddon($addon, $left = true)
    {
        if (empty($addon)) {
            return '';
        } else {
            $class = $left ? self::INPUT_GROUP_ADDON_LEFT_CLASS : self::INPUT_GROUP_ADDON_RIGHT_CLASS;
            return '<div class="' . self::INPUT_GROUP_ADDON_CLASS . ' ' . $class . '"><div class="input-group-text">' . $addon . '</div></div>';
        }
    }

    public function errorMessage($key)
    {
        $error = '';

        $errors = view()->shared('errors');

        if (!is_null($errors) && count($errors) > 0 && $errors->has($key)) {
            $error = '<span class="' . self::ERROR_SPAN_CLASS . '">' . $errors->first($key) . '</span>';
        }

        return $error;
    }

    public function formGroup($content, $required = false, $error = null, $class = '')
    {
        if (empty($class)) {
            $class = self::FORM_GROUP_CLASS;
        }
        if ($required) {
            $class .= self::REQUIRED_FIELD_CLASS;
        }

        if (!empty($error)) {
            $class .= self::FORM_GROUP_ERROR_CLASS;
            $content = $content . $error;
        }

        return '<div class="' . $class . '">' . $content . '</div>';
    }

    public function input($key, $label = '', $required = false, $value = null, $attributes = [], $type = 'text')
    {
        $attributes['class'] = self::CONTROLS_CLASS . \Arr::get($attributes, 'class', '');

        $wrapper_class = self::FORM_GROUP_CLASS . ' ' . \Arr::pull($attributes, 'wrapper_class') . ' ';

        if (!Arr::has($attributes, 'data-placeholder')) {
            $attributes['placeholder'] = trans(\Arr::get($attributes, 'placeholder', $label ?? ''));
        }

        $attributes['id'] = \Arr::get($attributes, 'id', $key);

        $attributes = $this->setDataAttribute($attributes);

        $labelAttributes = \Arr::pull($attributes, 'label', []);

        $help_text = \Arr::pull($attributes, 'help_text', '');

        if (!empty($help_text)) {
            $help_text = $this->helpText($help_text);
        }

        $left_addon = \Arr::pull($attributes, 'left_addon', '');
        $right_addon = \Arr::pull($attributes, 'right_addon', '');

        if (!empty($left_addon) || !empty($right_addon)) {
            $left_addon = $this->inputAddon($left_addon, true);
            $right_addon = $this->inputAddon($right_addon, false);
        }

        //remove empty empty attributes
        $attributes = array_filter($attributes, 'removeEmptyArrayElement');

        // in case selectTypes, radio, checkboxes
        $options = \Arr::pull($attributes, 'options', []);

        if (in_array($type, $this->selectTypes)) {
            $input = Form::select($key, $options, $value, array_merge([], $attributes));
        } elseif (in_array($type, $this->skipValueTypes)) {
            $input = Form::{$type}($key, array_merge([], $attributes));

            if ($type == 'file') {
                $image = \Arr::get($attributes, 'with_preview',
                    true) ? '<img  src="#" alt="" class="preview hidden" width="100"/>' : '';
                $input = '<div class="upload-file-area" data-input="' . $attributes['id'] . '"><span class="' . self::FILE_CLASS . '">' . trans('Corals::labels.browse') . $input . '</span>' . self::SPACER . '<span class="file-name"></span>' . $image . '</div>';
            }
        } elseif ($type == 'checkbox') {
            $attributes['class'] = \Arr::get($attributes, 'class', '') . ' custom-control-input';
            $checked = \Arr::pull($attributes, 'checked', false);
            $id = \Arr::get($attributes, 'id', $key);

            $input = '<div class="custom-control custom-checkbox">';
            $input .= Form::{$type}($key, $value, $checked, array_merge([], $attributes));
            $input .= '<label class="custom-control-label" for="' . $id . '">' . self::SPACER . trans($label) . '</label></div>';
            $label = '';
        } elseif ($type == 'checkboxes') {
            $attributes['class'] = \Arr::get($attributes, 'class', '') . ' custom-control-input';
            $selected = $value;
            //$checkboxesWrapper to add ability to checkboxes to be inline with label you can use span for this case
            $checkboxesWrapper = \Arr::pull($attributes, 'checkboxes_wrapper', 'div');

            $input = "<$checkboxesWrapper>";

            foreach ($options as $checkbox_value => $checkbox_label) {
                $attributes['id'] = $checkbox_value . '_' . \Str::random(6);
                $input .= '<span class="custom-control custom-checkbox">';
                $input .= Form::checkbox($key, $checkbox_value, in_array($checkbox_value, $selected ?? []),
                    array_merge([], $attributes));
                $input .= '<label class="custom-control-label" for="' . $attributes['id'] . '">' . self::SPACER . $checkbox_label . '</label></span>' . self::SPACER;
            }

            $input = $input . "</$checkboxesWrapper>";
        } elseif ($type == 'radio') {
            $attributes['class'] = \Arr::get($attributes, 'class', '') . ' custom-control-input';
            $selected = $value;
            $input = '<div style="padding: 3px 0;">';
            $radioWrapper = Arr::get($attributes, 'radio_wrapper', 'span');
            foreach ($options as $radio_value => $radio_label) {
                $attributes['id'] = $radio_value . '_' . \Str::random(6);
                $input .= '<' . $radioWrapper . ' class="custom-control custom-radio">';
                $input .= Form::radio($key, $radio_value, $radio_value == $selected, array_merge([], $attributes));
                $input .= '<label class="custom-control-label" for="' . $attributes['id'] . '">' . self::SPACER . $radio_label . '</label>' .
                    "</$radioWrapper>" . self::SPACER;
            }
            $input = $input . '</div>';
        } elseif ($type == 'date_range') {
            $input = '<div class="input-group input-daterange" data-autoclose="true" data-date-format="yyyy-mm-dd">';
            $input .= $this->date($key . "[from]", '', $required, is_array($value) ? $value['from'] ?? null : $value,
                $attributes);
            $input .= '<div class="input-group-addon">to</div>';
            $input .= $this->date($key . "[to]", '', $required, is_array($value) ? $value['to'] ?? null : $value,
                $attributes);
            $input .= '</div>';
        } elseif ($type == 'number_range') {
            $input = '<div class="input-group input-number-range">';
            $input .= $this->number($key . "[from]", '', $required,
                is_array($value) ? $value['from'] ?? null : $value, $attributes);
            $input .= '<div class="input-group-addon">to</div>';
            $input .= $this->number($key . "[to]", '', $required, is_array($value) ? $value['to'] ?? null : $value,
                $attributes);
            $input .= '</div>';
        } else {
            $input = Form::{$type}($key, $value, array_merge([], $attributes));
        }

        $label = $this->inputLabel($key, $label, $labelAttributes);

        if (!empty($left_addon) || !empty($right_addon)) {
            $input = '<div class="' . self::INPUT_GROUP_CLASS . '">' . $left_addon . $input . $right_addon . '</div>';
        }

        $response = $label . $input . $help_text;

        return $this->toHtmlString($this->formGroup($response, $required, $this->errorMessage($key), $wrapper_class));
    }

    public function checkbox($key, $label = '', $checked = false, $value = 1, $attributes = [])
    {
        $attributes['value'] = $value;
        $attributes['checked'] = $checked;

        $checkbox = $this->input($key, $label, false, $value, $attributes, 'checkbox');

        if (Arr::get($attributes, 'inline-form', false)) {
            $checkbox = '<label style="display: block;">&nbsp;</label>' . $checkbox;
        }

        return $checkbox;
    }

    public function checkboxes($key, $label = '', $required = false, $options = [], $selected = [], $attributes = [])
    {
        $options = $this->getArrayOf($options);
        $attributes['options'] = $options;
        return $this->input($key, $label, $required, $selected, $attributes, 'checkboxes');
    }

    public function radio($key, $label = '', $required = false, $options = [], $selected = null, $attributes = [])
    {
        $options = $this->getArrayOf($options);

        $attributes['options'] = $options;

        if ($required && !$selected) {
            $selected = array_key_first($options);
        }

        return $this->input($key, $label, $required, $selected, $attributes, 'radio');
    }

    public function text($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'text');
    }

    public function color($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'color');
    }

    public function date($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'date');
    }

    public function dateRange($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'date_range');
    }

    public function textarea($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        if (\Arr::has($attributes, 'class') && \Str::contains($attributes['class'], 'ckeditor')) {
            \Assets::add(asset('assets/corals/plugins/ckeditor/ckeditor.js'));
        }

        $attributes['rows'] = \Arr::get($attributes, 'rows', '4');

        return $this->input($key, $label, $required, $value, $attributes, 'textarea');
    }

    public function number($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'number');
    }

    public function numberRange($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'number_range');
    }

    public function email($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'email');
    }

    public function password($key, $label = '', $required = false, $attributes = [])
    {
        $attributes = array_merge($attributes, ['autocomplete' => 'new-password']);

        return $this->input($key, $label, $required, null, $attributes, 'password');
    }

    public function boolean($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        if (!is_null($value)) {
            if ($value === true || $value === "true") {
                $value = "true";
            } else {
                $value = "false";
            }
        }


        $options = \Arr::pull($attributes, 'options', ['true' => 'True', 'false' => 'False']);

        return $this->select($key, $label, $options, $required, $value, $attributes);
    }

    public function select(
        $key,
        $label = '',
        $options = [],
        $required = false,
        $value = null,
        $attributes = [],
        $type = 'select'
    ) {
        if (!empty($label)) {
            $label = trans($label);
        }
        $placeholder = trans('Corals::labels.select', ['label' => $label]);

        if ($type == 'select') {
            $attributes['placeholder'] = \Arr::get($attributes, 'placeholder', $placeholder);
        } else {
            $dataPlaceholder = \Arr::pull($attributes, 'data-placeholder',
                \Arr::pull($attributes, 'placeholder', $placeholder));

            $attributes['data-placeholder'] = $dataPlaceholder;

            $attributes['class'] = \Arr::get($attributes, 'class', 'select2-normal');

            if (!\Str::contains($attributes['class'], 'select2')) {
                $attributes['class'] .= ' ' . self::SELECT2_CLASS;
            }

            $options = $this->getArrayOf($options);

            if (!\Arr::get($attributes, 'multiple', false)) {
                //add empty option to enable select2 placeholder
                if ($type != 'select2-tree') {
                    $options = ['' => ''] + $options;
                } else {
                    $options = array_merge([
                        [
                            'id' => null,
                            'text' => $attributes['data-placeholder'],
                            'selected' => true
                        ]
                    ], $options);
                }
            } elseif (!Str::is('*[]', $key)) {
                $key .= '[]';
            }
        }

        if ($type == 'select2-tree') {
            $attributes['class'] = 'select2-tree';
            $attributes['data-options'] = json_encode($options);
            $attributes['options'] = [];
        } else {
            $attributes['options'] = $options;
        }

        return $this->input($key, $label, $required, $value, $attributes, $type);
    }

    public function select2($key, $label = '', $options = [], $required = false, $value = null, $attributes = [])
    {
        return $this->select($key, $label, $options, $required, $value, $attributes, $type = 'select2');
    }

    public function file($key, $label = '', $required = false, $attributes = [])
    {
        return $this->input($key, $label, $required, null, $attributes, 'file');
    }

    /**
     * get array from collection object if options passed as object
     * @param $options
     * @return array
     */
    protected function getArrayOf($options)
    {
        if (gettype($options) == 'object') {
            try {
                $options = $options->toArray();
            } catch (\Exception $exception) {
                $options = [];
            }
        }

        return $options;
    }

    /**
     * @param $href
     * @param $label
     * @param array $attributes
     * @return HtmlString
     */
    public function link($href, $label, $attributes = [])
    {
        $attributes = $this->setDataAttribute($attributes);

        $attributes['href'] = $href;

        $html_attributes = Html::attributes($attributes);

        return $this->toHtmlString('<a' . $html_attributes . '>' . trans($label) . '</a>');
    }

    /**
     * @param $attributes
     * @return mixed
     */
    protected function setDataAttribute($attributes)
    {
        $data = \Arr::pull($attributes, 'data', []);

        foreach ($data as $key => $value) {
            $attributes['data-' . $key] = $value;
        }

        return $attributes;
    }

    /**
     * @param $label
     * @param array $attributes
     * @param string $type
     * @return HtmlString
     */
    public function button($label, $attributes = [], $type = 'button')
    {
        $attributes = $this->setDataAttribute($attributes);

        $attributes['type'] = $type;

        $html_attributes = Html::attributes($attributes);

        $button = $this->toHtmlString('<button' . $html_attributes . '>' . trans($label) . '</button>');

        if (Arr::get($attributes, 'inline-form', false)) {
            $button = '<label style="display: block;">&nbsp;</label>' . $button;
        }

        return $button;
    }

    public function formButtons($label = '', $attributes = [], $cancelAttributes = [], $extraButtons = [])
    {
        $wrapper_class = \Arr::pull($attributes, 'wrapper_class', self::FORM_GROUP_CLASS . ' text-right');


        if (empty($label)) {
            $label = '<i class="fa fa-save"></i> ' . (view()->shared('title_singular') ?: '');
        }

        $buttons = '';

        if (!empty($extraButtons)) {
            foreach ($extraButtons as $extraButton) {
                $buttons .= $this->button($extraButton['label'], $extraButton['attributes'] ?? [],
                    $extraButton['type'] ?? 'button');
            }
        }

        $buttons .= $this->button($label, array_merge(['class' => 'btn btn-success'], $attributes), 'submit');

        if (\Arr::get($cancelAttributes, 'show_cancel', true)) {
            $cancelLabel = \Arr::get($cancelAttributes, 'label', trans('Corals::labels.cancel'));

            $cancelHrefDefault = view()->shared('resource_url') ?: 'dashboard';

            $cancelHref = \Arr::get($cancelAttributes, 'href', $cancelHrefDefault);

            $buttons .= self::SPACER . $this->link(url($cancelHref), $cancelLabel,
                    array_merge(['class' => 'btn btn-warning'], $cancelAttributes));
        }

        return $this->toHtmlString($this->formGroup($buttons, false, null, $wrapper_class));
    }

    /**
     * @param $model
     * @param string $fieldClass
     * @param array $customFields
     * @param string $propColumn
     * @return string
     */
    public function customFields($model, $fieldClass = 'col-md-4', $customFields = [], $propColumn = 'properties')
    {
        // check if model has CustomFieldsModelTrait
        if (!method_exists($model, 'customFieldSettings') && empty($customFields)) {
            return '';
        }

        if (empty($customFields)) {
            $customFields = array_merge($model->customFieldSettings(), $customFields);
        }

        $fields = [];

        $customFields = CustomFields::getSortedFields($customFields);

        foreach ($customFields as $field) {
            $name = $this->getCustomFieldName(Arr::get($field, 'name'));
            $name = str_replace('properties.', '', $name);
            $value = $this->getCustomFieldValue($model, $name, $propColumn, $field);

            $fields [$name]['content'] = $this->handleCustomFieldInput($field, $value);
            $fields[$name]['grid_class'] = data_get($field, 'field_config.grid_class') ?? $fieldClass;
            $fields[$name]['type'] = data_get($field, 'type');
        }

        return $this->renderCustomFieldsContent($fields);
    }

    /**
     * @param $model
     * @param $name
     * @param $propColumn
     * @param $field
     * @return array|\ArrayAccess|mixed
     */
    public function getCustomFieldValue($model, $name, $propColumn, $field)
    {
        $value = $model->exists ? $model->getProperty($name, null, null, $propColumn) : Arr::get($field,
            'default_value');

        $fieldTypeValueMethodName = sprintf("get%sFieldValue", Str::studly($field['type']));

        if (method_exists($this, $fieldTypeValueMethodName)) {
            $value = $this->{$fieldTypeValueMethodName}($value, $model, $propColumn);
        }


        return $value;
    }

    /**
     * @param $value
     * @param $model
     * @param $propColumn
     * @return array
     */
    protected function getGoogleLocationFieldValue($value, $model, $propColumn): array
    {
        return [
            'address' => $value,
            'long' => $model->getProperty('long', null, null, $propColumn),
            'lat' => $model->getProperty('lat', null, null, $propColumn),
            'address_street' => $model->getProperty('address_street', null, null, $propColumn),
            'address_city' => $model->getProperty('address_city', null, null, $propColumn),
            'address_state' => $model->getProperty('address_state', null, null, $propColumn),
            'address_country' => $model->getProperty('address_country', null, null, $propColumn),
        ];
    }

    public function getCustomFieldName($fieldName, $dotPattern = true)
    {
        if (Str::is('*[*', $fieldName)) {
            if ($dotPattern) {
                $fieldName = str_replace('[', '.', $fieldName);
                $fieldName = str_replace(']', '', $fieldName);
            }
            $name = "properties" . $fieldName;
        } else {
            if ($dotPattern) {
                $name = "properties.$fieldName";
            } else {
                $name = "properties[" . $fieldName . "]";
            }
        }

        return $name;
    }

    public function handleCustomFieldInput($field, $value = null)
    {
        $input = '';
        $field = $this->parseSourceOptions($field, $value);

        $fieldName = Arr::get($field, 'name');

        $name = $this->getCustomFieldName($fieldName, false);

        $fieldLabel = Arr::get($field, 'label');
        if (is_array($field['validation_rules'])) {
            $isFieldRequired = in_array('required', $field['validation_rules']);
        } else {
            $isFieldRequired = Str::contains(Arr::get($field, 'validation_rules', ''), 'required');
        }

        $fieldCustomAttributes = Arr::get($field, 'custom_attributes');
        $options = Arr::get($field, 'options', []);

        $options = collect($options)->mapWithKeys(function ($value, $key) {
            return [$value['key'] => $value['value']];
        })->toArray();

        $fieldCustomAttributes = collect($fieldCustomAttributes)->mapWithKeys(function ($value, $key) {
            return [data_get($value, 'key', $value[0] ?? null) => data_get($value, 'value', $value[1] ?? null)];
        })->toArray();

        switch ($fieldType = Arr::get($field, 'type')) {
            case 'label':
                $input = $this->{$fieldType}($name, $fieldLabel, $fieldCustomAttributes);
                break;
            case 'number':
            case 'date':
            case 'text':
            case 'textarea':
                $input = $this->{$fieldType}($name, $fieldLabel, $isFieldRequired, $value, $fieldCustomAttributes);
                break;
            case 'checkbox':
                $input = $this->{$fieldType}($name, $fieldLabel, $value, 1, $fieldCustomAttributes);
                break;
            case 'radio':
                $input = $this->{$fieldType}($name, $fieldLabel, $isFieldRequired, $options, $value,
                    $fieldCustomAttributes);
                break;
            case 'select':
                $input = $this->{$fieldType}($name, $fieldLabel, $options, $isFieldRequired, $value,
                    $fieldCustomAttributes, 'select2');
                break;
            case 'multi_values':

                if (!\Str::contains('[]', $name)) {
                    $name .= '[]';
                }
                $attributes = array_merge(['class' => 'select2-normal tags', 'multiple' => true],
                    $fieldCustomAttributes);

                $input = $this->select($name, $fieldLabel, $options, $isFieldRequired, $value, $attributes, 'select2');
                break;
            case 'google_location':

                //add _autocomplete id, if not exist,
                //since google map js depend on _autocomplete id
                if (array_search('_autocomplete', $fieldCustomAttributes) !== 'id') {
                    $fieldCustomAttributes['id'] = '_autocomplete';
                }

                $input = sprintf("%s %s %s %s %s %s %s",
                    $this->text($name, $fieldLabel, $isFieldRequired, data_get($value, 'address'),
                        $fieldCustomAttributes),
                    Form::hidden('properties[lat]', data_get($value, 'lat'), ['id' => 'lat']),
                    Form::hidden('properties[long]', data_get($value, 'long'), ['id' => 'long']),
                    Form::hidden('properties[address_street]', data_get($value, 'address_street'),
                        ['id' => 'address_street']),
                    Form::hidden('properties[address_city]', data_get($value, 'address_city'),
                        ['id' => 'address_city']),
                    Form::hidden('properties[address_state]', data_get($value, 'address_state'),
                        ['id' => 'address_state']),
                    Form::hidden('properties[address_country]', data_get($value, 'address_country'),
                        ['id' => 'address_country'])
                );

                \Assets::add(asset('assets/corals/js/auto_complete_google_address.js'));

                break;
            default:
                $input = $this->input($name, $fieldLabel, $isFieldRequired, $value, $fieldCustomAttributes, $fieldType);
                break;
        }

        return $input;
    }

    private function parseSourceOptions($field, $value)
    {
        $fieldOptions = Arr::get($field, 'options_setting');

        if (isset($fieldOptions['source']) && ($fieldOptions['source'] == "database")) {
            switch ($fieldType = Arr::get($field, 'type')) {
                case 'checkbox':
                case 'radio':
                    $model = $fieldOptions['source_model'];
                    $field['options'] = $model::all()->pluck($fieldOptions['source_model_column'], 'id')->toArray();
                    break;
                case 'select':
                case 'multi_values':
                    $field['options'] = [];
                    $custom_attribues = [
                        ['data-model', $fieldOptions['source_model']],
                        ['data-columns', json_encode([$fieldOptions['source_model_column']])],

                        ['class', 'select2-ajax '],
                    ];

                    if ($value) {
                        $custom_attribues = array_merge($custom_attribues,
                            [['data-selected', json_encode(is_array($value) ? $value : [$value])]]);
                    }
                    $field['custom_attributes'] = $custom_attribues;
                    break;
            }
        }

        return $field;
    }

    /**
     * @param Model|null $model
     * @param array $attributes
     * @return HtmlString
     */
    public function openForm(Model $model = null, $attributes = [])
    {
        // check form method value
        $formDefaultMethod = !is_null($model) ? ($model->exists ? 'PUT' : 'POST') : null;

        $attributes['method'] = \Arr::get($attributes, 'method', $formDefaultMethod);

        if (empty($attributes['method'])) {
            unset($attributes['method']);
        }

        //check form url value
        $attributes['url'] = trim(\Arr::get($attributes, 'url'), '/');

        if (empty($attributes['url'])
            && !\Arr::has($attributes, 'route')
            && !\Arr::has($attributes, 'action')) {
            $resource_url = view()->shared('resource_url');
            if (!empty($resource_url) && !is_null($model)) {
                $attributes['url'] = trim(url($resource_url . '/' . $model->hashed_id), '/');
            } else {
                unset($attributes['url']);
            }
        }

        // check form class attributes
        $attributes['class'] = \Arr::get($attributes, 'class', 'ajax-form');

        if (!is_null($model)) {
            $formOpenTag = Form::model($model, $attributes);
        } else {
            $formOpenTag = Form::open($attributes);
        }


        $customContent = $this->formTagAppendCustomContent($model);
        return $formOpenTag . $customContent;
    }

    /**
     * @param $model
     * @return string
     */
    protected function formTagAppendCustomContent($model)
    {
        $customContent = '';

        if ($model) {
            $customContent .= $this->handleFormWithTranslatableFields($model);
        }

        return $customContent;
    }

    /**
     * @param $model
     * @return string
     */
    protected function handleFormWithTranslatableFields($model)
    {
        $customContent = '';

        $modelHasTrait = !empty(array_search(Translatable::class, class_uses_recursive($model)));


        if ($model && $model->exists && $modelHasTrait && $model->hasTranslatableFields()
            && count(\Settings::get('supported_languages', [])) > 1) {
            $languages = '';

            foreach (\Language::allowed() as $code => $name) {
                $btnClass = 'btn btn-sm btn-default btn-secondary ' . ($code === \App::getLocale() ? 'current-lang' : '');

                $LangBtn = HtmlElement("button.$btnClass", [
                    'type' => 'button',
                    'data-model' => get_class($model),
                    'data-hashed_id' => $model->hashed_id,
                    'data-lang_code' => $code,
                ], \Language::flag($code));

                $languages .= HtmlElement('li', $LangBtn);
            }

            $switcher = HtmlElement('ul.list-inline', $languages);

            $translation_language_code = Form::hidden('translation_language_code', \App::getLocale(),
                ['class' => 'translation_language_code ignore-dirty-state']);
            $customContent .= $translation_language_code;

            $customContent .= HtmlElement('div.row > div.col-md-12 > div.form_language_switcher text-right', $switcher);
        }

        return $customContent;
    }

    /**
     * @param Model|null $model
     * @return string
     */
    public function closeForm(Model $model = null)
    {
        return Form::close();
    }

    /**
     * @param $fields
     * @return string
     */
    protected function renderCustomFieldsContent($fields)
    {
        $renderedFields = '';
        foreach ($fields as $fieldConfig) {
            if ($fieldConfig['type'] == 'hidden') {
                $renderedFields .= sprintf("<div class='%s' style='display: none'>%s</div>", $fieldConfig['grid_class'],
                    $fieldConfig['content']);
            } else {
                $renderedFields .= sprintf("<div class='%s'>%s</div>", $fieldConfig['grid_class'],
                    $fieldConfig['content']);
            }
        }

        return sprintf("<div class='row'>%s</div>", $renderedFields);
    }

    /**
     * @param $key
     * @param string $label
     * @param bool $required
     * @param null $value
     * @param array $attributes
     * @return string
     */
    public function datetimePicker($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        $attributes['class'] = Arr::get($attributes, 'class');

        $attributes = array_merge($attributes, ['class' => 'corals-datepicker']);

        $attributes = $this->setDataAttribute($attributes);

        $elementID = Arr::pull($attributes, 'id', 'ID_' . Str::random(3));

        $timePickerAttributes = [
            'class' => 'form-control time-picker',
            'data-start_hour' => Arr::pull($attributes, 'data-start_hour'),
            'data-last_hour' => Arr::pull($attributes, 'data-last_hour'),
            'data-minutes_step' => Arr::pull($attributes, 'data-minutes_step'),
            'id' => $elementID . '-time',
        ];

        $datetimePicker = '<div class="coralsDatetimePicker">';
        $datetimePicker .= "<div class='row'><div class='col-md-7'>" . $this->input('', $label, $required, null,
                array_merge($attributes, [
                    'id' => $elementID . '-date'
                ]), 'date') . "</div>";

        $datetimePicker .= "<div class='col-md-5' style='padding-left: 0;'>" . $this->select('', 'Time', [], $required,
                null, $timePickerAttributes, 'select2') . "</div></div>";

        $datetimePicker .= $this->formGroup(Form::hidden($key, $value,
            ['class' => 'datetime-hidden', 'id' => $elementID]), false, $this->errorMessage($key),
            self::FORM_GROUP_CLASS . ' mb-0 m-b-0');
        $datetimePicker .= '</div>';

        return $datetimePicker;
    }

    public function timePicker($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        $attributes['class'] = Arr::get($attributes, 'class');

        $elementID = Arr::pull($attributes, 'id', 'ID_' . Str::random(3));

        $startHour = Carbon::now()->setTime(
            Arr::pull($attributes, 'start_hour', 0),
            Arr::pull($attributes, 'start_minutes', 0)
        );

        $lastHour = Carbon::now()->setTime(
            Arr::pull($attributes, 'last_hour', 24),
            Arr::pull($attributes, 'last_minutes', 0)
        );

        $minutesStep = Arr::pull($attributes, 'minutes_step', 30);

        $timePickerAttributes = array_merge([
            'class' => 'form-control time-picker',
            'id' => $elementID . '-time',
        ], $attributes);

        $timePicker = '<div class="coralsTimePicker">';

        $options = [];

        while ($startHour->lt($lastHour)) {
            $options[$startHour->format('H:i:00')] = $startHour->format('h:i A');
            $startHour->addMinutes($minutesStep);
        }

        $timePicker .= $this->select($key, $label, $options, $required,
                $value, $timePickerAttributes, 'select2') . "</div>";

        return $timePicker;
    }
}
