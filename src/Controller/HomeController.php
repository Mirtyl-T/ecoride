<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Menu;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index() : Response
    {
        $user = $this->getUser(); 

        return $this->render('security/index.html.twig');

    }

}
