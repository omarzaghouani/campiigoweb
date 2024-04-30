<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;

class Stat_UserController extends AbstractController
{
    #[Route('/user/stat', name: 'utilisateur_stat')]
    public function generateStatistics(UtilisateurRepository $centreRepository): Response
    {
        // Récupérer les statistiques des centres par ville
        $statistics = $centreRepository->getStatisticsByRole();

        // Formater les données pour Chart.js
        $labels = [];
        $data = [];
        foreach ($statistics as $statistic) {
            $labels[] = $statistic['role'];
            $data[] = $statistic['roleCount'];
        }

        // Convertir les données en format JSON pour le graphique
        $chartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        // Passer les données à la vue Twig
        return $this->render('user/stat_user.html.twig', [
            'chartData' => json_encode($chartData),
        ]);
    }
}

