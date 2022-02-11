<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;

class UserTypeEditGerente extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add(child: 'username')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor digite uma senha',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Sua senha deve ter pelo menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('passwordAdmin', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor digite uma senha',
                    ])
                ],
            ])
            ->add('superadmin', ChoiceType::class, [
                'mapped' => false,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'Sim' => true,
                    'Não' => false
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Confirmar alteração'])
        ;
    }

}