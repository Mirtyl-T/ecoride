<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Menu;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index() : Response
    {
        return $this->render('security/index.html.twig');

    }
}
