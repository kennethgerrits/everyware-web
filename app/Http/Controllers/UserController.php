<?php

namespace App\Http\Controllers;


use App\Forms\UserForm;
use App\Models\User;
use App\Models\Department;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends CrudController
{
    protected $indexView = 'groups';
    protected $editView = 'users.edit';
    protected $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email:rfc|unique:users',
        'roles' => 'required|array',
        'password' => 'confirmed',
    ];

    public $createRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email:rfc|unique:users',
        'roles' => 'required|array',
        'password' => 'required|confirmed',
    ];

    protected function model()
    {
        return new User;
    }

    public function getForm($data = [])
    {
        return $this->createNamed('user', UserForm::class, $data);
    }

    public function index()
    {
        $view = view($this->indexView);
        $view->users = User::all();
        return $view;
    }

    public function edit($id)
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        $item = $this->model()->find($id);

        $view = view($this->editView);

        $view->item = $item;
        $view->form = $this->getForm($item)->createView();

        return $view;
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        $item = $this->model()->find($id);

        $updateRules = $this->rules;

        if ($item->email == $request->input('user.email')) {
            $updateRules['email'] = 'required|email:rfc';
        }

        $rules = $this->getRules($updateRules, $id);

        $this->validateForm($this->getForm($item), $request, $rules);

        if ($message = $this->validateRoles($item, $request->input('user.roles'))) {
            session()->flash('error', $message);
            return redirect()
                ->back()
                ->withInput();
        }

        $this->handleForm($request, $item, $rules);

        $this->storeDepartments($request, $item);

        if ($item->save()) {
            $request->session()->flash('success', 'Gebruiker: ' . $item->name . ' is succesvol geüpdate.');
        } else {
            $request->session()->flash('error', 'Gebruiker: ' . $item->name . ' kon niet geüpdate worden.');
        }

        return redirect()->route("groups.index");
    }

    public function create()
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

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
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        $item = $this->model();

        $rules = $this->getRules($this->createRules);

        $this->validateForm($this->getForm($item), $request, $rules);

        if ($message = $this->validateRoles($item, $request->input('user.roles'))) {
            session()->flash('error', $message);
            return redirect()
                ->back()
                ->withInput();
        }
        $this->storeDepartments($request, $item);

        $this->handleForm($request, $item, $rules);

        $request->session()->flash('success', 'Gebruiker: ' . $request->user['first_name'] . ' is aangemaakt.');

        return redirect()->route("groups.index");
    }

    public function destroy(Request $request, $id)
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        $item = $this->model()->find($id);

        $item->many($item->studentGroups(), [], 'studentgroup_ids');

        $item->many($item->teacherGroups(), [], 'teachergroup_ids');

        if ($item->delete()) {
            $request->session()->flash('success', 'Gebruiker: ' . $item->name . ' is verwijderd.');
        } else {
            $request->session()->flash('error', 'Gebruiker: ' . $item->name . ' kon niet verwijderd worden.');
        }

        return redirect()->route("groups.index");
    }

    protected function handleForm(Request $request, $item, $rules = [])
    {
        $userRequest = $request->get('user');

        $classgroup = null;
        $classgroups = null;

        if (array_key_exists('studentgroup_ids', $userRequest)) {
            $classgroups = $userRequest['studentgroup_ids'];
        }

        if ($request->isMethod('post')) {
            User::create([
                'first_name' => $userRequest['first_name'],
                'last_name' => $userRequest['last_name'],
                'email' => $userRequest['email'],
                'password' => bcrypt($userRequest['password']),
                'roles' => $userRequest['roles'],
                'studentgroup_ids' => $classgroups,
                'templates' => array_key_exists('templates', $userRequest) ? $userRequest['templates'] : [],
                'departments' => array_key_exists('departments', $userRequest) ? $userRequest['departments'] : [],
            ]);
        }

        if ($request->isMethod('put')) {
            unset($userRequest['password_confirmation']);
            if (!$userRequest['password']) {
                unset($userRequest['password']);
                $item->fill([
                    'first_name' => $userRequest['first_name'],
                    'last_name' => $userRequest['last_name'],
                    'email' => $userRequest['email'],
                    'roles' => $userRequest['roles'],
                    'classgroup_id' => $classgroup,
                    'templates' => array_key_exists('templates', $userRequest) ? $userRequest['templates'] : [],
                    'departments' => array_key_exists('departments', $userRequest) ? $userRequest['departments'] : [],
                ]);
            } else {
                $item->fill([
                    'first_name' => $userRequest['first_name'],
                    'last_name' => $userRequest['last_name'],
                    'email' => $userRequest['email'],
                    'password' => bcrypt($userRequest['password']),
                    'roles' => $userRequest['roles'],
                    'classgroup_id' => $classgroup,
                    'templates' => array_key_exists('templates', $userRequest) ? $userRequest['templates'] : [],
                    'departments' => array_key_exists('departments', $userRequest) ? $userRequest['departments'] : [],
                ]);
            }

            $item->many($item->studentGroups(), $classgroups, 'studentgroup_ids');

            $item->save();
        }
    }

    private function validateRoles($item, $roles)
    {
        if (in_array("STUDENT", $roles) && (in_array("TEACHER", $roles) || in_array("ADMIN", $roles))) {
            return 'Een leerling kan geen leraar en/of directie rol hebben.';
        } else if(auth()->user()->isAdmin() && $item->id == auth()->user()->id && !in_array("ADMIN", $roles)){
            return 'De rol directie kan niet worden verwijderd.';
        } else if(!auth()->user()->isAdmin() && in_array("ADMIN", $roles)){
            return 'Alleen de rol Directie kan een directie rol toekennen.';
        } else if(!auth()->user()->isAdmin() && $item->isAdmin() && !in_array("ADMIN", $roles)){
            return 'De rol directie kan niet worden verwijderd door een docent.';
        }

        return false;
    }

    public function storeDepartments($request, $item)
    {
        $departments = $request->input('user.departments');

        $tempArr = []; // vanwege overload, php error :(
        if ($departments) {
            foreach ($departments as $department) {
                if (!Department::find($department) && !Department::where('name', $department)->first()) {
                    $newDepartment = Department::create([
                        'name' => $department
                    ]);
                    array_push($tempArr, $newDepartment->_id);
                }
            }

            $item->departments = array_merge($item->departments ?: [], $tempArr);
        }
    }
}
