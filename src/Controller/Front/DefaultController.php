<?php

namespace App\Controller\Front;

use App\Repository\HikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct()
    {

    }

    #[Route('/', name: 'default_index')]
    public function index(HikeRepository $hikeRepository): Response
    {
        $hikes = $hikeRepository->findAll();

        $hikesWithAvailability = [];

        foreach ($hikes as $hike) {
            $availablePlaces = $hike->getMaxUsers() - count($hike->getBookings());
            $hikesWithAvailability[] = ['hike' => $hike, 'availablePlaces' => $availablePlaces];
        }

        return $this->render('front/default/index.html.twig', [
            'hikes' => $hikes,
            'hikesWithAvailability' => $hikesWithAvailability,
        ]);
    }

}
