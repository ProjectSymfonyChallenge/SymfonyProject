<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\Front\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/', name: 'user_')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translation;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translation

    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->translation = $translation;
    }

    #[Route('show', name: 'show', methods: ['GET', 'POST'])]
    public function show(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        dd($request->request->get('user'), $request);
        $userPwd = $request->request->get('password');
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')){
            if (!empty($userPwd['first']) && $userPwd['first'] === $userPwd['second']){
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $userPwd['first']
                );
                $user->setPassword($hashedPassword);
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translation-> trans("form.success"));
            return $this->render('front/user/show.html.twig', ['user' => $user, 'form' => $form->createView()]);
        }
        return $this->render('front/user/show.html.twig', ['user' => $user, 'form' => $form->createView()]);

}