<?php

namespace App\Forms;

use App\Enums\CategoryType;
use App\Models\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $builder
            ->add('name', TextType::class, [
                'label' => __('category.name_category') .'*',
                'required' => false,
                'data' => $data->name
            ])
            ->add('color', ColorType::class, [
                'label' => __('category.color') . '*',
                'required' => false,
                'data' => $data->color
            ]);

        if ($data->id) {
            $builder->setAction("/category/" . $data->id);
            $builder->setMethod("PUT");
        } else {
            $builder->setAction("/category");
        }
    }
}
