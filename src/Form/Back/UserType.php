<?php

namespace App\Form\Back;

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
                'label' => 'Prénom'
            ])
            ->add('lastname', null, [
                'required' => false,
                'label' => 'Nom'
            ])
            ->add('username', null, [
                'label' => 'Pseudo'
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Guide' => 'ROLE_GUIDE',
                    'Gérant' => 'ROLE_GERANT',
                ],
            ])
                // Add a listener to the PRE_SET_DATA event to add the status field only on edit
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                if ($user->getId() !== null) {
                    $form->add('status', CheckboxType::class, [
                        'label' => 'Actif',
                        'required' => false,
                    ]);
                } else {
                    $form->add('password', PasswordType::class, [
                        'label' => 'Mot de passe'
                    ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
