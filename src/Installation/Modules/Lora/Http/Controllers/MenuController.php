<?php

namespace Dosarkz\Lora\Installation\Modules\Lora\Http\Controllers;

use Dosarkz\Lora\Installation\Modules\Lora\Http\Requests\StoreMenuRequest;
use Dosarkz\Lora\Installation\Modules\Lora\Http\Requests\UpdateMenuRequest;
use Dosarkz\Lora\Installation\Modules\Lora\Models\MenuRole;
use Dosarkz\Lora\Installation\Modules\Lora\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MenuController extends ModuleController
{
    public function __construct()
    {
        $model = config('menu.admin.model');
        $this->setModule(config('menu.module.alias'));
        $this->setModel(new $model);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $model = $this->getModel()->paginate();
        $module = $this->getModule();
        return view($this->getModule()->alias.'::index', compact('model', 'module'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $model = $this->getModel();
        $module = $this->getModule();
        $roles = Role::all();
        return view($this->getModule()->alias.'::create', compact('model', 'module', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreMenuRequest $request)
    {
        $request->merge([
            'user_id' => auth()->guard('admin')->user()->id
        ]);

        $model  = $this->getModel()->create($request->all());

        if($request->has('menuRole'))
        {
            MenuRole::where('menu_id',$model->id)->delete();

            foreach ($request->input('menuRole') as $role_id => $item) {
                MenuRole::firstOrCreate([
                    'menu_id' => $model->id,
                    'role_id' => $role_id
                ]);
            }
        }
        return redirect()->back()->with('success', trans('admin::base.resource_created'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view($this->getModule()->alias.'::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->getModel()->findOrFail($id);
        $module = $this->getModule();
        $roles = Role::all();
        return view($this->getModule()->alias.'::edit', compact('model', 'module', 'roles'));
    }

    /**
     * @param UpdateMenuRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateMenuRequest $request, $id)
    {
        $model = $this->getModel()->findOrFail($id);

        if($request->has('menuRole'))
        {
            MenuRole::where('menu_id',$model->id)->delete();

            foreach ($request->input('menuRole') as $role_id => $item) {
                MenuRole::firstOrCreate([
                    'menu_id' => $model->id,
                    'role_id' => $role_id
                ]);
            }
        }

        $model->update($request->all());

        return redirect('admin/menu')->with('success', trans('admin::base.resource_updated'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $model = $this->getModel()->findOrFail($id);
        $model->delete();
        return redirect()->back()->with('success', trans('admin::base.resource_deleted'));
    }
}