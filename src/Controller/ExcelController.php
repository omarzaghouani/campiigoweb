<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Entity\Activite;

class ExcelController extends AbstractController
{
    #[Route('/excel', name: 'app_excel')]
    public function generate(Request $request): Response
    {
        // Récupère les données depuis la base de données
        $activites = $this->getDoctrine()->getRepository(Activite::class)->findAll();

        // Crée une nouvelle instance de la classe Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Sélectionne la feuille active
        $sheet = $spreadsheet->getActiveSheet();

        // Charge le logo depuis le serveur
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logoCampigo2.jpg';
        $drawing = new Drawing();
        $drawing->setPath($logoPath);
        $drawing->setCoordinates('B1');
       
        

// Définit les dimensions du logo
$drawing->setWidth(250); // Largeur du logo en pixels
$drawing->setHeight(250); // Hauteur du logo en pixels

// Ajoute le logo à la feuille de calcul
$drawing->setWorksheet($sheet);

// Ajustement de la taille des colonnes
$sheet->getColumnDimension('B')->setWidth(20); // Ajuste la largeur de la colonne B
$sheet->getColumnDimension('C')->setWidth(20); // Ajuste la largeur de la colonne C
// Ajoute d'autres ajustements de taille de colonnes si nécessaire

// Ajustement de la taille des lignes
$sheet->getRowDimension(15)->setRowHeight(30);

        // Décalage pour la première ligne après le logo
        $rowOffset = 2;

        // Ajoute les en-têtes des colonnes
        $sheet->setCellValue('B15', 'Nom Activité');
        $sheet->setCellValue('C15', 'Description');
        $sheet->setCellValue('D15', 'Type');
        $sheet->setCellValue('E15', 'Centre');

        // Ajoute les données à la feuille de calcul
        $row = 15 + $rowOffset; // Commence à la ligne 2 après le logo
        foreach ($activites as $activite) {
            $sheet->setCellValue('B' . $row, $activite->getNomActivite());
            $sheet->setCellValue('C' . $row, $activite->getDescription());
            $sheet->setCellValue('D' . $row, $activite->getType());
            $sheet->setCellValue('E' . $row, $activite->getCentre()->getNomCentre()); // Supposons que le nom du centre soit dans une propriété 'nomCentre' de l'entité Centre
            // Ajoute d'autres colonnes si nécessaire
            $row++;
        }

        // Spécifie le nom du fichier Excel
        $fileName = 'activites.xlsx';

        // Sauvegarde le fichier Excel dans le répertoire d'export
        $exportPath = $this->getParameter('kernel.project_dir') . '/public/exports/' . $fileName;
        $writer = new Xlsx($spreadsheet);
        $writer->save($exportPath);

        // Répond à la requête avec un message de succès
        return new Response('Fichier Excel généré avec succès : <a href="/exports/' . $fileName . '">Télécharger</a>');
    }
}
