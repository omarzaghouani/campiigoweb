<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function getLocationByIP(): JsonResponse
    {
        // Effectuer la requête vers l'API ipapi.co/json/
        $url = 'https://ipapi.co/json/';
        $response = file_get_contents($url);

        // Vérifier si la réponse est valide
        if ($response === false) {
            return $this->json(['error' => 'Erreur lors de la récupération de la localisation par IP'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Convertir la réponse JSON en tableau associatif
        $data = json_decode($response, true);

        // Vérifier si les données sont valides
        if (isset($data['city']) && isset($data['country'])) {
            $location = [
                'city' => $data['city'],
                'country' => $data['country']
            ];
            return $this->json($location);
        } else {
            return $this->json(['error' => 'Erreur lors de la récupération de la localisation par IP'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
