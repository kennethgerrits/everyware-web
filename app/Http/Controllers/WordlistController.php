<?php

namespace App\Http\Controllers;


use App\Enums\WordlistType;
use App\Forms\WordlistForm;
use App\Models\Image;
use App\Models\Wordlist;
use App\Traits\UploadsFiles;
use Illuminate\Http\Request;

class WordlistController extends CrudController
{
    use UploadsFiles;

    public $image;
    protected $indexView = 'wordlists.index';
    protected $editView = 'wordlists.edit';
    protected $rules = [
        'name' => 'required',
        'type' => 'required',
    ];

    public function index()
    {
        $view = view($this->indexView);
        $wordLists = WordList::all();
        $view->wordLists = $wordLists;

        return $view;
    }

    public function edit($id)
    {
        $item = $this->model()->find($id);

        $view = view($this->editView);

        $view->item = $item;
        $view->id = $item->id;
        if ($item->type == WordlistType::TEXT) {
            $view->list = $item->list;
        } else if ($item->type == WordlistType::TEXT_IMAGE) {
            $view->list = $item->images;
        } else if ($item->type == WordlistType::IMAGE) {
            $view->list = $item->images;
        }

        $view->type = $item->type;
        $view->form = $this->getForm($item)->createView();

        return $view;
    }

    protected function model()
    {
        return new Wordlist;
    }

    public function getForm($data = [])
    {
        return $this->createNamed('wordlist', WordlistForm::class, $data);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model()->find($id);
        $skipLoop = false;
        if ($request->input('wordlist.type') == WordlistType::TEXT) {
            if (!$request->has('wordlist.words')) {
                $skipLoop = true;
            }
        }
        $rules = $this->getRules($this->rules, $id);

        $this->handleForm($request, $item, $rules);

        if ($item->type == WordlistType::TEXT) {

            $wordList = collect();
            if ($request->has('wordlist.existing_words')) {
                $existingWords = $request->input('wordlist.existing_words');
                foreach ($existingWords as $word) {
                    if ($word != null && $word != '') {
                        $wordList->add($word);
                    }
                }
            }
            if (!$skipLoop) {
                $words = $request->input('wordlist.words');

                foreach ($words as $word) {
                    if ($word != null && $word != '') {
                        $wordList->add($word);
                    }
                }

            }
            $item->list = $wordList->toArray();
        }

        if ($item->type == WordlistType::TEXT_IMAGE) {
            //Delete images that are removed from the list.
            $item = $this->deleteImages($request, $item);

            if ($request->has('wordlist.words')) {
                $words = $request->input('wordlist.words');

                $wordList = collect();
                foreach ($words as $word) {
                    if ($word != null && $word != '') {
                        $wordList->add($word);
                    }
                }
                $images = $this->handleFileUpload($request, $wordList->toArray());
                $ids = $images->pluck('_id');

                //Add new images to existing list
                $originalList = $item->list;
                $item->list = array_merge($originalList, $ids->toArray());
            }
            //Save the related_to field of the existing images
            $existingImages = $request->input('wordlist.existing_words');
            if ($existingImages) {
                foreach ($existingImages as $imageId => $name) {
                    $image = Image::find($imageId);
                    $image->related_to = $name;
                    $image->save();
                }
            }
        }

        if ($item->type == WordlistType::IMAGE) {
            //Delete images that are removed from the list.
            $item = $this->deleteImages($request, $item);

            if ($request->has('wordlist.files')) {
                $images = $this->handleFileUpload($request);
                $ids = $images->pluck('_id');
                $item->list = array_merge($item->list, $ids->toArray());
            }
        }
        if ($item->save()) {
            $request->session()->flash('success', 'Werkblad: ' . $item->name . ' is succesvol geüpdate.');
        } else {
            $request->session()->flash('error', 'Werkblad: ' . $item->name . ' kon niet geüpdate worden.');
        }

        return redirect()->route('wordlists.index');
    }

