<?php

namespace Corals\Menu\Http\Controllers;

use Corals\Foundation\Facades\Actions;
use Corals\Foundation\Facades\Filters;
use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Menu\Http\Requests\MenuRequest;
use Corals\Menu\Models\Menu;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('menu.models.menu.resource_url');

        $this->title = 'Menu::module.menu.title';
        $this->title_singular = 'Menu::module.menu.title_singular';

        parent::__construct();
    }

    /**
     * @param MenuRequest $request
     * @param string $menu_key
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index(MenuRequest $request, $menu_key = 'sidebar')
    {
        try {
            $root = Menu::where('key', $menu_key)->root()->active()->first();

            if (!$root) {
                throw (new ModelNotFoundException())->setModel(
                    class_basename(Menu::class)
                );
            }

            $this->setViewSharedData([
                'title_singular' => trans('Corals::labels.update_title', ['title' => $root->name])
            ]);

            return view('Menu::menu.index')->with(compact('root'));
        } catch (\Exception $exception) {
            log_exception($exception, Menu::class, 'index');
            return redirect('dashboard');
        }
    }

    /**
     * @param MenuRequest $request
     * @return $this
     */
    public function create(MenuRequest $request)
    {
        $menu = new Menu();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        $parent_id = $request->get('parent') ?: 0;

        if ($parent_id) {
            $parent_id = hashids_decode($parent_id);

            $menu->parent_id = $parent_id;
            $parent = Menu::findOrFail($parent_id);
        } else {
            $parent = null;
        }

        $root = $menu->isRoot();

        return view('Menu::menu.create_edit')->with(compact('menu', 'root', 'parent'));
    }

    /**
     * @param MenuRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(MenuRequest $request)
    {
        try {
            $data = $request->except(['permissions']);

            $menu = Menu::create($data);

            $menu->permissions()->sync($request->get('permissions', []));

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.created', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Menu::class, 'store');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param MenuRequest $request
     * @param Menu $menu
     * @return $this
     */
    public function edit(MenuRequest $request, Menu $menu)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $menu->name])]);

        $root = $menu->isRoot();

        $parent = null;

        return view('Menu::menu.create_edit')->with(compact('menu', 'root', 'parent'));
    }

    /**
     * @param MenuRequest $request
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        try {
            $data = $request->except(['permissions']);

            if (!isset($data['roles'])) {
                $data['roles'] = null;
            }

            $menu->update($data);

            $menu->permissions()->sync($request->get('permissions', []));

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Menu::class, 'update');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function toggleStatus(Request $request, Menu $menu)
    {
        try {
            if (!user()->can('update', $menu)) {
                abort(403, trans('Corals::exceptions.403'));
            }

            $data['status'] = $menu->status === 'active' ? 'inactive' : 'active';

            $menu->update($data);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Menu::class, 'update');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param MenuRequest $request
     * @param Menu $menu
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(MenuRequest $request, Menu $menu)
    {
        try {
            Menu::where('parent_id', $menu->id)->delete();
            $menu->delete();

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Menu::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param MenuRequest $request
     * @param Menu $menu
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTree(MenuRequest $request, Menu $menu)
    {
        try {
            Actions::do_action('pre_update_menu_tree', $menu);
            $menu = Filters::do_filter('update_menu_tree', $menu);


            $json_tree = $request->get('tree');

            if ($json_tree) {
                $json_tree = json_decode($json_tree, true);
            }

            $tree = [];
            $this->buildTree($menu->id, $json_tree, $tree);

            foreach ($tree as $parent_id => $children) {
                foreach ($children as $order => $child_id) {
                    Menu::where('id', $child_id)->update(['parent_id' => $parent_id, 'order' => $order]);
                }
            }
            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Menu::class, 'updateTree');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        Actions::do_action('post_update_menu_tree', $menu);

        return response()->json($message);
    }

    protected function buildTree($id, $json_tree, &$tree)
    {
        foreach ($json_tree as $node) {
            $tree[$id][] = \Arr::get($node, 'id');

            if (isset($node['children'])) {
                $this->buildTree(\Arr::get($node, 'id'), $node['children'], $tree);
            }
        }
    }
}
