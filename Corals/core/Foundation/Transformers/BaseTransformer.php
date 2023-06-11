<?php

namespace Corals\Foundation\Transformers;

use Illuminate\Support\Arr;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    protected $resource_url;
    protected $resource_route;
    protected $extras = null;

    public function __construct($extras = [])
    {
        $this->extras = $extras;
    }

    /**
     * @param $model
     * @param null $id
     * @return string
     */
    public function generateCheckboxElement($model, $id = null)
    {
        if (is_null($id)) {
            $modelHashedId = $model->hashed_id;
        } else {
            $modelHashedId = $id;
        }

        return '<div class="custom-control custom-checkbox">
               <input type="checkbox" class="datatable-row-checkbox custom-control-input" name="bulk_selected[]" 
               value="' . $modelHashedId . '" id="' . $modelHashedId . '_checkbox" />
               <label class="custom-control-label" for="' . $modelHashedId . '_checkbox"> </label>
               </div>';
    }

    /**
     * @param $model
     * @param array $actions
     * @return array|string
     * @throws \Throwable
     */
    protected function actions($model, array $actions = [])
    {
        if (\Arr::has($this->extras, 'include-action') && !\Arr::get($this->extras, 'include-action')) {
            return '';
        }

        $modelActions = $model->getActions(true);

        $actions = array_merge(empty($modelActions) ? [] : $modelActions, $actions);

        $actions = collect($actions)->filter(function ($action) {
            return !empty($action);
        });

        if (view()->exists('components.item_actions')) {
            return view('components.item_actions', ['actions' => $actions->toArray()])->render();
        } else {
            return '';
        }
    }

    /**
     * @param array $transformedArray
     * @param null $model
     * @param array $extra
     * @return array
     */
    public function transformResponse(array $transformedArray, $model = null, $extra = [])
    {
        $requestOnly = request()->get('select');

        if (!empty($requestOnly)) {
            $onlyColumns = explode(',', $requestOnly);

            $transformedArray = array_filter($transformedArray, function ($key) use ($onlyColumns) {
                return in_array($key, $onlyColumns);
            }, ARRAY_FILTER_USE_KEY);
        }

        if (!Arr::has($transformedArray, 'identifier') && $model) {
            $transformedArray['identifier'] = user() && user()->can('view', $model) ? HtmlElement('a',
                ['href' => $model->getShowURL()], $model->getIdentifier()) : $model->getIdentifier();
        }

        return $transformedArray;
    }

    public function getModelLink($model, $labels, $additionalAttributes = [], $isEdit = false, $showInModal = true)
    {
        if (user()->cannot($isEdit ? 'update' : 'view', $model)) {
            return $labels;
        }

        $linkAttributes = array_merge([
            'href' => $isEdit ? $model->getEditURL() : $model->getShowURL()
        ], $additionalAttributes);

        if ($showInModal) {
            $linkAttributes = array_merge([
                'data-action' => 'modal-load',
                'data-title' => $labels
            ], $linkAttributes);
        }

        return HtmlElement('a', $linkAttributes, $labels);
    }
}
