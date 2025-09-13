<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\Participation;
use App\Form\SearchTripType;
use App\Form\CreateTripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/trip/{id}', name: 'trip_show', requirements: ['id' => '\d+'])]
    public function show(Trip $trip): Response
    {
        return $this->render('security/show.html.twig', [
            'trip' => $trip,
        ]);
    }

    #[Route('/trip/new', name: 'trip_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAUFFEUR');

        $trip = new Trip();
        $form = $this->createForm(CreateTripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trip->setDriver($this->getUser());
            $em->persist($trip);
            $em->flush();

            $this->addFlash('success', 'Trajet créé avec succès !');
            return $this->redirectToRoute('trip_list');
        }

        return $this->render('security/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trip/{id}/reserve', name: 'trip_reserve')]
    public function reserve(Trip $trip, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        // Vérifier qu'il reste des places
        if ($trip->getPlacesDispo() <= 0) {
            $this->addFlash('danger', 'Plus de places disponibles.');
            return $this->redirectToRoute('trip_show', ['id' => $trip->getId()]);
        }

        // Vérifier que l'utilisateur n'est pas déjà inscrit
        foreach ($trip->getParticipations() as $p) {
            if ($p->getUser() === $user) {
                $this->addFlash('warning', 'Vous avez déjà réservé ce trajet.');
                return $this->redirectToRoute('trip_show', ['id' => $trip->getId()]);
            }
        }

        // Vérifier crédits utilisateur
        $prix = (int) $trip->getPrix();
        if ($user->getCredits() < $prix) {
            $this->addFlash('danger', 'Crédits insuffisants pour réserver.');
            return $this->redirectToRoute('trip_show', ['id' => $trip->getId()]);
        }

        // Créer la participation
        $participation = new Participation();
        $participation->setTrip($trip);
        $participation->setUser($user);
        $participation->setDateParticipation(new \DateTime());
        $participation->setStatut('confirmé');

        // Décrémenter les places
        $trip->setPlacesDispo($trip->getPlacesDispo() - 1);

        // Débiter le passager
        $user->removeCredits($prix);

        // Créditer le chauffeur
        $chauffeur = $trip->getDriver();
        $chauffeur->addCredits($prix);

        // Sauvegarder
        $em->persist($participation);
        $em->flush();

        $this->addFlash('success', 'Réservation effectuée avec succès !');
        return $this->redirectToRoute('trip_show', ['id' => $trip->getId()]);
    }
    
    #[Route('/trip/{id}/cancel', name: 'trip_cancel')]
    public function cancel(Trip $trip, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        // Trouver la participation de l’utilisateur pour ce trip
        $participation = null;
        foreach ($trip->getParticipations() as $p) {
            if ($p->getUser() === $user) {
                $participation = $p;
                break;
            }
        }

        if (!$participation) {
            $this->addFlash('danger', 'Vous n’êtes pas inscrit à ce trajet.');
            return $this->redirectToRoute('trip_show', ['id' => $trip->getId()]);
        }

        // Vérifier si le trajet n’a pas encore commencé
        if ($trip->getStatus() !== 'prévu') {
            $this->addFlash('danger', 'Impossible d’annuler, le trajet a déjà commencé.');
            return $this->redirectToRoute('trip_show', ['id' => $trip->getId()]);
        }

        // Rembourser les crédits
        $prix = (int) $trip->getPrix();
        $user->addCredits($prix);
        $trip->getDriver()->removeCredits($prix);

        // Supprimer la participation ou la marquer comme annulée
        $em->remove($participation);

        // Réattribuer la place
        $trip->setPlacesDispo($trip->getPlacesDispo() + 1);

        $em->flush();

        $this->addFlash('success', 'Votre réservation a été annulée.');
        return $this->redirectToRoute('trip_show', ['id' => $trip->getId()]);
    }

}
