<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname', null, [
                'required' => false,
            ])
            ->add('lastname', null, [
                'required' => false,
            ])
            ->add('username')
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Guide' => 'ROLE_GUIDE',
                    'GÃ©rant' => 'ROLE_GERANT',
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => false,

            ])
            // Add a listener to the PRE_SET_DATA event to add the status field only on edit
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                // checks if the Product object is "new"
                // If no data is passed to the form, the data is "null".
                // This should be considered a new "Product"
                if ($user->getId() !== null && in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                    $form->add('status', CheckboxType::class, [
                        'label' => 'Actif',
                        'required' => false,
                    ]);
                }
            })
            ;

        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}