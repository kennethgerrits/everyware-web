<?php

namespace App\Http\Controllers;


use App\Forms\ClassGroupForm;
use App\Models\ClassGroup;
use App\Models\User;
use Illuminate\Http\Request;

class ClassGroupController extends CrudController
{
    protected $indexView = 'groups';
    protected $editView = 'class-groups.edit';
    protected $rules = [
        'name' => 'required'
    ];

    protected function model()
    {
        return new ClassGroup;
    }

    public function getForm($data = [])
    {
        return $this->createNamed('classgroup', ClassGroupForm::class, $data);
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
        $users = User::student()->get();
        $teachers = User::teacher()->get();
        $view->users = $users;
        $view->teachers = $teachers;
        $view->form = $this->getForm($item)->createView();

        return $view;
    }

    public function update(Request $request, $id)
    {
        $item = $this->model()->find($id);

        $rules = $this->getRules($this->rules, $id);

        $this->handleForm($request, $item, $rules);

        $item->many($item->students(), $request->input('users'), 'student_ids');

        $item->many($item->teachers(), $request->input('teachers'), 'teacher_ids');

        if ($item->save()) {
            $request->session()->flash('success', 'Groep: ' . $item->name . ' is succesvol geüpdate.');
        } else {
            $request->session()->flash('error', 'Groep: ' . $item->name . ' kon niet geüpdate worden.');
        }

        return redirect()->route('groups.index');
    }

    public function create()
    {
        $view = view($this->editView);

        $formView = $this->getForm($this->model())->createView();

        $users = User::student()->get();
        $teachers = User::teacher()->get();
        $view->users = $users;
        $view->teachers = $teachers;
        $view->form = $formView;

        return $view;
    }

    public function store(Request $request)
    {
        $item = $this->model();

        $rules = $this->getRules($this->rules);

        $this->handleForm($request, $item, $rules);

        $item->many($item->students(), $request->input('users'), 'student_ids');

        $item->many($item->teachers(), $request->input('teachers'), 'teacher_ids');

        $request->session()->flash('success', 'Groep: ' . $request->classgroup['name'] . ' is aangemaakt.');

        return redirect()->route('groups.index');
    }

    public function destroy(Request $request, $id)
    {
        $item = $this->model()->find($id);

        $item->many($item->students(), [], 'student_ids');

        $item->many($item->teachers(), [], 'teacher_ids');

        if ($item->delete()) {
            $request->session()->flash('success', 'Groep: ' . $item->name . ' is verwijderd.');
        } else {
            $request->session()->flash('error', 'Groep: ' . $item->name . ' kon niet verwijderd worden.');
        }

        return redirect()->route('groups.index');
    }
}
