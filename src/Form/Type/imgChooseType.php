<?php

namespace App\Form\Type;

use App\Entity\Cargo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class imgChooseType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('img', type:FileType::class)
            ->add('id', type:HiddenType::class)
            ->add('save', SubmitType::class, ['label' => 'Confirmar'])
            ->setAction("/chooseImgCover")
        ;
    }

}