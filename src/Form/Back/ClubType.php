<?php

namespace App\Form\Back;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du club',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom du club'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Quelques mots sur le club'
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('manager', TextType::class, [
                'label' => 'Responsable',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom du gérant'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
