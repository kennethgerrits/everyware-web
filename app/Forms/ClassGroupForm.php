<?php

namespace App\Forms;

use App\Models\Classroom;
use App\Models\Template;
use App\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ClassGroupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $teachers = User::teacher()->get();
        $builder
            ->add('name', TextType::class, [
                'label' => 'Naam *',
                'required' => false,
            ])
            ->add('templates', ChoiceType::class, [
                'label' => 'Templates',
                'placeholder' => 'Selecteren..',
                'required' => false,
                'attr' => [
                    'class' => 'js-select2'
                ],
                'mapped' => true,
                'multiple' => true,
                'choices' => Template::pluck('_id', 'name'),
                'data' => $data->templates ?: [],
            ]);

        if ($data->id) {
            $builder->setAction("/class-groups/" . $data->id);
            $builder->setMethod("PUT");
        } else {
            $builder->setAction("/class-groups");
        }
    }
}
