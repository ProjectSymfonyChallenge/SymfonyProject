<?php

namespace App\Form\Back;

use App\Entity\Club;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
/*    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }*/

    public function buildForm(FormBuilderInterface $builder, array $options, $user = null): void
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
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'label' => 'club.label.manager',
                'choice_label' => function (User $user) {
                    return $user->getFirstname().' '.$user->getLastname(); // replace with your User entity's method to display in the select options
                },
                'placeholder' => 'Choisissez un gérant',
                'required' => false,
/*                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('SIZE(u.roles) = 0');
                },*/
            ]);

/*        $usersWithNoRoles = $this->userRepository->createQueryBuilder('u')
            ->where('SIZE(u.roles) = 0')
            ->getQuery()
            ->getResult();*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
