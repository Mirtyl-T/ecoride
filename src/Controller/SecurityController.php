<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private UserRepository $userRepository
    ) {}

    // --- API registration (POST) ---
    #[Route('/api/register', name: 'app_api_register', methods: ['POST'])]
    public function registerApi(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data) || empty($data['email']) || empty($data['username']) || empty($data['password'])) {
            return new JsonResponse(['message' => 'Données manquantes ou invalides.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $role = $data['role'] ?? 'ROLE_USER'; // valeur par défaut
        if (!in_array($role, User::ROLES, true)) {
            return new JsonResponse(['message' => 'Role invalide.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles([$role]); 
        $user->generateApiToken();

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'role' => $role,
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ], JsonResponse::HTTP_CREATED);
    }

    // --- Login API (POST) ---
    #[Route('/api/login', name: 'app_api_login', methods: ['POST'])]
    public function apilogin(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['email' => $data['email'] ?? '']);

        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'] ?? '')) {
            return new JsonResponse(['message' => 'Identifiants invalides'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user'     => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles'    => $user->getRoles(),
        ]);
    }

    // --- Logout (POST) ---
    #[Route('/api/logout', name: 'app_api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return new JsonResponse(['message' => 'Déconnecté'], Response::HTTP_OK);
    }

}
