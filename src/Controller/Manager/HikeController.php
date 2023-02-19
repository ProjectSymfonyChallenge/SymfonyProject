<?php

namespace App\Controller\Manager;

use App\Entity\Hike;
use App\Form\HikeType;
use App\Entity\Picture;
use App\Repository\HikeRepository;
use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/hike', name: 'hike_')]
class HikeController extends AbstractController
{

    /**
     * @var HikeRepository
     */
    private HikeRepository $hikeRepository;

    /**
     * @var PictureRepository
     */
    private PictureRepository $pictureRepository;

    public function __construct(HikeRepository $hikeRepository, PictureRepository $pictureRepository)
    {
        $this->hikeRepository = $hikeRepository;
        $this->pictureRepository = $pictureRepository;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $hikes = $this->hikeRepository->findAllByUserClub($this->getUser());
        return $this->render('manager/hike/index.html.twig', [
            'hikes' => $hikes,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {   
        $hike = new Hike();
        $form = $this->createForm(HikeType::class, $hike);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('file')->getData();

            if ($files) {
                $hikeDirectory = $this->getParameter('hike_directory');
    
                foreach ($files as $file) {
                    $filename =  '/uploads/hikes/' . md5(uniqid()).'.'.$file->guessExtension();
    
                    $picture = (new Picture())
                        ->setFilename($filename)
                        ->setType('hike');
    
                    $this->pictureRepository->save($picture, false);
    
                    $file->move(
                        $hikeDirectory,
                        $filename
                    );
    
                    $hike->addPicture($picture);
                }
            }

            $hike->setClub($this->getUser()->getClubs()->first());
            $this->hikeRepository->save($hike, true);

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
    public function edit(Request $request, Hike $hike): Response
    {
        $form = $this->createForm(HikeType::class, $hike);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('file')->getData();

            if ($files) {
                $hikeDirectory = $this->getParameter('hike_directory');
    
                foreach ($files as $file) {
                    $filename =  '/uploads/hikes/' . md5(uniqid()).'.'.$file->guessExtension();
    
                    $picture = (new Picture())
                        ->setFilename($filename)
                        ->setType('hike');
    
                    $this->pictureRepository->save($picture, false);
    
                    $file->move(
                        $hikeDirectory,
                        $filename
                    );
    
                    $hike->addPicture($picture);
                }
            }

            $this->hikeRepository->save($hike, true);

            return $this->redirectToRoute('manager_hike_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/hike/edit.html.twig', [
            'hike' => $hike,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Hike $hike): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hike->getId(), $request->request->get('_token'))) {
            $this->hikeRepository->remove($hike, true);
        }

        return $this->redirectToRoute('manager_hike_index', [], Response::HTTP_SEE_OTHER);
    }
}
