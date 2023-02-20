<?php

namespace App\Form\Front;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'form.label.email',
                'help' => 'form.help.email',
                'attr' => [],
                'mapped' => true,
                'required' => true,
            ])
            ->add('firstname', TextType::class,[
                'label' => 'form.label.firstname',
                'help' => 'form.help.firstname',
                'attr' => [],
                'mapped' => true,
                'required' => false,
            ])
            ->add('lastname', TextType::class,[
                'label' => 'form.label.lastname',
                'help' => 'form.help.lastname',
                'attr' => [],
                'mapped' => true,
                'required' => false,
            ])
            ->add('username', TextType::class,[
                'label' => 'form.label.username',
                'help' => 'form.help.username',
                'attr' => [],
                'mapped' => true,
                'required' => true,
            ])

            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'form.label.password'],
                'second_options' => ['label' => 'form.label.cpassword'],
                'help' => 'form.help.password',
                'form_attr' => true,
                'attr' => ['class' => 'flex flex-col'],
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}