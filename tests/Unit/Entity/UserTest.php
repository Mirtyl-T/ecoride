<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Entity\Vehicule;
use App\Entity\Trip;
use App\Entity\Participation;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->getDateCreation());
        $this->assertEquals(20, $user->getCredits());
        $this->assertTrue($user->isActif());
        $this->assertFalse($user->isVerified());
        $this->assertFalse($user->getIsSuspended());
    }

    public function testEmail()
    {
        $user = new User();
        $email = 'test@example.com';
        
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->getUserIdentifier());
    }

    public function testUsername()
    {
        $user = new User();
        $username = 'testuser';
        
        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    public function testPassword()
    {
        $user = new User();
        $password = 'hashed_password';
        
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testRoles()
    {
        $user = new User();
        
        // Test des rôles par défaut
        $this->assertContains('ROLE_USER', $user->getRoles());
        
        // Test d'ajout de rôles
        $user->setRoles(['ROLE_CHAUFFEUR']);
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_CHAUFFEUR', $user->getRoles());
        
        // Test de rôle admin
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testInvalidRoleThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $user = new User();
        $user->setRoles(['ROLE_INVALID']);
    }

    public function testConflictingRolesThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $user = new User();
        $user->setRoles(['ROLE_ADMIN', 'ROLE_EMPLOYE']);
    }

    public function testCreditsManagement()
    {
        $user = new User();
        
        // Test crédits initiaux
        $this->assertEquals(20, $user->getCredits());
        
        // Test ajout de crédits
        $user->addCredits(10);
        $this->assertEquals(30, $user->getCredits());
        
        // Test retrait de crédits
        $user->removeCredits(15);
        $this->assertEquals(15, $user->getCredits());
        
        // Test crédits ne peuvent pas être négatifs
        $user->removeCredits(20);
        $this->assertEquals(0, $user->getCredits());
        
        // Test setCredits
        $user->setCredits(50);
        $this->assertEquals(50, $user->getCredits());
    }

    public function testStatusManagement()
    {
        $user = new User();
        
        // Test actif
        $user->setActif(false);
        $this->assertFalse($user->isActif());
        
        // Test vérification email
        $user->setIsVerified(true);
        $this->assertTrue($user->isVerified());
        
        // Test suspension
        $user->setIsSuspended(true);
        $this->assertTrue($user->getIsSuspended());
        $this->assertFalse($user->isEnabled());
    }

    public function testVehiculesManagement()
    {
        $user = new User();
        $vehicule = new Vehicule();
        
        $user->addVehicule($vehicule);
        $this->assertCount(1, $user->getVehicules());
        $this->assertTrue($user->getVehicules()->contains($vehicule));
        $this->assertEquals($user, $vehicule->getUser());
        
        $user->removeVehicule($vehicule);
        $this->assertCount(0, $user->getVehicules());
        $this->assertNull($vehicule->getUser());
    }

    public function testTripsManagement()
    {
        $user = new User();
        $trip = new Trip();
        
        $user->addTrip($trip);
        $this->assertCount(1, $user->getTrips());
        $this->assertTrue($user->getTrips()->contains($trip));
        $this->assertEquals($user, $trip->getDriver());
        
        $user->removeTrip($trip);
        $this->assertCount(0, $user->getTrips());
        $this->assertNull($trip->getDriver());
    }

    public function testParticipationsManagement()
    {
        $user = new User();
        $participation = new Participation();
        
        $user->addParticipation($participation);
        $this->assertCount(1, $user->getParticipations());
        $this->assertTrue($user->getParticipations()->contains($participation));
        $this->assertEquals($user, $participation->getUser());
        
        $user->removeParticipation($participation);
        $this->assertCount(0, $user->getParticipations());
        $this->assertNull($participation->getUser());
    }

    public function testDateCreation()
    {
        $user = new User();
        $date = new \DateTime('2023-01-01');
        
        $user->setDateCreation($date);
        $this->assertEquals($date, $user->getDateCreation());
    }

    public function testEraseCredentials()
    {
        $user = new User();
        
        $user->eraseCredentials();
        $this->assertTrue(true); 
    }
}