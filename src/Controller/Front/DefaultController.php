<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct()
    {

    }

    #[Route('/', name: 'default_index')]
    public function index(): Response
    {

        return $this->render('front/default/index.html.twig');
    }

}
