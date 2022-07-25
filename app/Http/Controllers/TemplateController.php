<?php

namespace App\Http\Controllers;


use App\Enums\AnswerType;
use App\Enums\WordlistType;
use App\Forms\TemplateForm;
use App\Models\Image;
use App\Models\Template;
use App\Models\Wordlist;
use App\Models\Tag;
use App\Models\Difficulty;
use Illuminate\Http\Request;

class TemplateController extends CrudController
{
    protected $indexView = 'learning-materials';
    protected $editView = 'templates.edit';
    protected $rules = [
        'question_amount' => 'required|integer|min:1',
        'cesuur' => 'required|integer|min:1|lte:question_amount',
        'answer_type' => 'required',
        'question_type' => 'required',
        'difficulties' => 'required',
        'welcome_message' => 'required',
        'category_id' => 'required',
        'name' => 'required',
    ];

    private $mathTypes = [
        'ARITHMETIC_SUM_TEXT' => ['min_amount' => 'required|integer|min:0', 'max_amount' => 'required|integer|min:0', 'sum_type' => 'required'],
        'ARITHMETIC_SUM_IMAGE' => ['min_amount' => 'required|integer|min:0', 'max_amount' => 'required|integer|min:0', 'sum_type' => 'required'],
        'ARITHMETIC_LISTENING' => ['min_amount' => 'required|integer|min:0', 'max_amount' => 'required|integer|min:0',],
        'DRAG_IMAGE' => ['min_amount' => 'required|integer|min:0', 'max_amount' => 'required|integer|min:0',],
    ];

    protected function model()
    {
        return new Template;
    }

    public function getForm($data = [])
    {
        return $this->createNamed('template', TemplateForm::class, $data);
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

        if (Image::find($item->image_id)) {
            $view->image = Image::find($item->image_id)->src;
        }

        $templates = Template::all()->where('_id', '!=', $item->_id);

        $view->templates = $templates;

        $view->wordlists = Wordlist::all();

        $view->form = $this->getForm($item)->createView();

        $view->route = route('templates.update', $id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $item = $this->model()->find($id);

        $standardRules = $this->rules;

        if (array_key_exists($request->input('template.question_type'), $this->mathTypes)) {
            $standardRules = array_merge($standardRules, $this->mathTypes[$request->input('template.question_type')]);
            $item->answer_type = "DRAG";
        }

        $required_templates = $request->get("required_templates");
        $item->required_templates = $required_templates;

        $rules = $this->getRules($standardRules, $id);

        $this->handleForm($request, $item, $rules);

        $this->storeImage($request, $item);

        $this->storeTags($request, $item);

        $this->storeDifficulties($request, $item);

        if ($item->save()) {
            $request->session()->flash('success', 'Werkblad: ' . $item->name . ' is succesvol geüpdate.');
        } else {
            $request->session()->flash('error', 'Werkblad: ' . $item->name . ' kon niet geüpdate worden.');
        }

        return redirect()->route('material.index');
    }

    public function create()
    {
        $view = view($this->editView);

        $formView = $this->getForm($this->model())->createView();

        $view->templates = Template::all();

        $view->form = $formView;

        $view->route = route('templates.store');

        return $view;
    }

    public function store(Request $request)
    {
        $item = $this->model();

        $standardRules = $this->rules;

        if (array_key_exists($request->input('template.question_type'), $this->mathTypes)) {
            $standardRules = array_merge($standardRules, $this->mathTypes[$request->input('template.question_type')]);
            $item->answer_type = "DRAG";
        }

        $required_templates = $request->get("required_templates");
        $item->required_templates = $required_templates;

        $rules = $this->getRules($standardRules);

        $this->handleForm($request, $item, $rules);

        $this->storeImage($request, $item);

        $this->storeTags($request, $item);

        $this->storeDifficulties($request, $item);

        $request->session()->flash('success', 'Werkblad: ' . $request->template['name'] . ' is aangemaakt.');

        return redirect()->route('material.index');
    }

    public function destroy(Request $request, $id)
    {
        $item = $this->model()->find($id);
        if ($item->delete()) {
            $request->session()->flash('success', 'Werkblad: ' . $item->name . ' is verwijderd.');
        } else {
            $request->session()->flash('error', 'Werkblad: ' . $item->name . ' kon niet verwijderd worden.');
        }

        //TemplateObserver removes all references from other collections.
        return redirect()->route('material.index');
    }

    public function copy(Request $request, $id)
    {

        $item = $this->model()->find($id);
        $new = $item->replicate();
        $new->name = $request->get('name');

        if ($new->save()) {
            $request->session()->flash('success', 'Werkblad: ' . $new->name . ' is succesvol gekopieerd.');
        } else {
            $request->session()->flash('error', 'Werkblad: ' . $new->name . ' kon niet worden gekopieerd.');
        }

        return redirect()->route('material.index');
    }

    public function getWordlist($id)
    {
        $words = [];
        $wordlist = Wordlist::find($id);

        if (!$wordlist->list) {
            return [
                'id' => $wordlist->id,
                'words' => $words
            ];
        }

        if ($wordlist->type == WordlistType::TEXT) {
            foreach ($wordlist->list as $word) {
                $words[] = ['name' => $word, 'url' => null];
            }
        } else {
            $images = Image::findMany($wordlist->list);
            foreach ($images as $image) {
                $words[] = ['name' => $image->related_to ?: null, 'url' => $image->src];
            }
        }

        return [
            'id' => $wordlist->id,
            'words' => $words
        ];
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
        $tags = $request->input('template.tags');

        $tempArr = []; // vanwege overload, php error :(
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
        $difficulties = $request->input('template.difficulties');

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

    public function getAnswers()
    {
        return array_merge(['' => 'Selecteren..'], AnswerType::KEYS);
    }
}
