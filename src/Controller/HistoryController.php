<?php

namespace App\Controller;

use App\Repository\ParticipationRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    #[Route('/mes-trajets', name: 'user_history')]
    public function index(ParticipationRepository $participationRepo, TripRepository $tripRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        // Historique passager (participations confirmées)
        $participations = $participationRepo->findBy(
            ['user' => $user, 'statut' => 'confirmé'],
            ['date_participation' => 'DESC']
        );

        // Historique chauffeur (trajets créés)
        $trips = $tripRepo->findBy(
            ['driver' => $user],
            ['date_depart' => 'DESC']
        );

        return $this->render('security/history.html.twig', [
            'participations' => $participations,
            'trips' => $trips,
        ]);
    }
}
