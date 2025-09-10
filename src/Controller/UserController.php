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
        dump('Formulaire soumis'); 
        if ($form->isValid()) {
            dump('Formulaire valide'); 

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
            dump($form->getErrors(true, false)); 
        }
    }

        $vehicules = $em->getRepository(Vehicule::class)->findBy(['user' => $user]);
        $vehiculeForm = $this->createForm(VehiculeType::class, new Vehicule())->createView();

        return $this->render('security/account.html.twig', [
            'form' => $form->createView(),   
            'vehicules' => $vehicules,
            'user' => $user,
            'vehiculeForm' => $vehiculeForm,    
        ]);
    }

    public function editProfile(Request $request, User $user): Response
    {
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hiddenRoles = array_filter($user->getRoles(), function($role) {
                return in_array($role, ['ROLE_EMPLOYE', 'ROLE_ADMIN']);
            });
            
            $newRoles = array_merge($form->get('roles')->getData(), $hiddenRoles);
            $user->setRoles(array_unique($newRoles));
            
            $entityManager->flush();
            
        }
        
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}