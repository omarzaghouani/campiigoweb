<?php

use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Activite;
use App\Entity\Centre;

require_once 'vendor/autoload.php'; // Assurez-vous d'ajuster le chemin selon votre structure de projet

// Fonction pour générer le PDF personnalisé
function generatePdf(Activite $activite)
{
    // Récupération des données de l'activité
    $nomActivite = $activite->getNomActivite();
    $description = $activite->getDescription();
    $centre = $activite->getCentre();
    
    $type = $activite->getType();

    // Contenu HTML du PDF
    $htmlContent = "
        <html>
        <head>
            <title>Détails de l'activité - $nomActivite</title>
        </head>
        <body>
            <h1>Détails de l'activité</h1>
            <p><strong>Nom de l'activité:</strong> $centre</p>
            <p><strong>Nom de l'activité:</strong> $nomActivite</p>
            <p><strong>Description:</strong> $description</p>
            <p><strong>Type:</strong> $type</p>
        </body>
        </html>
    ";

// Création de l'instance Dompdf
$dompdf = new Dompdf();

// Définition des options de configuration

$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('isPhpEnabled', true);
$dompdf->set_option('isRemoteEnabled', true); // Autoriser le chargement d'images externes

// Chargement du contenu HTML et rendu
$dompdf->loadHtml($htmlContent);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Renvoi de la réponse HTTP avec le contenu du PDF
return new Response(
    $dompdf->output(),
    Response::HTTP_OK,
    ['Content-Type' => 'application/pdf']
);

}

// Utilisation de la fonction pour générer le PDF
$activite = new Activite();
$activite->setNomActivite('Nom de l\'activité');
$activite->setDescription('Description de l\'activité');
$activite->setType('Type d\'activité');

$response = generatePdf($activite);
$response->send();
