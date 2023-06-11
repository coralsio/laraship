<?php

namespace Corals\Menu\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Menu\Models\Menu;

class MenuRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Menu::class);

        return $this->isAuthorized();
    }

    /**
     * Check the process is store.
     *
     * @return bool
     **/
    protected function isStore()
    {
        if ($this->isMethod('POST') && !$this->is('*tree*')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Menu::class);
        $rules = parent::rules();

        $root = $this->get('root');

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:191',
                'status' => 'required|in:active,inactive',
//                'url' => 'required|max:191',
//                'active_menu_url' => 'required|max:191',
            ]);

            if ($root) {
                $rules = array_merge($rules, [
                    'key' => 'required|max:191|unique:menus,key',
                    'url' => '',
                    'active_menu_url' => '',
                ]);
            }
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, []);
        }

        if ($this->isUpdate()) {
            $menu = $this->route('menu');
            if ($root) {
                $rules = array_merge($rules, [
                    'key' => 'required|max:191|unique:menus,key,' . $menu->id
                ]);
            }
        }
        return $rules;
    }


    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();
        $data['properties']['always_active'] = \Arr::get($data, 'properties.always_active', false);
        $this->getInputSource()->replace($data);


        return parent::getValidatorInstance();
    }
}