<?php

namespace App\Form;

use App\Entity\Hike;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class HikeType extends AbstractType
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => $this->translator->trans('hike.label.name'),
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'label' => $this->translator->trans('hike.label.description'),
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('file', FileType::class, [
                'label' => $this->translator->trans('hike.label.image'),
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                ],

            ])
            ->add('distance', NumberType::class, [
                'label' => $this->translator->trans('hike.label.distance_form'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 100,
                ],
            ])
            ->add('duration', TimeType::class, [
                'label' => $this->translator->trans('hike.label.duration_form'),
                'attr' => [
                    'class' => 'time-select',
                ],
                'required' => true,
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('effort', IntegerType::class, [
                'label' => $this->translator->trans('hike.label.difficulty_form'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 10,
                ],
            ])
            ->add('max_users', IntegerType::class, [
                'label' => $this->translator->trans('hike.label.capacity'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 40,
                ],
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hike::class,
        ]);
    }
}
