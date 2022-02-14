<?php

namespace App\Form\Type;

use App\Entity\Funcionario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjetoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('nome', TextType::class)
            ->add('cliente', TextType::class)
            ->add('descricao', TextareaType::class)
            ->add('coordenador', EntityType::class, [
                'class' => Funcionario::class,
                'choice_label' => 'nome'
            ])
            ->add('save', SubmitType::class, ['label' => 'Criar projeto'])
        ;
    }

}