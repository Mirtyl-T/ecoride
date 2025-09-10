<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;

#[IsGranted('ROLE_EMPLOYE')]
class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_review')]
    public function review(EntityManagerInterface $em): Response
    {

        $review = $em->getRepository(Review::class)->findBy(['etat' => 'en_attente']);

        return $this->render('security/employe.html.twig', [
            'review' => $review,
        ]);

        $incidents = $em->getRepository(Review::class)->findBy([
            'etat' => 'en_attente',
            'isIncident' => true,
        ]);

        return $this->render('security/employe.html.twig', [
            'review' => $reviews,
            'incidents' => $incidents,
        ]);
    }

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

 