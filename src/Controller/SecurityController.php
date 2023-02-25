<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\LevelRepository;
use App\Repository\UserRepository;
use App\Service\Emailing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private Emailing $emailing;
    private LevelRepository $levelRepository;
    private TranslatorInterface $translation;
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        LevelRepository $levelRepository,
        Emailing $emailing,
        TranslatorInterface $translation
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->levelRepository = $levelRepository;
        $this->emailing = $emailing;
        $this->translation = $translation;
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('post')){

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
            $bytes = bin2hex(random_bytes(32));
            $user->setToken($bytes);
            $level = $this->levelRepository->find(['id'=>1]);
            $user->setLevel($level);
            $user->setStatus(0);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $userEmail = $user->getEmail();
            $userToken = $user->getToken();
            $result = $this->emailing->sendEmailing([$userEmail], 1, $userToken);
            $this->addFlash('success', $this->translation-> trans("form.success"));
            return $this->redirectToRoute("app_login");
        }

        return $this->render("front/register.html.twig",[
            'form' => $form->createView(),
        ]);
    }
    #[Route(path: '/test', name: 'app_test')]
    public function test(): Response
    {
        return $this->render('security/test.html.twig');
    }
    #[Route('/validate', name: 'app_validate', methods: ['GET'])]
    public function validate(Request $request): Response
    {
        $tokenUser = $request->query->get('token');
        $emailUser = $request->query->get('email');

        if (!isset($token)){
            $user = $this->userRepository->findOneBy(['email' => $emailUser]);
            if ($user->getToken() == $tokenUser){
                $user->setStatus(1);
                $user->setToken(null);
                $this->entityManager->flush();
            }
        }
        return $this->redirectToRoute("app_login");
    }
}
