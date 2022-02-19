<?php

namespace App\Form\Type;

use App\Entity\Classificacao;
use App\Entity\Funcionario;
use App\Entity\Tipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('nome', TextType::class)
            ->add('final_date', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('descricao', TextareaType::class)
            ->add('cod_func', EntityType::class, [
                'class' => Funcionario::class,
                'choice_label' => 'nome'
            ])
            ->add('class', EntityType::class, [
                'mapped' => false,
                'class' => Classificacao::class,
                'choice_label' => 'nome'
            ])
            ->add('nomeClass', TextType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('idProjeto', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('tipo', EntityType::class, [
                'class' => Tipo::class,
                'choice_label' => 'nome'
            ])
            ->add('save', SubmitType::class, ['label' => 'Cadastrar Task'])
            ->setAction("/newTask")
        ;
    }

}