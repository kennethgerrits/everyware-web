<?php

namespace App\Observers;

use App\Models\ClassGroup;
use App\Models\Template;
use App\Models\User;
use App\Models\Wordlist;
use App\Models\Worksheet;

class WordlistObserver
{
    public function deleted(Wordlist $wordlist)
    {
        //Remove any references to this wordlist.
        Template::where('wordlist_id', $wordlist->id)->unset('wordlist_id');
    }
}
