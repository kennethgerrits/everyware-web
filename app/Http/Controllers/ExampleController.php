<?php

namespace App\Http\Controllers;


use App\Forms\ExampleForm;
use App\Forms\StudentForm;
use App\Models\Example;
use App\Models\Student;
use Illuminate\Http\Request;

class ExampleController extends CrudController
{
    protected $indexView = 'example.index';
    protected $editView = 'crud.edit';
    protected $rules = [
        //Insert Rules
    ];

    protected function model()
    {
        //Return model of choice
        return new Example;
    }

    public function getForm($data = []){
        return $this->createNamed('example', ExampleForm::class, $data);
    }

    public function index(){
        $view = view($this->indexView);
        //Insert models
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

        return redirect()->route('example.index');
    }

    public function create(){
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

        return redirect()->route('example.index');
    }

    public function destroy($id)
    {
        $item = $this->model()->find($id);
        $item->delete();

        return redirect()->route('example.index');
    }


}
