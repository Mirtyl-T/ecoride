<?php
namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class UserController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        LoggerInterface $logger
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $logger->info('Formulaire de profil soumis', [
                'user' => $user->getUserIdentifier(),
            ]);

            if ($form->isValid()) {
                $logger->info('Formulaire valide');

                $plainPassword = $form->get('plainPassword')->getData();
                if ($plainPassword) {
                    $hashedPassword = $hasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashedPassword);
                    $logger->info('Mot de passe mis à jour');
                }

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Profil mis à jour.');
                return $this->redirectToRoute('app_home');
            } else {
                $logger->warning('Formulaire invalide', [
                    'errors' => (string) $form->getErrors(true, false),
                ]);
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
}
