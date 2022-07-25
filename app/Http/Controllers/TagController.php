<?php

namespace App\Http\Controllers;


use App\Forms\TagForm;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends CrudController
{
    protected $indexView = 'tags.index';
    protected $editView = 'crud.edit';
    protected $rules = [
        //Insert Rules
    ];

    protected function model()
    {
        return new Tag;
    }

    public function getForm($data = [])
    {
        return $this->createNamed('tag', TagForm::class, $data);
    }

    public function index()
    {
        $view = view($this->indexView);

        return $view;
    }

    public function edit($id)
    {
        $item = $this->model()->find($id);

        $view = view($this->editView);

        $view->item = $item;
        $view->form = $this->getForm($item)->createView();

        return $view;
    }

    public function update(Request $request, $id)
    {
        $item = $this->model()->find($id);

        $rules = $this->getRules($this->rules, $id);

        $this->handleForm($request, $item, $rules);

        return redirect()->route($this->indexView);
    }

    public function create()
    {
        $view = view($this->editView);

        $formView = $this->getForm($this->model())->createView();

        $view->form = $formView;

        return $view;
    }

    /**
     * Save a new item
     *
     * @param  Request  $request
     * @return \Response
     */
    public function store(Request $request)
    {
        $item = $this->model();

        $rules = $this->getRules($this->rules);

        $this->handleForm($request, $item, $rules);

        return redirect()->route($this->indexView);
    }

    public function destroy($id)
    {
        $item = $this->model()->find($id);
        $item->delete();

        return redirect()->route($this->indexView);
    }
}
