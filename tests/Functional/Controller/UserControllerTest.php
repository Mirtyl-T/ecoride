<?php

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $passwordHasher;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->passwordHasher = $this->client->getContainer()
            ->get(UserPasswordHasherInterface::class);
    }

    public function testAccessDeniedWhenNotLoggedIn()
    {
        $this->client->request('GET', '/account');
        
        $this->assertResponseRedirects('/login');
    }

    public function testAccountPageAccessWhenLoggedIn()
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $this->client->request('GET', '/account');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mon Compte');
        $this->assertSelectorExists('form[name="user_profile"]');
    }

    public function testUpdateProfileWithoutPassword()
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', '/account');
        
        $form = $crawler->selectButton('Mettre à jour')->form([
            'user_profile[email]' => 'updated@example.com',
            'user_profile[username]' => 'updateduser',
            'user_profile[plainPassword]' => '', 
        ]);

        $this->client->submit($form);
        
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
        
        $updatedUser = $this->entityManager->getRepository(User::class)->find($user->getId());
        
        $this->assertEquals('updated@example.com', $updatedUser->getEmail());
        $this->assertEquals('updateduser', $updatedUser->getUsername());
        $this->assertTrue($this->passwordHasher->isPasswordValid($updatedUser, 'password123'));
    }

    public function testUpdateProfileWithPassword()
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', '/account');
        
        $form = $crawler->selectButton('Mettre à jour')->form([
            'user_profile[email]' => 'user@example.com',
            'user_profile[username]' => 'testuser',
            'user_profile[plainPassword]' => 'newpassword123',
        ]);

        $this->client->submit($form);
        
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        
        $updatedUser = $this->entityManager->getRepository(User::class)->find($user->getId());
        $this->assertTrue($this->passwordHasher->isPasswordValid($updatedUser, 'newpassword123'));
    }

    public function testInvalidProfileForm()
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', '/account');
        
        $form = $crawler->selectButton('Mettre à jour')->form([
            'user_profile[email]' => 'invalid-email',
            'user_profile[username]' => '',
            'user_profile[plainPassword]' => '',
        ]);

        $this->client->submit($form);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.form-error-message');
        $this->assertSelectorTextContains('h1', 'Mon Compte');
    }

    public function testVehiculesDisplayedInAccount()
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $vehicule = new Vehicule();
        $vehicule->setMarque('Renault');
        $vehicule->setModele('Clio');
        $vehicule->setCouleur('Bleu');
        $vehicule->setPlaces(4);
        $vehicule->setUser($user);
        
        $this->entityManager->persist($vehicule);
        $this->entityManager->flush();

        $this->client->request('GET', '/account');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Mes Véhicules');
        $this->assertSelectorTextContains('table', 'Renault Clio');
    }

    public function testVehiculeFormAvailableInAccount()
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $this->client->request('GET', '/account');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="vehicule"]');
        $this->assertSelectorExists('input[name="vehicule[marque]"]');
        $this->assertSelectorExists('input[name="vehicule[modele]"]');
    }

    public function testUserCreditsDisplayed()
    {
        $user = $this->createTestUser();
        $user->setCredits(50); 
        $this->entityManager->flush();
        
        $this->client->loginUser($user);

        $this->client->request('GET', '/account');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', '50 crédits'); 
    }

    public function testUserStatusDisplayed()
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $this->client->request('GET', '/account');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.user-status');
    }

    private function createTestUser(): User
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setUsername('testuser');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        $connection = $this->entityManager->getConnection();
        $connection->executeQuery('DELETE FROM vehicule');
        $connection->executeQuery('DELETE FROM user');
        $this->entityManager->clear();
    }
}