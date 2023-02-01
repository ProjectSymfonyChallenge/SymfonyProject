<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegisterType extends AbstractType
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
                'mapped' => true,
                'required' => true,
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
