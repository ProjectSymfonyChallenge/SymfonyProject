<?php

namespace App\Controller\Front;

use App\Entity\Hike;
use App\Repository\BookingRepository;
use App\Repository\HikeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/hike', name: 'hike_')]
class HikeController extends AbstractController
{

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Hike $hike, BookingRepository $bookingRepository): Response
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

        $bookable = true;
        
        $bookings = $bookingRepository->findBy(['hike' => $hike]);
        $nbBookings = count($bookings);

        if ($nbBookings >= $hike->getMaxUsers()) {
            $bookable = false;
        }

        return $this->render('front/hike/show.html.twig', [
            'hike' => $hike,
            'averageDuration' => $avgDate,
            'bookable' => $bookable,
        ]);
    }

}
