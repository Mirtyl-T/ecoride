<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vehicule;
use App\Entity\Trip;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ==== Utilisateurs ====
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@ecoride.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setCredits(100);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $chauffeur = new User();
        $chauffeur->setUsername('marie');
        $chauffeur->setEmail('marie@ecoride.fr');
        $chauffeur->setRoles(['ROLE_CHAUFFEUR']);
        $chauffeur->setCredits(20);
        $chauffeur->setPassword($this->passwordHasher->hashPassword($chauffeur, 'marie123'));
        $manager->persist($chauffeur);

        $passager = new User();
        $passager->setUsername('paul');
        $passager->setEmail('paul@ecoride.fr');
        $passager->setRoles(['ROLE_PASSAGER']);
        $passager->setCredits(20);
        $passager->setPassword($this->passwordHasher->hashPassword($passager, 'paul123'));
        $manager->persist($passager);

        // ==== Voitures ====
        $Vehicule1 = new Vehicule();
        $Vehicule1->setUser($chauffeur);
        $Vehicule1->setMarque('Tesla');
        $Vehicule1->setModele('Model 3');
        $Vehicule1->setfuelType('electrique');
        $Vehicule1->setCouleur('Blanc');
        $Vehicule1->setImmatriculation('AB-123-CD');
        $Vehicule1->setNbPlaces(4);
        $manager->persist($Vehicule1);

        $Vehicule2 = new Vehicule();
        $Vehicule2->setUser($chauffeur);
        $Vehicule2->setMarque('Renault');
        $Vehicule2->setModele('Clio');
        $Vehicule2->setfuelType('essence');
        $Vehicule2->setCouleur('Bleu');
        $Vehicule2->setImmatriculation('EF-456-GH');
        $Vehicule2->setNbPlaces(3);
        $manager->persist($Vehicule2);

        // ==== Trajets ====
        $Trip1 = new Trip();
        $Trip1->setDriver($chauffeur);
        $Trip1->setVehicule($Vehicule1);
        $Trip1->setVilleDepart('Paris');
        $Trip1->setVilleArrivee('Lyon');
        $Trip1->setDateDepart(new \DateTime('2025-09-25 08:00:00'));
        $Trip1->setPrix(25);
        $Trip1->setPlacesDispo(3);
        $manager->persist($Trip1);

        $Trip2 = new Trip();
        $Trip2->setDriver($chauffeur);
        $Trip2->setVehicule($Vehicule2);
        $Trip2->setVilleDepart('Marseille');
        $Trip2->setVilleArrivee('Nice');
        $Trip2->setDateDepart(new \DateTime('2025-09-26 09:30:00'));
        $Trip2->setPrix(15);
        $Trip2->setPlacesDispo(2);
        $manager->persist($Trip2);

        $manager->flush();
    }
}
