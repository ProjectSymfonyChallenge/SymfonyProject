<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
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
               'attr' => [
                   'class' => 'form-control',
               ],
               'mapped' => true,
               'required' => true,
           ])
            ->add('username', TextType::class,[
                'label' => 'form.label.username',
                'help' => 'form.help.username',
                'attr' => [
                    'class' => 'form-control',
                ],

                'mapped' => true,
                'required' => true,
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'form.label.password',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'second_options' => [
                    'label' => 'form.label.cpassword',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'help' => 'form.help.password',
                'form_attr' => true,
                'mapped' => true,
                'required' => true,
                'constraints' => [
                    new Callback([
                        'callback' => function ($password, ExecutionContextInterface $context) {
                            $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
                            if (!preg_match($regex, $password)) {
                                $context->buildViolation('Votre mot de passe doit comporter au moins 8 caractères et contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
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
