<?php

namespace App\Http\Controllers;


use App\Forms\TemplateCollectionForm;
use App\Models\Difficulty;
use App\Models\Image;
use App\Models\Tag;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateCollectionController extends CrudController
{
    protected $indexView = 'learning-material';
    protected $editView = 'template-collections.edit';
    protected $rules = [
        'question_amount' => 'required|integer|min:1',
        'cesuur' => 'required|integer|lte:question_amount',
        'welcome_message' => 'required',
        'category_id' => 'required',
        'name' => 'required',
    ];

    protected function model()
    {
        return new Template;
    }

    public function getForm($data = [])
    {
        return $this->createNamed('templatecollection', TemplateCollectionForm::class, $data);
    }

    public function edit($id)
    {
        $item = $this->model()->find($id);

        $view = view($this->editView);

        $availableTemplates = Template::normal()->whereNotIn('_id', $item->templates ?: [])->pluck('_id', 'name');

        $existingTemplates = Template::whereIn('_id', $item->templates ?: [])->pluck('_id', 'name');

        if (Image::find($item->image_id)) {
            $view->image = Image::find($item->image_id)->src;
        }

        $view->item = $item;
        $view->form = $this->getForm($item)->createView();
        $view->availableTemplates = $availableTemplates;
        $view->existingTemplates = $existingTemplates;
        $view->route = route('template-collections.update', $id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $item = $this->model()->find($id);

        $rules = $this->getRules($this->rules, $id);

        $this->handleForm($request, $item, $rules);

        $this->storeImage($request, $item);

        $this->storeTags($request, $item);

        $this->storeDifficulties($request, $item);

        $templateIds = $request->get('templates');

        $item->templates = $templateIds;

        if ($item->save()) {
            $request->session()->flash('success', 'Collectie: ' . $item->name . ' is succesvol geüpdate.');
        } else {
            $request->session()->flash('error', 'Collectie: ' . $item->name . ' kon niet geüpdate worden.');
        }

        return redirect()->route('material.index');
    }

    public function create()
    {
        $view = view($this->editView);

        $formView = $this->getForm($this->model())->createView();
        $availableTemplates = Template::normal()->pluck('_id', 'name');

        $view->form = $formView;
        $view->availableTemplates = $availableTemplates;
        $view->route = route('template-collections.store');

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

        $this->storeTags($request, $item);

        $templateIds = $request->get('templates');

        $item->templates = $templateIds;

        $item->is_collection = true;

        if ($item->save()) {
            $request->session()->flash('success', 'Collectie: ' . $item->name . ' is succesvol aangemaakt.');
        } else {
            $request->session()->flash('error', 'Collectie: ' . $item->name . ' kon niet worden aangemaakt.');
        }

        return redirect()->route('material.index');
    }

    public function destroy(Request $request, $id)
    {
        $item = $this->model()->find($id);
        if ($item->delete()) {
            $request->session()->flash('success', 'Collectie: ' . $item->name . ' is verwijderd.');
        } else {
            $request->session()->flash('error', 'Collectie: ' . $item->name . ' kon niet verwijderd worden.');
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

    public function storeTags($request, $item)
    {
        $tags = $request->input('templatecollection.tags');

        $tempArr = [];
        if ($tags) {
            foreach ($tags as $tag) {
                if (!Tag::find($tag) && !Tag::where('name', $tag)->first()) {
                    $newTag = Tag::create([
                        'name' => $tag
                    ]);
                    array_push($tempArr, $newTag->_id);
                }
            }

            $item->tags = array_merge($item->tags, $tempArr);
            $item->save();
        }
    }

    public function storeDifficulties($request, $item)
    {
        $difficulties = $request->input('templatecollection.difficulties');

        $tempArr = []; // vanwege overload, php error :(
        if ($difficulties) {
            foreach ($difficulties as $difficulty) {
                if (!Difficulty::find($difficulty) && !Difficulty::where('name', $difficulty)->first()) {
                    $newdifficulty = Difficulty::create([
                        'name' => $difficulty
                    ]);
                    array_push($tempArr, $newdifficulty->_id);
                }
            }

            $item->difficulties = array_merge($item->difficulties, $tempArr);
            $item->save();
        }
    }
}
