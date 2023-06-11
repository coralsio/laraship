<?php

namespace Corals\Foundation\Classes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ModelActionsHandler
{
    public function isActionVisible($action, $model)
    {
        if (!data_get($action, 'visible', true)) {
            return false;
        }
        
        if (empty($action['policies']) && empty($action['permissions'])) {
            return true;
        }

        $isVisible = false;

        if (!user()) {
            return $isVisible;
        }

        if (!empty($action['policies'])) {
            foreach ($action['policies'] as $policy) {
                $policyModel = $model;

                if (\Arr::has($action, 'policies_args')
                    && (is_null($action['policies_args']) || !empty($action['policies_args']))
                ) {
                    $policyArguments = $action['policies_args'];
                } elseif (\Arr::has($action, 'policies_args_relation')) {
                    $policyArguments = $model->{$action['policies_args_relation']};
                } else {
                    $policyArguments = $model;
                }

                if (!empty($action['policies_model'])) {
                    $policyModel = $action['policies_model'];
                }

                if ($policyModel != $policyArguments) {
//                    $policyModel = is_object($policyModel) ? get_class($policyModel) : $policyModel;

                    if (user()->can($policy, [$policyModel, $policyArguments])) {
                        $isVisible = true;
                        break;
                    }
                } else {

                    if (user()->can($policy, $policyModel)) {
                        $isVisible = true;
                        break;
                    }
                }
            }
        }

        if (!empty($action['permissions'])) {
            foreach ($action['permissions'] as $permission) {
                if (user()->hasPermissionTo($permission)) {
                    $isVisible = true;
                    break;
                }
            }
        }

        return $isVisible;
    }

    public function solveActionPatterns($action, $model)
    {
        $action = $this->patternSolver($action, $model);

        //for now only one level supported
        foreach ($action as $key => $value) {
            if (is_array($value) && !\Str::is('*_pattern', $key)) {
                $action[$key] = $this->patternSolver($value, $model);
            }
        }

        return $action;
    }

    public function patternSolver($array, $model)
    {
        $patternKeys = [];

        $this->getPatternKeys($array, $patternKeys);

        if (!empty($patternKeys)) {

            foreach ($patternKeys as $key) {
                $pattern = Arr::get($array, $key . '.pattern');
                $replace = Arr::get($array, $key . '.replace');

                $replaceResult = Str::replaceArray('[arg]', $this->eval_array($replace, $model), $pattern);

                $resultKey = str_replace('_pattern', '', $key);

                Arr::set($array, $resultKey, $replaceResult);
                Arr::forget($array, $key);
            }
        }
        return $array;
    }

    /**
     * @param $keys
     * @param $patternKeys
     * @param null $path
     * @param null $previousKey
     */
    protected function getPatternKeys($keys, &$patternKeys, $path = null, $previousKey = null)
    {
        foreach ($keys as $key => $value) {

            if (preg_match('/^.*_pattern/', $key)) {

                //prevent duplication
                if (last(explode('.', $path)) !== $previousKey) {
                    $path = is_null($path) ? $previousKey : "$path.$previousKey";
                }

                $fullPath = is_null($path) ? $key : "$path.$key";

                $patternKeys[] = $fullPath;

            }

            if (is_array($value)) {
                $this->getPatternKeys($value, $patternKeys, $path, $key);
            }
        }
    }

    public function eval_array($array, $object = null)
    {
        return array_map(function ($ele) use ($object) {
            return eval($ele);
        }, $array);
    }

    /**
     * @param $actions
     * @param null $view
     * @return string
     * @throws \Throwable
     */
    public function renderActions($actions, $view = null)
    {
        $actionsView = 'components.actions_buttons';

        if (!is_null($view) && view()->exists($view)) {
            $actionsView = $view;
        }

        return view($actionsView)->with(compact('actions'))->render();
    }
}
