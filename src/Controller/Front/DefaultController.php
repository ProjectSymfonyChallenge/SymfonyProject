<?php

namespace App\Controller\Front;

use App\Repository\HikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private HikeRepository $hikeRepository;
    public function __construct(HikeRepository $hikeRepository)
    {
        $this->hikeRepository = $hikeRepository;
    }

    #[Route('/', name: 'default_index')]
    public function index(ParameterBagInterface $params): Response
    {
        $apiKey = $params->get('google_maps_api_key');
        $hikes = null;
        if ($this->getUser()){
            $hikes = $this->hikeRepository->findAll();
            $hikesWithAvailability = [];
            foreach ($hikes as $hike) {
                $availablePlaces = $hike->getMaxUsers() - count($hike->getBookings());
                $hikesWithAvailability[] = ['hike' => $hike, 'availablePlaces' => $availablePlaces];
            }
            return $this->render('front/default/index.html.twig', [
                'hikes' => $hikes,
                'hikesWithAvailability' => $hikesWithAvailability,
                'google_maps_api_key' => $apiKey
            ]);
        }else{
            return $this->render('front/default/index.html.twig');
        }
    }

}
