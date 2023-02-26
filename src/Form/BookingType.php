<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BookingType extends AbstractType
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];

        for ($i = 0; $i < 14; $i++) {
            $date = new \DateTime("+{$i} days");
            $value = $date->format('Y-m-d 11:00:00');
            $label = $date->format('d/m/Y à 11\h');
            $choices[$label] = $value;
        }

        $builder
        ->add('hike_date', ChoiceType::class, [
            'label' => $this->translator->trans('booking.label.hike_date'),
            'required' => true,
            'mapped' => false,
            'choices' => $choices,
        ])
        ->add('stripeToken', HiddenType::class, [
            'mapped' => false,
            'data' => $options['stripeToken'], // get the value of stripeToken option
            'attr' => [
                'type' => 'hidden', // make the input hidden
            ],
        ]);
    
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'stripeToken' => null, // add the stripeToken option with default value null
        ]);
    }
}
