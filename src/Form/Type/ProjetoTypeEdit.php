<?php

namespace App\Form\Type;

use App\Entity\Funcionario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjetoTypeEdit extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add(child:'id')
            ->add(child:'nome')
            ->add(child:'cliente')
            ->add(child:'descricao')
            ->add('coordenador', EntityType::class, [
                'class' => Funcionario::class,
                'choice_label' => 'nome'
            ])
            ->add('save', SubmitType::class, ['label' => 'Confirmar alterações'])
            ->setAction("/update")
        ;
    }

}