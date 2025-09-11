<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Review;
use App\Entity\User;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $employes = $em->getRepository(User::class)->findAll();
        $reviews = $em->getRepository(Review::class)->findBy(['etat' => 'en_attente']);
        $incidents = $em->getRepository(Review::class)->findBy([
            'etat' => 'en_attente',
            'isIncident' => true,
        ]);

        $conn = $em->getConnection();
        $sql = "SELECT MONTH(date_depart) as mois, COUNT(*) as total FROM trips GROUP BY mois";
        $stmt = $conn->prepare($sql);
        $trips = $stmt->executeQuery()->fetchAllAssociative();

        return $this->render('security/admin.html.twig', [
            'employes' => $employes,
            'reviews' => $reviews,
            'incidents' => $incidents,
            'trips' => $trips,
        ]);
    }

    #[Route('/admin/suspendre/{id}', name: 'admin_suspendre')]
    public function suspendre(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $user->setIsSuspended(true);
        $em->flush();

        $this->addFlash('success', 'Compte suspendu avec succès !');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/reactiver/{id}', name: 'admin_reactiver')]
    public function reactiver(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $user->setIsSuspended(false);
        $em->flush();

        $this->addFlash('success', 'Compte réactivé avec succès !');
        return $this->redirectToRoute('admin_dashboard');
    }
    #[Route('/admin/role/employe/{id}', name: 'admin_role_employe')]
    public function assignEmployeRole(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $roles = $user->getRoles();

        if (!in_array('ROLE_EMPLOYE', $roles, true)) {
            $roles[] = 'ROLE_EMPLOYE';
            $user->setRoles($roles);
            $em->flush();

            $this->addFlash('success', 'Utilisateur promu en employé avec succès !');
        } else {
            $this->addFlash('info', 'Cet utilisateur est déjà employé.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }
    #[Route('/admin/role/remove-employe/{id}', name: 'admin_remove_role_employe')]
    public function removeEmployeRole(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $roles = $user->getRoles();

        if (in_array('ROLE_EMPLOYE', $roles, true)) {
            $roles = array_filter($roles, fn($role) => $role !== 'ROLE_EMPLOYE');
            $user->setRoles($roles);
            $em->flush();

            $this->addFlash('success', 'Le rôle employé a été retiré à cet utilisateur.');
        } else {
            $this->addFlash('info', 'Cet utilisateur n\'est pas employé.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}
