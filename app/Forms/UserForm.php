<?php

namespace App\Forms;

use App\Enums\Role;
use App\Models\ClassGroup;
use App\Models\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $builder
            ->add('first_name', TextType::class, [
                'label' => __('user.firstname') . ' *',
                'required' => false,
            ])
            ->add('last_name', TextType::class, [
                'label' => __('user.lastname') . ' *',
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail *',
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'label' => __('user.password') . ($data->id ? "" : " *"),
                'required' => false,
            ])
            ->add('password_confirmation', PasswordType::class, [
                'label' => __('user.password_repeat') . ($data->id ? "" : " *"),
                'required' => false,
                'mapped' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => __('user.roles') . ' *',
                'placeholder' => 'Selecteren..',
                'required' => false,
                'attr' => [
                  'class' => 'js-select2'
                ],
                'mapped' => true,
                'multiple' => true,
                'choices' => Role::VALUES,
                'data' => $data->roles,
            ])
            ->add('studentgroup_ids', ChoiceType::class, [
                'label' => 'Klas',
                'placeholder' => 'Selecteren..',
                'required' => false,
                'attr' => [
                    'class' => 'js-select2'
                ],
                'mapped' => true,
                'multiple' => true,
                'choices' => ClassGroup::pluck('_id', 'name'),
                'data' => $data->studentgroup_ids,
            ])
            ->add('departments', ChoiceType::class, [
                'label' => __('user.departments'). ' *',
                'placeholder' => 'Selecteren..',
                'required' => false,
                'attr' => [
                    'class' => 'js-select2-departments',
                ],
                'mapped' => true,
                'multiple' => true,
                'choices' => Department::pluck('_id', 'name'),
                'data' => $data->departments,
            ]);

        if ($data->id) {
            $builder->setAction("/users/" . $data->id);
            $builder->setMethod("PUT");
        } else {
            $builder->setAction("/users");
        }
    }
}
