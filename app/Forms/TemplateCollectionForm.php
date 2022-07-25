<?php

namespace App\Forms;

use App\Enums\AnswerType;
use App\Enums\QuestionType;
use App\Enums\SumType;
use App\Models\Category;
use App\Models\Classroom;
use App\Models\Tag;
use App\Models\Wordlist;
use App\Models\Difficulty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TemplateCollectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $builder
            ->add('name', TextType::class, [
                'label' => 'Naam',
                'required' => false
            ])
            ->add('category_id', ChoiceType::class, [
                'label' => 'Category',
                'required' => false,
                'placeholder' => 'Selecteren..',
                'choices' => Category::pluck('_id', 'name')
            ])
            ->add('welcome_message', TextType::class, [
                'label' => 'Welkom bericht',
                'required' => false,
            ])
            ->add('tags', ChoiceType::class, [
                'label' => 'Tags',
                'placeholder' => 'Selecteren..',
                'required' => false,
                'attr' => [
                    'class' => 'js-select2-tags input-field',
                ],
                'mapped' => true,
                'multiple' => true,
                'choices' => Tag::pluck('_id', 'name'),
                'data' => $data->tags,
            ])
            ->add('difficulties', ChoiceType::class, [
                'label' => "Niveau's",
                'placeholder' => 'Selecteren..',
                'required' => true,
                'attr' => [
                    'class' => 'js-select2-difficulties',
                ],
                'mapped' => true,
                'multiple' => true,
                'choices' => Difficulty::pluck('_id', 'name'),
                'data' => $data->difficulties,
            ])
            ->add('is_available', CheckboxType::class, [
                'label' => 'Beschikbaar',
                'required' => false,
                'data' => $data->is_available,
            ])
            ->add('question_amount', NumberType::class, [
                'label' => 'Aantal vragen',
                'required' => false,
            ])
            ->add('reward', TextType::class, [
                'label' => 'Beloning',
                'required' => false
            ])
            ->add('cesuur', NumberType::class, [
                'label' => 'Cesuur',
                'required' => false
            ]);

        if ($data->id) {
            $builder->setAction("/template-collections/" . $data->id);
            $builder->setMethod("PUT");
        } else {
            $builder->setAction("/template-collections");
        }
    }
}
