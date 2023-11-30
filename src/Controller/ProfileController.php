<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class ProfileController extends AbstractController
{
    #[Route('/profile/update', name: 'app_profile_update', methods: ['PUT'])]
    public function updateProfile(Request $request, UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        if (!$user) {
            return $this->json(['message' => 'Utilisateur non connecté'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        // Mettre à jour les propriétés de l'utilisateur
        if (isset($data['bio'])) {
            $user->setBio($data['bio']);
        }
        if (isset($data['location'])) {
            $user->setLocation($data['location']);
        }
        // Ajouter d'autres champs si nécessaire

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Profil mis à jour avec succès',
            'user'=> $user
        ]);
    }


}
