<?php

namespace App\Forms;

use App\Enums\WordlistType;
use App\Forms\Types\WordlistItemType;
use App\Models\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class WordlistForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $builder
            ->add('name', TextType::class, [
                'label' => __('wordlist.name') . '*',
                'required' => false

            ])
            ->add('type', ChoiceType::class, [
                'label' => __('wordlist.list_type') . '*',
                'required' => false,
                'placeholder' => 'Selecteren..',
                'choices' => WordlistType::VALUES,
                'attr' => [
                    'class' => 'js-type input-field',
                ],
            ]);

        if ($data->id) {
            $builder->setAction("/wordlists/" . $data->id);
            $builder->setMethod("PUT");
        } else {
            $builder->setAction("/wordlists");
        }
    }
}
