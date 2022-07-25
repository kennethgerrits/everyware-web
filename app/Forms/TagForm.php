<?php

namespace App\Forms;

use App\Models\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TagForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $builder
            ->add('name', TextType::class, [
                'label' => 'Naam'
            ]);

            if($data->id){
            $builder->setAction("/Tag/".$data->id);
            $builder->setMethod("PUT");
        }else{
            $builder->setAction("/Tags");
        }
    }
}