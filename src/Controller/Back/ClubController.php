<?php

namespace App\Controller\Back;

use App\Entity\Club;
use App\Form\Back\ClubType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/club', name: 'club_')]
class ClubController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, ClubRepository $clubRepository): Response
    {
        return $this->render('back/club/index.html.twig', [
            'clubs' => $clubRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClubRepository $clubRepository): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->get('manager')->getData();
            $user->setRoles(['ROLE_GERANT']);

            $club = new Club();
            $club->setName($form->get('name')->getData());
            $club->setDescription($form->get('description')->getData());
            $club->setAdresse($form->get('adresse')->getData());

            $user->addClub($club);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('back_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/club/new.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Club $club): Response
    {
        return $this->render('back/club/show.html.twig', [
            'club' => $club,
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->get('manager')->getData();
            $user->setRoles(['ROLE_GERANT']);

            $club = $form->getData();
            $club->setManager($user);
            $clubRepository->save($club, true);

            return $this->redirectToRoute('back_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $club->getId(), $request->request->get('_token'))) {
            $clubRepository->remove($club, true);
        }

        return $this->redirectToRoute('back_club_index', [], Response::HTTP_SEE_OTHER);
    }

}