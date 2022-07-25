<?php
namespace App\Transformers;

use App\Enums\WordlistType;
use App\Models\Image;
use App\Models\Template;
use App\Models\Wordlist;
use League\Fractal\TransformerAbstract;

class WordlistTransformer extends TransformerAbstract
{
    public function transform(Wordlist $wordlist)
    {
        $words = [];
        if(!$wordlist->list){
            return [
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


        return [
            'id' => $wordlist->id,
            'words' => $words
        ];
    }
}
