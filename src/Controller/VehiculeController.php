<?php
namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehiculeController extends AbstractController
{
    #[Route('/vehicule', name: 'vehicule_index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $vehicules = $em->getRepository(Vehicule::class)->findBy(['user' => $this->getUser()]);

        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicule->setUser($this->getUser());
            $em->persist($vehicule);
            $em->flush();

            $this->addFlash('success', 'Véhicule ajouté avec succès !');
            return $this->redirectToRoute('vehicule_index');
        }
        $editForms = [];
        foreach ($vehicules as $vehicule) {
            $editForms[$vehicule->getId()] = $this->createForm(VehiculeType::class, $vehicule)->createView();
        }

        return $this->render('security/vehicule.html.twig', [
            'vehicules' => $vehicules,
            'form' => $form->createView(),
            'editForms' => $editForms, 
        ]);
    }
    
    #[Route('/vehicule/{id}/edit', name: 'vehicule_edit', methods: ['GET','POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $vehicule = $em->getRepository(Vehicule::class)->find($id);

        if (!$vehicule) {
            throw $this->createNotFoundException("Véhicule introuvable");
        }

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('vehicule_index');
        }

        return $this->render('security/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vehicule/delete/{id}', name: 'vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $vehicule = $entityManager->getRepository(Vehicule::class)->find($id);
        if (!$vehicule) {
            throw $this->createNotFoundException('No vehicule found for id '.$id);
        }

        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vehicule_index');
    }
}
