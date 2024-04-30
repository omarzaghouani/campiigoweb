<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Centre;
use App\Repository\CentreRepository;

class StatisticsController extends AbstractController
{
    #[Route('/centre/statistics', name: 'centre_statistics')]
    public function generateStatistics(CentreRepository $centreRepository): Response
    {
        // Récupérer les statistiques des centres par ville
        $statistics = $centreRepository->getStatisticsByCity();

        // Formater les données pour Chart.js
        $labels = [];
        $data = [];
        foreach ($statistics as $statistic) {
            $labels[] = $statistic['ville'];
            $data[] = $statistic['centreCount'];
        }

        // Convertir les données en format JSON pour le graphique
        $chartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        // Passer les données à la vue Twig
        return $this->render('centre/stat.html.twig', [
            'chartData' => json_encode($chartData),
        ]);
    }
}

