<?php

namespace App\Controller;

use App\Entity\UserRelation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\UserRelationRepository;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/get-unseen-users/{userId}', name: 'get_unseen_users')]
    public function getUnseenUsers(int $userId, UserRepository $userRepository, UserRelationRepository $userRelationRepository): Response
    {
    $user = $userRepository->find($userId);

    if (!$user) {
        return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
    }

    $unseenUsers = $userRepository->findUnseenUsers($user, $userRelationRepository);

    $userData = array_map(function ($user) {
        return [
            'id' => $user->getId(),
            'photos' => $user->getPhotos(),
            'firstName' => $user->getFirstName(),
        ];
    }, $unseenUsers);

    return $this->json($userData);
    }

    #[Route('/user-action', name: 'user_action', methods: ['POST'])]
    public function userAction(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $userId = $data['userId'];
        $targetUserId = $data['targetUserId'];
        $actionType = $data['actionType']; 
        $user = $userRepository->find($userId);
        $targetUser = $userRepository->find($targetUserId);
    
        if (!$user || !$targetUser) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    
        $userRelation = new UserRelation();
        $userRelation->setUser($user);
        $userRelation->setTargetUser($targetUser);
        $userRelation->setType($actionType);
        $userRelation->setCreatedAt(new \DateTime());
    
        $entityManager->persist($userRelation);
        $entityManager->flush();
    
        return $this->json(['message' => 'Action enregistrée avec succès']);
    }

    #[Route('/user/{id}', name: 'get_user_by_id', methods: ['GET'])]
    public function getUserById(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
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
        ]);
    }
}
