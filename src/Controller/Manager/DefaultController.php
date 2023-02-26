<?php

namespace App\Controller\Manager;

use App\Entity\User;
use App\Entity\Club;
use App\Repository\MembershipRepository;
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
    private MembershipRepository $MembershipRepository;
    private UserRepository $userRepository;

    public function __construct(
        Emailing $emailing,
        EntityManagerInterface $entityManager,
        MembershipRepository $MembershipRepository,
        UserRepository $userRepository
    )
    {
        $this->emailing = $emailing;
        $this->entityManager = $entityManager;
        $this->MembershipRepository = $MembershipRepository;
        $this->userRepository = $userRepository;
    }


    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $ManagerId = $this->getUser()->getId();
        $clubId = $this->getUser()->getClubs()->first()->getId();
        $vipusers = $this->userRepository->findUserByMembershipId($clubId, $ManagerId);
        return $this->render('manager/default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $userRepository->findAll(),
            'vipusers' => $vipusers,


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

        return $this->redirectToRoute('manager_default_index',['success' => $userEmail]);

        } else {
            return $this->redirectToRoute('manager_default_index');
      }

    }

    //delete guide from club
    #[Route(path:'/delete_guide/{id}', name: 'delete_guide', methods: ['GET', 'POST'])]
    public function delete_Guide(Request $request, UserRepository $userRepository, $id): Response
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('The user does not exist');
        }

        $ManagerId = $this->getUser()->getId();
        $clubId = $this->getUser()->getClubs()->first()->getId();
        $vipusers = $this->userRepository->findUserByMembershipId($clubId, $ManagerId);
        //compare $user with vipusers
        $membership = $this->MembershipRepository->findOneBy(['user' => $user, 'club' => $this->getUser()->getClubs()->first()]);
        if (!$membership) {
            return $this->redirectToRoute('manager_default_index');
        }

        $this->entityManager->remove($membership);
        $this->entityManager->flush();

        return $this->redirectToRoute('manager_default_index');
    }

}