    private function listRedirect()
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors('Er dient minimaal een item aan de lijst toegevoegd te worden.');
    }

    private function deleteImages(Request $request, $item)
    {
        if ($request->input('wordlist.deleted_images') != '') {
            $ids = explode(',', $request->input('wordlist.deleted_images'));
            $list = $item->list;
            foreach ($ids as $id) {
                unset($list[array_search($id, $list)]);
            }
            $item->list = $list;
            return $item;
        }else{
            $item->list = [];
            return $item;
        }

    }

    protected function handleFileUpload(Request $request, $array = [])
    {
        $files = \is_array($request->file('wordlist.files')) ? $request->file('wordlist.files') : [$request->file('wordlist.files')];

        $images = collect();
        if (null != $files) {
            foreach ($files as $key => $file) {
                $validTypes = ['png', 'jpg', 'jpeg'];
                if (!in_array($file->extension(), $validTypes)) {
                    continue;
                }

                if (count($array)) {
                    $imageFile = Image::create([
                        'src' => base64_encode(file_get_contents($file)),
                        'alt' => 'wordlist_image_' . $array[$key],
                        'related_to' => $array[$key],
                    ]);
                } else {
                    $imageFile = Image::create([
                        'src' => base64_encode(file_get_contents($file)),
                        'alt' => 'image'
                    ]);
                }

                $images->add($imageFile);
            }
        }
        return $images;
    }

    public function create()
    {
        $view = view($this->editView);
        $formView = $this->getForm($this->model())->createView();

        $view->form = $formView;
        $view->id = 0;
        $view->type = '';
        $view->list = null;

        return $view;
    }

    /**
     * Save a new item
     *
     * @param Request $request
     * @return \Response
     */
    public function store(Request $request)
    {
        $skipLoop = false;
        if ($request->input('wordlist.type') == WordlistType::TEXT) {
            if (!$request->has('wordlist.words')) {
                $skipLoop = true;
            }
        } else if ($request->input('wordlist.type') == WordlistType::TEXT_IMAGE) {
            if (!$request->has('wordlist.words') || !$request->has('wordlist.files')) {
                $skipLoop = true;
            }
        } else if ($request->input('wordlist.type') == WordlistType::IMAGE) {
            if (!$request->has('wordlist.files')) {
                $skipLoop = true;
            }
        }

        $item = $this->model();

        $rules = $this->getRules($this->rules);
        $this->handleForm($request, $item, $rules);

        if (!$skipLoop) {
            if ($item->type == WordlistType::TEXT) {
                $words = $request->input('wordlist.words');
                $wordList = collect();
                foreach ($words as $word) {
                    if ($word != null && $word != '') {
                        $wordList->add($word);
                    }
                }
                $item->list = $wordList->toArray();
            }

            if ($item->type == WordlistType::TEXT_IMAGE) {
                $words = $request->input('wordlist.words');
                $wordList = collect();
                foreach ($words as $word) {
                    if ($word != null && $word != '') {
                        $wordList->add($word);
                    }
                }
                $images = $this->handleFileUpload($request, $wordList->toArray());
                $ids = $images->pluck('_id');
                $item->list = $ids->toArray();
            }

            if ($item->type == WordlistType::IMAGE) {
                $images = $this->handleFileUpload($request);
                $ids = $images->pluck('_id');
                $item->list = $ids->toArray();
            }
        }


        $item->save();
        $request->session()->flash('success', 'Werkblad: ' . $request->wordlist['name'] . ' is aangemaakt.');

        return redirect()->route('wordlists.index');
    }

    public function destroy(Request $request, $id)
    {
        $item = $this->model()->find($id);
        if ($item->delete()) {
            $request->session()->flash('success', 'Werkblad: ' . $item->name . ' is verwijderd.');
        } else {
            $request->session()->flash('error', 'Werkblad: ' . $item->name . ' kon niet verwijderd worden.');
        }

        return redirect()->route('wordlists.index');
    }

    public function getImages($wordlist_id)
    {
        $item = Wordlist::find($wordlist_id);
        $list = null;
        if ($item->type == WordlistType::TEXT) {
            $list = $item->list;
        } else if ($item->type == WordlistType::TEXT_IMAGE) {
            $list = $item->images;
        } else if ($item->type == WordlistType::IMAGE) {
            $list = $item->images;
        }
        return $list;
    }
}
