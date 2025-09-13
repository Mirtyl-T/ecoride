<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\User;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Form\SearchTripType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/trip', name: 'trip_list')]
    public function list(Request $request, TripRepository $repo): Response
    {
        $form = $this->createForm(SearchTripType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        $trips = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trips = $repo->searchTrips(
                $data['villeDepart'] ?? null,
                $data['villeArrivee'] ?? null,
                $data['dateDepart'] ?? null,
                $data['prixMax'] ?? null,
                $data['placesMin'] ?? null,
                $data['fuelType'] ?? null, 
                $data['preferences'] ?? null 
            );
        }

        return $this->render('security/list.html.twig', [
            'form' => $form->createView(),
            'trips' => $trips,
            'searchPerformed' => $form->isSubmitted()
        ]);
    }

    #[Route('/trip/{id}', name: 'trip_show')]
    public function show(int $id, TripRepository $tripRepo): Response
    {
        $trip = $tripRepo->find($id);

        if (!$trip) {
            throw $this->createNotFoundException('Trajet non trouvé');
        }

        return $this->render('security/show.html.twig', [
            'trip' => $trip,
        ]);
    }

}
