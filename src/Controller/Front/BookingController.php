<?php

namespace App\Controller\Front;

use App\Entity\Hike;
use App\Entity\Booking;
use App\Entity\Payment;
use App\Form\BookingType;
use App\Repository\HikeRepository;
use App\Repository\BookingRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/booking', name: 'booking_')]
class BookingController extends AbstractController
{
    private $bookingRepository;
    private $paymentRepository;
    private $translator;

    public function __construct(BookingRepository $bookingRepository, PaymentRepository $paymentRepository, TranslatorInterface $translator)
    {
        $this->bookingRepository = $bookingRepository;
        $this->paymentRepository = $paymentRepository;
        $this->translator = $translator;
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Booking $booking): Response
    {

        $hike = $booking->getHike();
        $payment = $booking->getPayment();


        return $this->render('front/booking/show.html.twig', [
            'booking' => $booking,
            'hike' => $hike,
            'payment' => $payment,
        ]);
    }

    #[Route('/new/{slug}', name: 'new', methods: ['GET', 'POST'])]
    #[ParamConverter('hike', options: ['mapping' => ['slug' => 'slug']])]
    public function new(Hike $hike, Request $request, HikeRepository $hikeRepository): Response
    {   
        $booking = new Booking();

        $date = $hike->getDuration();
        $hours = $date->getTimestamp() / 3600;
        $price = round($hours * 15);

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hikeDate = $form->get('hike_date')->getData();
            $hikeDate = New \DateTime($hikeDate);

            $bookable = true;

            if ($this->getUser()->getBookings()->count() > 0) {
                foreach ($this->getUser()->getBookings() as $booking) {
                    if ($booking->getHikeDate() == $hikeDate) {
                        $bookable = false;
                        break;
                    }
                }
                if ($bookable == false) {
                    $this->addFlash('danger', $this->translator->trans('booking.label.already_booked'));
                    return $this->redirectToRoute('front_booking_new', ['slug' => $hike->getSlug()], Response::HTTP_SEE_OTHER);
                }
            }

            $booking->setHike($hike);
            $booking->setUser($this->getUser());
            $booking->setHikeDate($hikeDate);

            $payment = new Payment();
            $payment->setPrice($price);
            $payment->setBooking($booking);

            $this->paymentRepository->save($payment, false);

            $booking->setPayment($payment);

            $this->bookingRepository->save($booking, true);

            return $this->redirectToRoute('front_booking_show', ['id' => $booking->getId()], Response::HTTP_SEE_OTHER);

        }
        
        return $this->renderForm('front/booking/new.html.twig', [
            'booking' => $booking,
            'price' => $price,
            'hike' => $hike,
            'form' => $form,
        ]);

    }
}
