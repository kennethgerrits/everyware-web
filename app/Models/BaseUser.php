<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsToMany;

class BaseUser extends \Jenssegers\Mongodb\Auth\User
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
