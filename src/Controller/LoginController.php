<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $JWTManager): Response
    {
        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return new Response('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        $userData = [
            'id' => $user->getId(),
            'photos'=>$user ->getPhotos(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'birthDate' => $user->getBirthDate() ? $user->getBirthDate()->format('Y-m-d') : null,
            'gender' => $user->getGender(),
            'location' => $user->getLocation(),
            'interests' => $user->getInterests(),
            'favoriteBook' => $user->getFavoriteBook(),
            'matches' => $user->getMatches(),
            'bio' => $user->getBio(),
        ];

        // Créer un jeton JWT en incluant les informations sur l'utilisateur
        $token = $JWTManager->create($user);

        // Ajouter les informations sur l'utilisateur à la réponse JSON
        $response = [
            'token' => $token,
            'user' => $userData,
        ];

        // Convertir la réponse en JSON
        return $this->json($response);
    }
    
}
