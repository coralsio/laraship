<?php

namespace Corals\Foundation\Http\Requests;

use Corals\Foundation\Facades\CoralsForm;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BaseRequest extends FormRequest
{
    /**
     * Model for the current request.
     * @var
     */
    protected $model;
    protected $customFieldsAttributes = [];


    protected function setModel($class, $routeName = '')
    {
        if (empty($routeName)) {
            $routeName = strtolower(Str::snake(class_basename($class)));
        }

        $model = $this->route($routeName);

        $model = $model ?? $class;

        $this->model = $model;
    }

    /**
     * Check if the user can continue in the request or not.
     * @param $action
     * @return bool
     **/
    protected function can($action)
    {
        $user = user();

        if (!$user) {
            return false;
        }

        return $user->can($action, $this->model);
    }

    protected function isAuthorized()
    {

        if ($this->isCreate() || $this->isStore()) {
            // Determine if the user is authorized to create an item,
            return $this->can('create');
        }

        if ($this->isEdit() || $this->isUpdate()) {
            // Determine if the user is authorized to update an item,
            return $this->can('update');
        }

        if ($this->isDelete()) {
            // Determine if the user is authorized to delete an item,
            return $this->can('destroy');
        }

        return $this->can('view');
    }

    /**
     * Check the process is create.
     *
     * @return bool
     **/
    protected function isCreate()
    {
        if ($this->is('*/create')) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is store.
     *
     * @return bool
     **/
    protected function isStore()
    {
        if ($this->isMethod('POST')) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is edit.
     *
     * @return bool
     **/
    protected function isEdit()
    {
        if ($this->is('*/edit')) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is update.
     *
     * @return bool
     **/
    protected function isUpdate()
    {
        if ($this->isMethod('PUT') ||
            $this->isMethod('PATCH')
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is verify.
     *
     * @return bool
     **/
    protected function isDelete()
    {
        if ($this->isMethod('DELETE')) {
            return true;
        }
        return false;
    }

    public function rules()
    {
        $rules = [];

        if ($this->isUpdate() || $this->isStore()) {
            if ($this->model && method_exists($this->model, 'customFieldSettings')) {

                $model = $this->model;

                if (is_string($model)) {
                    $model = new $this->model;
                }

                $rules = array_merge($rules, $this->getCustomFieldsRules($model->customFieldSettings()));
            }
        }

        return $rules;
    }

    protected function getCustomFieldsRules($customFields)
    {
        $rules = [];

        foreach ($customFields as $field) {
            $fieldName = Arr::get($field, 'name');

            $customFieldFullName = CoralsForm::getCustomFieldName($fieldName);

            $validation_rules = Arr::get($field, 'validation_rules');

            if (!empty($validation_rules)) {
                if (is_array($validation_rules)) {
                    //in case validation is different depends on the request method
                    if (array_key_exists($this->method(), $validation_rules)) {
                        $validation_rules = $validation_rules[$this->method()];
                    }
                }

                $rules[$customFieldFullName] = $validation_rules;

                $label = Arr::get($field, 'label');

                if (empty($label)) {
                    $label = $fieldName;
                }

                $this->customFieldsAttributes[$customFieldFullName] = strtolower(trans($label));
            }
        }

        return $rules;
    }

    /**
     * @return array
     */

    protected function customFieldsAttributes(): array
    {
        return $this->customFieldsAttributes;
    }

    /**
     * Create the default validator instance.
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createDefaultValidator(ValidationFactory $factory)
    {
        return $factory->make(
            $this->validationData(), $this->container->call([$this, 'rules']),
            $this->messages(), array_merge($this->customFieldsAttributes(), $this->attributes())
        );
    }

    protected function getValidatorInstance()
    {
        if ($this->isUpdate() || $this->isStore()) {
            $modelClass = null;

            if ($this->model) {
                $modelClass = is_string($this->model) ? $this->model : get_class($this->model);
            }

            $data = $this->all();

            $htmlentitiesExcluded = $modelClass
                ? $modelClass::htmlentitiesExcluded()
                : [];

            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    if (in_array($key, $htmlentitiesExcluded)) {
                        continue;
                    }

                    $data[$key] = strip_tags($value);
                }
            }

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}
