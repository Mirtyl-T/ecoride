<?php

namespace App\Controller;

use App\Document\Review;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends AbstractController
{
    #[Route('/reviews', name: 'app_review')]
    public function index(DocumentManager $dm): Response
    {
        $reviews = $dm->getRepository(Review::class)->findAll();

        return $this->render('security/employe.html.twig', [
            'reviews' => $reviews,
        ]);
    }
}
