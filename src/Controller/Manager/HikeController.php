<?php

namespace App\Controller\Manager;

use App\Entity\Hike;
use App\Form\HikeType;
use App\Repository\HikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/hike', name: 'hike_')]
class HikeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(HikeRepository $hikeRepository): Response
    {
        $hikes = $hikeRepository->findAllByUserClub($this->getUser());
        return $this->render('manager/hike/index.html.twig', [
            'hikes' => $hikes,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, HikeRepository $hikeRepository): Response
    {   
        $hike = new Hike();
        $form = $this->createForm(HikeType::class, $hike);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($this->getUser()->getClubs()->first());
            $hike->setClub($this->getUser()->getClubs()->first());
            dd($hike);
            $hikeRepository->save($hike, true);

            return $this->redirectToRoute('manager_hike_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/hike/new.html.twig', [
            'hike' => $hike,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Hike $hike): Response
    {
        // get Average duration
        $evaluations = $hike->getEvaluations();
        $sum = 0;
        foreach ($evaluations as $evaluation) {
            $sum += $evaluation->getDuration()->getTimestamp();
        }
        if (count($evaluations) == 0) {
            $avgDate = 0;
        } else {
            $averageDuration = $sum / count($evaluations);
            $avgDate = new \DateTime("@".$averageDuration);
        }

        return $this->render('manager/hike/show.html.twig', [
            'hike' => $hike,
            'averageDuration' => $avgDate,
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hike $hike, HikeRepository $hikeRepository): Response
    {
        $form = $this->createForm(HikeType::class, $hike);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hikeRepository->save($hike, true);

            return $this->redirectToRoute('manager_hike_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/hike/edit.html.twig', [
            'hike' => $hike,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Hike $hike, HikeRepository $hikeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hike->getId(), $request->request->get('_token'))) {
            $hikeRepository->remove($hike, true);
        }

        return $this->redirectToRoute('manager_hike_index', [], Response::HTTP_SEE_OTHER);
    }
}
