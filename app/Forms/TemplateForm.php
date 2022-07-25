<?php

namespace App\Forms;

use App\Enums\AnswerType;
use App\Enums\QuestionType;
use App\Enums\SumType;
use App\Models\Wordlist;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Difficulty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TemplateForm extends AbstractType
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
                'required' => false,
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
            ->add('question_type', ChoiceType::class, [
                'label' => 'Type vragen',
                'required' => false,
                'placeholder' => 'Selecteren..',
                'choices' => QuestionType::VALUES,
                'data' => $data->question_type
            ])
            ->add('answer_type', ChoiceType::class, [
                'label' => 'Type beantwoording',
                'required' => false,
                'placeholder' => 'Selecteren..',
                'choices' => AnswerType::VALUES,
                'data' => $data->answer_type
            ])
            ->add('question_amount', NumberType::class, [
                'label' => 'Aantal vragen',
                'required' => false,
            ])
            ->add('cesuur', NumberType::class, [
                'label' => 'Cesuur',
                'required' => false,
            ])
            ->add('reward', TextType::class, [
                'label' => 'Beloning',
                'required' => false,
            ])
            ->add('min_amount', NumberType::class, [
                'label' => 'Minimaal aantal *',
                'required' => false
            ])
            ->add('max_amount', NumberType::class, [
                'label' => 'Maximaal aantal *',
                'required' => false
            ])
            ->add('sum_type', ChoiceType::class, [
                'label' => 'Soort berekening *',
                'placeholder' => 'Selecteren..',
                'required' => false,
                'data' => $data->sum_type,
                'choices' => SumType::VALUES,
            ])
            ->add('wordlist_id', ChoiceType::class, [
                'label' => 'Woordenlijst',
                'placeholder' => 'Selecteren..',
                'required' => false,
                'data' => $data->wordlist_id,
                'choices' => Wordlist::pluck('_id', 'name'),
            ]);

        if ($data->id) {
            $builder->setAction("/templates/" . $data->id);
            $builder->setMethod("PUT");
        } else {
            $builder->setAction("/templates");
        }
    }
}
