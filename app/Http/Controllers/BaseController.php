<?php

namespace App\Http\Controllers;

use Barryvdh\Form\CreatesForms;
use Barryvdh\Form\ValidatesForms;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    use ValidatesForms, CreatesForms;

    protected function getRules($rules, $id = null)
    {
        if ($id === null) {
            return $rules;
        }

        array_walk($rules, function (&$rules, $field) use ($id) {
            if (!is_array($rules)) {
                $rules = explode("|", $rules);
            }

            foreach ($rules as $ruleIdx => $rule) {
                // get name and parameters
                @list($name, $params) = explode(":", $rule);

                // only do someting for the unique rule
                if (strtolower($name) != "unique") {
                    continue; // continue in foreach loop, nothing left to do here
                }

                $p = array_map("trim", explode(",", $params));

                // set field name to rules key ($field) (laravel convention)
                if (!isset($p[1])) {
                    $p[1] = $field;
                }

                // set 3rd parameter to id given to getValidationRules()
                $p[2] = $id;

                $params = implode(",", $p);
                $rules[$ruleIdx] = $name . ":" . $params;
            }
        });

        return $rules;
    }
}
