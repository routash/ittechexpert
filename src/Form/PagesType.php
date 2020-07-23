<?php

namespace App\Form;

use App\Entity\Pages;
use App\Entity\Programs;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('program', EntityType::class, [
                'class' => Programs::class,
                'allow_extra_fields' => true,
                'by_reference' => false,
                'mapped'=>false
            ])
            /*->add('program', CollectionType::class, [
                'label' => false,
                'entry_options' => array('label' => false),
                'entry_type' => ProgramsType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'prototype' => true,
                'by_reference' => false
        ])*/
//            ->add('program', CollectionType::class, [
//                'label'      => false,
//                'entry_type' => ProgramsType::class,
//                'allow_add'  => true,
//            ])
//                        ->add('defaultInsideColor', EntityType::class, [
//                        'class' => PMSColor::class,
//                        'label' => 'Default Inside Color',
//                        'attr' => ['class' => 'pms-color-search'],
//                        'choice_attr' => function (PMSColor $choice, $key, $value){
//                            return ['data-code' => $choice->getCode(), 'data-hexCode' => $choice->getHexCode()];
//                        },
//                    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pages::class,
        ]);
    }
}
