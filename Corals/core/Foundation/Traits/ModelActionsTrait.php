<?php

namespace Corals\Foundation\Traits;

use Corals\Activity\Models\Activity;
use Corals\Foundation\Facades\ModelActionsHandler;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

trait ModelActionsTrait
{
    /**
     * @param null $view
     * @param array $only
     * @return array|string
     */
    public function getGenericActions($view = null)
    {
        $model = $this;

        $actions = $model->getConfig('genericActions');

        $formModalConfig = [];

        if (property_exists($this, 'formModalConfig')) {
            $formModalConfig = $this->formModalConfig;
        }


        if (!$actions || !is_array($actions)) {
            $actions = [];
        }

        $commonActions = [];

        if ($formModalConfig) {
            $data = [
                'action' => 'modal-load',
                'size' => $formModalConfig['size'] ?? '',
                'title_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.create");']
                ],
            ];
        }

        $commonActions['create'] = [
            'icon' => 'fa fa-fw fa-plus',
            'class' => 'btn btn-success',
            'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getCreateUrl();']],
            'label_pattern' => [
                'pattern' => '[arg]',
                'replace' => ['return trans("Corals::labels.without_icon.create");']
            ],
            'policies' => ['create'],
            'permissions' => [],
            'data' => $data ?? [],
        ];

        if (in_array(SoftDeletes::class, class_uses($this))) {
            $commonActions['deleted_records'] = [
                'class' => 'btn btn-warning',
                'href_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return url($object->getConfig("resource_url"))."?deleted=1";']
                ],
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.deleted_records");']
                ],
                'policies' => ['deletedRecords'],
            ];

            $commonActions['records'] = [
                'class' => 'btn btn-warning',
                'href_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return url($object->getConfig("resource_url"));']
                ],
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.records");']
                ],
                'policies' => ['records'],
            ];
        }

        $actions = array_merge($commonActions, $actions);

        foreach ($actions as $index => $action) {
            $actions[$index] = $model->getAction($action);
        }


        return ModelActionsHandler::renderActions($actions, $view);
    }

    /**
     * @param bool $isDatatable
     * @param null $view
     * @param array $only
     * @return array|string
     */
    public function getActions($isDatatable = false, $view = null, $only = [])
    {
        if ($this->archived ?? false) {
            return '';
        }

        if (in_array(SoftDeletes::class, class_uses($this)) && $this->trashed()) {
            $actions = $this->getTrashedActions();
        } else {
            $actions = $this->getConfig('actions');

            if (!$actions || !is_array($actions)) {
                $actions = [];
            }

            $actions = array_merge($this->getCommonActions(), $actions);
        }

        if (!empty($only)) {
            $actions = Arr::only($actions, $only);
        }

        foreach ($actions as $index => $action) {
            $actions[$index] = $this->getAction($action);
        }

        if (!$isDatatable) {
            $actions = array_filter($actions, function ($action) {
                if (isset($action['datatable_only'])) {
                    return false;
                }

                return true;
            });
        }

        if ($view || !$isDatatable) {
            $actions = ModelActionsHandler::renderActions($actions, $view);
        }

        return $actions;
    }

    public function getTrashedActions()
    {
        return [
            'restore' => [
                'icon' => 'fa fa-fw fa-undo',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getShowURL()."/restore";']],
                'class' => 'btn btn-primary btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.restore");']
                ],
                'policies' => ['restore'],
                'permissions' => [],
                'data' => [
                    'action' => 'post',
                    'table' => '.dataTableBuilder',
                    'confirmation_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Corals::labels.restore_confirmation');"]
                    ]
                ]

            ],
            'hardDelete' => [
                'icon' => 'fa fa-fw fa-remove',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getShowURL()."/hard-delete";']],
                'class' => 'btn btn-danger btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.hardDelete");']
                ],
                'policies' => ['hardDelete'],
                'permissions' => [],
                'data' => [
                    'action' => 'delete',
                    'table' => '.dataTableBuilder'
                ]
            ],
        ];
    }

    public function getCommonActions()
    {
        $formModalConfig = [];

        if (property_exists($this, 'formModalConfig')) {
            $formModalConfig = $this->formModalConfig;
        }

        if ($formModalConfig) {
            $data = [
                'action' => 'modal-load',
                'size' => $formModalConfig['size'] ?? '',
                'title_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.update_title", ["title" => $object->getIdentifier()]);']
                ],
            ];
        } else {
            $data = [];
        }

        return [
            'edit' => [
                'icon' => 'fa fa-fw fa-pencil',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getEditURL();']],
                'class' => 'btn btn-primary btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.edit");']
                ],
                'policies' => ['update'],
                'permissions' => [],
                'data' => $data
            ],
            'delete' => [
                'datatable_only' => true,
                'icon' => 'fa fa-fw fa-trash',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getShowURL();']],
                'class' => 'btn btn-danger btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.delete");']
                ],
                'policies' => ['destroy'],
                'permissions' => [],
                'data' => [
                    'action' => 'delete',
                    'table' => '.dataTableBuilder'
                ]
            ],
            'activity_log' => [
                'icon' => 'fa fa-history fa-fw',
                'href_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return url("activities/".str_replace("\\\","-",getMorphAlias($object))."/".$object->hashed_id);']
                ],
                'class' => 'btn btn-info btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.logs");']
                ],
                'policies_model' => Activity::class,
                'policies' => ['view'],
                'policies_args' => null,
                'permissions' => [],
                'data' => [
                    'action' => 'modal-load',
                    'title' => "<i class='fa fa-history fa-fw'></i> Activity Log",
                    'modal_class' => 'modal-x',
                ]
            ]
        ];
    }

    public function getAction($action)
    {
        if (!ModelActionsHandler::isActionVisible($action, $this)) {
            return null;
        }

        return ModelActionsHandler::solveActionPatterns($action, $this);
    }

    public function getActionByName($name, $view = null)
    {
        if (!$action = $this->getConfig("actions.$name")) {
            return null;
        }

        if ($action = $this->getAction($action)) {
            return ModelActionsHandler::renderActions([$name => $action], $view);
        }

        return null;
    }


    /**
     * @param array $names
     * @param null $view
     * @return string
     */
    public function getActionsByNames(array $names, $view = null)
    {
        foreach ($names as $name) {
            $actions[] = $this->getActionByName($name, $view);
        }

        return join('', $actions ?? []);
    }
}
