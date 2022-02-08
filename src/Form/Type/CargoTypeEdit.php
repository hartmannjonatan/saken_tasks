<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CargoTypeEdit extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add(child:'nome')
            ->add('save', SubmitType::class, ['label' => 'Editar Cargo'])
        ;
    }


}