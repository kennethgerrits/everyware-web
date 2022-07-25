<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsToMany;

abstract class BaseModel extends Model
{
    use HasFactory;

    public function many(BelongsToMany $relation, $items, $opposingField){
        $relation->detach();

        if (!$items) {
            $this->unset($opposingField);
        } else {
            $this->unset($opposingField);
            $relation->attach($items);
        }
    }
}
