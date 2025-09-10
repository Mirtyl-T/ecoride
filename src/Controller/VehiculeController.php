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

            $listePreferences = [];
            if ($vehicule->isFumeur()) $listePreferences[] = 'Fumeur';
            if ($vehicule->isAnimaux()) $listePreferences[] = 'Animaux acceptés';

            $texteLibre = $form->get('preferences')->getData();
            if ($texteLibre) $listePreferences[] = $texteLibre;

            $vehicule->setPreferences(implode("\n", $listePreferences));

            $em->persist($vehicule);
            $em->flush();

            return $this->redirectToRoute('vehicule_index');
        }

        $editForms = [];
        foreach ($vehicules as $v) {
            $editForms[$v->getId()] = $this->createForm(VehiculeType::class, $v)->createView();
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
        if (!$vehicule) throw $this->createNotFoundException("Véhicule introuvable");

        $vehicule->setPreferences(null);

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listePreferences = [];
            if ($vehicule->isFumeur()) $listePreferences[] = 'Fumeur';
            if ($vehicule->isAnimaux()) $listePreferences[] = 'Animaux acceptés';

            $texteLibre = $form->get('preferences')->getData();
            if ($texteLibre) $listePreferences[] = $texteLibre;

            $vehicule->setPreferences(implode("\n", $listePreferences));

            $em->flush();
            return $this->redirectToRoute('vehicule_index');
        }

        return $this->render('security/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vehicule/delete/{id}', name: 'vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $em): Response
    {
        $vehicule = $em->getRepository(Vehicule::class)->find($id);
        if ($vehicule && $this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $em->remove($vehicule);
            $em->flush();
        }

        return $this->redirectToRoute('vehicule_index');
    }
}
