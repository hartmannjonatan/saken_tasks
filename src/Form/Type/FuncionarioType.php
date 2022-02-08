<?php

namespace App\Form\Type;

use App\Entity\Cargo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class FuncionarioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('nome', TextType::class)
            ->add('cpf', TextType::class, [
                'attr' => ['data-mask' => '000.000.000-00']
            ])
            ->add('data_nasc', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('cod_cargo', EntityType::class, [
                'class' => Cargo::class,
                'choice_label' => 'nome'
            ])
            ->add('save', SubmitType::class, ['label' => 'Cadastrar funcionário'])
        ;
    }

}