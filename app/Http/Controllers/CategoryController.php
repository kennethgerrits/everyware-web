<?php

namespace App\Http\Controllers;


use App\Forms\CategoryForm;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Image;

class CategoryController extends CrudController
{
    protected $indexView = 'learning-material.index';
    protected $editView = 'category.edit';
    protected $rules = [
        'name' => 'required',
    ];

    protected function model()
    {
        return new Category;
    }

    public function getForm($data = [])
    {
        return $this->createNamed('category', CategoryForm::class, $data);
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
        if (Image::find($item->image_id)) {
            $view->image = Image::find($item->image_id)->src;
        }

        $view->item = $item;
        $view->route = route('category.update', $id);
        $view->form = $this->getForm($item)->createView();

        return $view;
    }

    public function update(Request $request, $id)
    {
        $item = $this->model()->find($id);

        $rules = $this->getRules($this->rules, $id);

        $this->handleForm($request, $item, $rules);
        $this->storeImage($request, $item);

        if ($item->save()) {
            $request->session()->flash('success', 'Categorie: ' . $item->name . ' is succesvol geüpdate.');
        } else {
            $request->session()->flash('error', 'Categorie: ' . $item->name . ' kon niet geüpdate worden.');
        }

        return redirect()->route('material.index');
    }

    public function create()
    {
        $view = view($this->editView);

        $formView = $this->getForm($this->model())->createView();

        $view->form = $formView;

        $view->route = route('category.store');

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
        $this->storeImage($request, $item);

        $request->session()->flash('success', 'Categorie: ' . $request->category['name'] . ' is aangemaakt.');

        return redirect()->route('material.index');
    }

    public function destroy(Request $request, $id)
    {
        $item = $this->model()->find($id);

        if ($item->delete()) {
            $request->session()->flash('success', 'Categorie: ' . $item->name . ' is verwijderd.');
        } else {
            $request->session()->flash('error', 'Categorie: ' . $item->name . ' kon niet verwijderd worden.');
        }

        return redirect()->route('material.index');
    }

    public function storeImage($request, $item)
    {
        $image = $request->file('image_id');

        $validTypes = ['png', 'jpg', 'jpeg'];
        if ($image) {
            if (in_array($image->extension(), $validTypes)) {
                $formattedImage = \Intervention\Image\Facades\Image::make($image);

                $formattedImage->resize(250, 250, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $imageFile = Image::create([
                    'src' => base64_encode($formattedImage->encode()->encoded),
                    'alt' => $image->getClientOriginalName()
                ]);

                $item->image_id = $imageFile->_id;
                $item->save();
            }
        }
    }
}
