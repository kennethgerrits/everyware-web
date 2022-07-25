<?php

namespace App\Observers;

use App\Models\ClassGroup;
use App\Models\Template;
use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Support\Facades\Auth;

class TemplateObserver
{
    public function saved(Template $template)
    {

        if (Auth::check()) {
            $template->edited_by = auth()->user()->id;
            $template->saveQuietly();
        }
    }

    public function deleted(Template $template)
    {
        //Check if any classgroup has this template.
        $classGroups = ClassGroup::where('templates', 'all', [$template->id])->get();
        if ($classGroups->count()) {
            foreach ($classGroups as $classGroup) {
                $classGroup->pull('templates', $template->id);
            }
        }

        //Set deleted templates to null on worksheets
        Worksheet::where('template_id', $template->id)->update(['template_id' => null]);

        //Remove references in template collections
        $templates = Template::where('is_collection', true)->where('templates', 'all', [$template->id])->get();
        if ($templates->count()) {
            foreach ($templates as $t) {
                $t->pull('templates', $template->id);
            }
        }

        //Remove references from required_templates
        $templates = Template::where('required_templates', 'all', [$template->id])->get();
        if ($templates->count()) {
            foreach ($templates as $t) {
                $t->pull('required_templates', $template->id);
            }
        }
    }
}
