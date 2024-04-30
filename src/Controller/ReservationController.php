<?php

namespace App\Controller;

use App\Entity\Centre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/Reservation', name: 'app_Reservation')]
    public function affiche(): Response
    {
        $centre=$this->getDoctrine()->getRepository(Centre::class)->findAll();
        return $this->render('Home/Reservation.html.twig',['c'=>$centre]);
    }
    
}
