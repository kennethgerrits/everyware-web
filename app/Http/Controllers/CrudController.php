<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model;

abstract class CrudController extends BaseController
{
    protected $indexView = 'admin::crud.index';
    protected $editView = 'admin::crud.edit';
    protected $rules = [];

    /**
     * Create a new model instance
     *
     * @return \Eloquent
     */
    abstract protected function model();

    /**
     * Form used for editing and validation
     *
     * @param  Model  $item
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function getForm($item);

    /**
     * Create new query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function queryBase() {
        return $this->model()->newQuery();
    }

    /**
     * Handles a form submission
     *
     * @param  Request  $request
     * @param  mixed  $item
     * @param  array  $rules
     * @return \Response
     */
    protected function handleForm(Request $request, $item, $rules=[])
    {
        $form = $this->getForm($item);

        $this->validateForm($form, $request, $rules);

        $form->handleRequest($request);

        $item->save();
    }
}
