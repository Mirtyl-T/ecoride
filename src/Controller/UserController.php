<?php
namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{ 
#[Route('/account', name: 'app_account')]
public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
{
    $user = $this->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException();
    }

    $form = $this->createForm(UserProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        dump('Formulaire soumis'); // debug
        if ($form->isValid()) {
            dump('Formulaire valide'); // debug

            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $hasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profil mis à jour.');
            return $this->redirectToRoute('app_home');
        } else {
            dump($form->getErrors(true, false)); // debug erreurs
        }
    }

        $vehicules = $em->getRepository(Vehicule::class)->findBy(['user' => $user]);
        $vehiculeForm = $this->createForm(VehiculeType::class, new Vehicule())->createView();

        return $this->render('security/account.html.twig', [
            'form' => $form->createView(),   // formulaire User
            'vehicules' => $vehicules,
            'user' => $user,
            'vehiculeForm' => $vehiculeForm,    // formulaire Véhicule
        ]);
    }
}