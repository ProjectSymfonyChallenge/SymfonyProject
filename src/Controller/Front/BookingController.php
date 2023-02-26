<?php

namespace App\Controller\Front;

use Dompdf\Dompdf;
use App\Entity\Hike;
use App\Entity\Booking;
use App\Entity\Payment;
use App\Form\BookingType;
use App\Repository\HikeRepository;
use App\Repository\BookingRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
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

    #[Route('/{id}/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(Booking $booking): Response
    {
        if ($this->getUser() !== $booking->getUser()) {
            return $this->redirectToRoute('front_default_index');
        }

        $hike = $booking->getHike();
        $payment = $booking->getPayment();

        $dompdf = new Dompdf();

        $html = $this->renderView('pdf/booking.html.twig', [
            'hike' => $hike,
            'booking' => $booking,
            'payment' => $payment,
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("booking.pdf", [
            "Attachment" => false
        ]);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }

    #[Route('/new/{slug}', name: 'new', methods: ['GET', 'POST'])]
    #[ParamConverter('hike', options: ['mapping' => ['slug' => 'slug']])]
    public function new(Hike $hike, Request $request, HikeRepository $hikeRepository): Response
    {
        Stripe::setApiKey($_ENV["STRIPE_SECRET_KEY"]);

        $booking = new Booking();

        $date = $hike->getDuration();
        $hours = $date->getTimestamp() / 3600;
        $price = round($hours * 15);

        $stripe = new \Stripe\StripeClient($_ENV["STRIPE_SECRET_KEY"]);
        $token = $stripe->tokens->create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 12,
                'exp_year' => 2025,
                'cvc' => '123',
            ],
        ]);

        $form = $this->createForm(BookingType::class, $booking, [
        'stripeToken' => $token->id,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $stripeToken = $form->get('stripeToken')->getData();
            $user = $this->getUser();
            if (!$user->getFirstname() || !$user->getLastname()) {
                $this->addFlash('warning', $this->translator->trans('booking.label.no_identity'));
                return $this->redirectToRoute('front_user_show', ['slug' => $user->getSlug()]);
            }

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

            $charge = Charge::create([
                'amount' => $price * 100, // convert to cents
                'currency' => 'eur',
                'source' => $stripeToken,
                'description' => 'Booking payment',
            ]);
            $booking->setHike($hike);
            $booking->setUser($this->getUser());
            $booking->setHikeDate($hikeDate);

            $payment = new Payment();
            $payment->setPrice($price);
            $payment->setBooking($booking);

            $this->paymentRepository->save($payment, false);

            $booking->setPayment($payment);

            $this->bookingRepository->save($booking, true);

            return $this->redirectToRoute('front_booking_show',
                [
                    'id' => $booking->getId(),
                    'stripe_public_key' => $_ENV["STRIPE_PUBLIC_KEY"],
                ], Response::HTTP_SEE_OTHER);

        }
        
        return $this->renderForm('front/booking/new.html.twig', [
            'booking' => $booking,
            'price' => $price,
            'hike' => $hike,
            'form' => $form,
        ]);

    }
}
