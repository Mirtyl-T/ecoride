<?php

namespace App\Controller;

<<<<<<< HEAD
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
=======
use App\Document\Review;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
>>>>>>> ancien-master

#[IsGranted('ROLE_EMPLOYE')]
class EmployeController extends AbstractController
{
<<<<<<< HEAD
    #[Route('/employe', name: 'app_review')]
    public function review(EntityManagerInterface $em): Response
    {

        $review = $em->getRepository(Review::class)->findBy(['etat' => 'en_attente']);

        return $this->render('security/employe.html.twig', [
            'review' => $review,
        ]);

        $incidents = $em->getRepository(Review::class)->findBy([
=======
    #[Route('/employe', name: 'app_employe_dashboard')]
    public function dashboard(DocumentManager $dm): Response
    {
        // Avis en attente
        $reviews = $dm->getRepository(Review::class)->findBy(['etat' => 'en_attente']);

        // Incidents en attente
        $incidents = $dm->getRepository(Review::class)->findBy([
>>>>>>> ancien-master
            'etat' => 'en_attente',
            'isIncident' => true,
        ]);

<<<<<<< HEAD
        return $this->render('security/employe.html.twig', [
            'review' => $reviews,
=======
        return $this->render('employe/dashboard.html.twig', [
            'reviews' => $reviews,
>>>>>>> ancien-master
            'incidents' => $incidents,
        ]);
    }

<<<<<<< HEAD
    #[Route('/employe/{id}/valider', name: 'app_review_valider')]
    public function valider(review $review, EntityManagerInterface $em): Response
    {
        $review->setEtat('valide');
        $em->flush();

        $this->addFlash('success', 'Avis validé.');
        return $this->redirectToRoute('app_review');
    }

    #[Route('/employe/{id}/refuser', name: 'app_review_refuser')]
    public function refuser(review $review, EntityManagerInterface $em): Response
    {
        $review->setEtat('refuse');
        $em->flush();

        $this->addFlash('danger', 'Avis refusé.');
        return $this->redirectToRoute('app_review');
    }

}

 
=======
    #[Route('/employe/review/{id}/valider', name: 'app_review_valider')]
    public function validerReview(string $id, DocumentManager $dm): Response
    {
        $review = $dm->getRepository(Review::class)->find($id);
        if (!$review) {
            throw $this->createNotFoundException('Avis introuvable.');
        }

        $review->setEtat('valide');
        $dm->flush();

        $this->addFlash('success', 'Avis validé avec succès.');
        return $this->redirectToRoute('app_employe_dashboard');
    }

    #[Route('/employe/review/{id}/refuser', name: 'app_review_refuser')]
    public function refuserReview(string $id, DocumentManager $dm): Response
    {
        $review = $dm->getRepository(Review::class)->find($id);
        if (!$review) {
            throw $this->createNotFoundException('Avis introuvable.');
        }

        $review->setEtat('refuse');
        $dm->flush();

        $this->addFlash('danger', 'Avis refusé.');
        return $this->redirectToRoute('app_employe_dashboard');
    }

    #[Route('/employe/incident/{id}/traiter', name: 'app_incident_traiter')]
    public function traiterIncident(string $id, DocumentManager $dm): Response
    {
        $incident = $dm->getRepository(Review::class)->find($id);
        if (!$incident) {
            throw $this->createNotFoundException('Incident introuvable.');
        }

        $incident->setEtat('traite');
        $dm->flush();

        $this->addFlash('success', 'Incident marqué comme traité.');
        return $this->redirectToRoute('app_employe_dashboard');
    }
}
>>>>>>> ancien-master
