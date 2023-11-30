<?php
// src/Controller/MatchController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRelationRepository;
use App\Repository\MessageRepository;

class MatchController extends AbstractController
{
    #[Route('/get-matches/{userId}', name: 'get_matches')]
public function getMatches(int $userId, UserRelationRepository $userRelationRepository, MessageRepository $messageRepository): Response
{
    $matches = $userRelationRepository->findMatchesForUser($userId);
    $formattedMatches = [];
    $processedTargetUsers = [];

    foreach ($matches as $match) {
        $targetUser = $match->getTargetUser();
        $targetUserId = $targetUser->getId();

        // Éviter les doublons et les matchs auto-référencés
        if ($targetUserId == $userId || in_array($targetUserId, $processedTargetUsers)) {
            continue;
        }

        $lastMessage = $messageRepository->findLastMessageBetweenUsers($userId, $targetUserId);
        $previewMessage = $lastMessage ? $lastMessage->getText() : 'Write a message';

        $formattedMatches[] = [
            'id' => $targetUserId,
            'name' => $targetUser->getFirstName(), // Utilisez la propriété pertinente
            'photo' => $targetUser->getPhotos()[0] ?? null, // Première photo ou null
            'previewMessage' => $previewMessage,
        ];

        // Ajouter l'ID du targetUser traité à la liste
        $processedTargetUsers[] = $targetUserId;
    }

    return $this->json([
        'matches' => $formattedMatches
    ]);

}
}