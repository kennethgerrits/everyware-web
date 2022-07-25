<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Carbon;

class LearningMaterialController extends Controller
{
    public function index()
    {
        $view = view('learning-material');
        $templates = Template::normal()->get();
        $collections = Template::collection()->get();
        $categories = Category::orderby('type')->get();

        $view->templates = $templates;
        $view->collections = $collections;
        $view->categories = $categories;

        return $view;
    }

    public function getCategories(){
        $categories = Category::all();
        $categories = $categories->sortBy('type');
        $categories = $categories->map(function ($item){
            return ['id' => $item->id, 'name' => $item->name, 'url' => route('category.edit', ['category' => $item->id]), 'script' => asset('js/category.js')];
        });
        return $categories;
    }

    public function getTemplates(){
        $templates = Template::normal()->get();
        $templates = $templates->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'text_tags' => $item->text_tags,
                'is_available' => $item->is_available,
                'updated_at' => Carbon::parse($item->updated_at)->format("Y-m-d h:i:s"),
                'editedBy' => $item->editedBy ? $item->editedBy->name : "",
                'url' => route('templates.edit', ['template' => $item->id]),
                'script' => asset('js/template.js')
            ];
        });
        return $templates;
    }

    public function getTemplateCollections(){
        $templates = Template::collection()->get();
        $templates = $templates->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'text_tags' => $item->text_tags,
                'is_available' => $item->is_available,
                'updated_at' => Carbon::parse($item->updated_at)->format("Y-m-d h:i:s"),
                'editedBy' => $item->editedBy ? $item->editedBy->name : "",
                'url' => route('template-collections.edit', ['template_collection' => $item->id]),
                'script' => asset('js/template-collection.js')
            ];
        });
        return $templates;
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $tags = Tag::where('name', 'LIKE', '%' . $request->search . "%")->pluck('_id');
            $templates = $templates = Template::{$request->type}()->whereIn('tags', $tags)->get();


            if (empty($request->search)) {
                $templates = Template::{$request->type}()->get();
            }

            if ($templates) {
                foreach ($templates as $key => $template) {
                    $availability = '<i class="fa fa-check"></i>';
                    if (!$template->is_available) {
                        $availability = '<i class="fa fa-times"></i>';
                    }

                    $url = route("templates.edit", ["template" => $template->id]);
                    $script = asset('js/template.js');
                    if ($template->is_collection) {
                        $url = route("template-collections.edit", ["template_collection" => $template->id]);
                        $script = asset('js/template-collection.js');
                    }

                    $output .=
                        '<tr data-url="' . $url . '" data-script="'.$script.'">' .
                        '<td>' . $template->name . '</td>' .
                        '<td>' . $template->text_tags . '</td>' .
                        '<td>' . $availability . '</td>' .
                        '<td>' . $template->updated_at . '</td>' .
                        '<td>' . ($template->editedBy ? $template->editedBy->name : ""). '</td>' .
                        '</tr>';
                }
                return Response($output);
            }
        }
    }
}
