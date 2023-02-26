<?php

namespace App\Controller\Manager;

use App\Entity\User;
use App\Entity\Club;
use App\Service\Emailing;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private Emailing $emailing;
    private EntityManagerInterface $entityManager;
    public function __construct(
        Emailing $emailing,
        EntityManagerInterface $entityManager,
    )
    {
        $this->emailing = $emailing;
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        return $this->render('manager/default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $userRepository->findAll()

        ]);
    }
    #[Route(path:'/add_guide/{id}', name: 'add_guide', methods: ['GET', 'POST'])]
    public function add_Guide(Request $request, UserRepository $userRepository, $id): Response
    {
        $club = null;
        $managerId = $this->getUser()->getId();

        $club =$this->getUser()->getClubs()->first();

        if (!$club == null) {
        $user = $userRepository->find($id)  ;
        if (!$user) {
            throw $this->createNotFoundException('The user does not exist');
        }
        $bytes = bin2hex(random_bytes(32));
        $userEmail = $user->getEmail();
        $user = $user->setToken($bytes);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $userToken = $user->getToken();
        $result =$this->emailing->sendEmailing([$userEmail], 2, $userToken, $club);

        $this->addFlash('success', 'Invitation sent to '.$userEmail);
        return $this->redirectToRoute('manager_default_index',['success' => $userEmail]);

        } else {
            $this->addFlash('error', 'You are not associated with any club');
            return $this->redirectToRoute('manager_default_index');
      }

    }

    //delete guide from club
    #[Route(path:'/delete_guide/{id}', name: 'delete_guide', methods: ['GET', 'POST'])]
    public function delete_Guide(Request $request, UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);
        $memberships = $user->getMemberships();
        foreach ($memberships as $membership) {
            $this->entityManager->remove($membership);
        }
        $this->entityManager->flush();
        $this->addFlash('success', 'Guide deleted from club');
        return $this->redirectToRoute('manager_default_index');
    }
}


