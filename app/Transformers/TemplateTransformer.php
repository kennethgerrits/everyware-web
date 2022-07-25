<?php

namespace App\Transformers;

use App\Enums\WordlistType;
use App\Models\Image;
use App\Models\Template;
use App\Models\Worksheet;
use League\Fractal\TransformerAbstract;

class TemplateTransformer extends TransformerAbstract
{
    public function transform(Template $template)
    {
        $words = [];
        if($template->all){
            if($wordlist = $template->wordlist){
                if(!$wordlist->list){
                    $words = [
                        'id' => $wordlist->id,
                        'words' => $words
                    ];
                }

                if($wordlist->type == WordlistType::TEXT){
                    foreach ($wordlist->list as $word){
                        $words[] = ['name' => $word, 'url' => null ];
                    }
                }else{
                    $images = Image::findMany($wordlist->list);
                    foreach ($images as $image){
                        $words[] = ['name' => $image->related_to ?: null, 'url' => $image->src ];
                    }
                }
            }
        }

        return [
            'id' => $template->id,
            'name' => $template->name,
            'category' => $template->category->name,
            'wordlist_id' => $template->wordlist_id ?: "",
            'words' => $words,
            'welcome_message' =>  $template->welcome_message,
            'question_type' => $template->question_type ?: "",
            'answer_type' => $template->answer_type ?: "",
            'question_amount' => $template->question_amount,
            'min_amount' => $template->min_amount ?: 0,
            'max_amount' => $template->max_amount ?: 0,
            'sum_type' => $template->sum_type ?: "",
            'reward' => $template->reward ?: '',
            'difficulty' => $template->difficulty_id,
            'is_available' => $template->is_available,
            'is_math' => $template->is_math,
            'is_collection' => $template->is_collection ? true : false,
            'is_new' => $template->is_new,
            'template_ids' => $template->templates ? implode(",",$template->templates) : "",
            'image' => $template->image ? $template->image->src : ''
        ];
    }
}
