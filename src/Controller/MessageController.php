<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\UserRelation;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'create_message', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $message->setIdSender($request->request->get('idSender'));
        $message->setIdReceiver($request->request->get('idReceiver'));
        $message->setText($request->request->get('text'));

        $entityManager->persist($message);
        $entityManager->flush();

        return new Response('Message created with id ' . $message->getId());
    }

    #[Route('/messages', name: 'get_messages', methods: ['POST'])]
    public function getMessages(Request $request, EntityManagerInterface $entityManager): Response
    {
        $idSender = $request->request->get('idSender');
        $idReceiver = $request->request->get('idReceiver');
    
        $messages = $entityManager->getRepository(Message::class)->createQueryBuilder('m')
            ->where('m.idSender = :idSender AND m.idReceiver = :idReceiver')
            ->orWhere('m.idSender = :idReceiver AND m.idReceiver = :idSender')
            ->setParameter('idSender', $idSender)
            ->setParameter('idReceiver', $idReceiver)
            ->getQuery()
            ->getResult();
    
        return $this->json($messages);
    }

    #[Route('/data', name: 'app_data')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $relations = $entityManager->getRepository(UserRelation::class)->findAll();
        $messages = $entityManager->getRepository(Message::class)->findAll();

        $userData = array_map(function ($user) {
            return [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'photos' => $user->getPhotos(),
                'interests' => $user->getInterests(),
                'location' => $user->getLocation(),
                'favoriteBook' => $user->getFavoriteBook(),
                'bio' => $user->getBio()
                // Ajoutez d'autres champs si nécessaire
            ];
        }, $users);

        $relationData = array_map(function ($relation) {
            return [
                'id' => $relation->getId(),
                'userId' => $relation->getUser()->getId(),
                'targetUserId' => $relation->getTargetUser()->getId(),
                'type' => $relation->getType(),
                'createdAt' => $relation->getCreatedAt()->format('Y-m-d H:i:s')
                // Ajoutez d'autres champs si nécessaire
            ];
        }, $relations);

        $messageData = array_map(function ($message) {
            return [
                'id' => $message->getId(),
                'idSender' => $message->getIdSender(),
                'idReceiver' => $message->getIdReceiver(),
                'text' => $message->getText(),
                'creationDate' => $message->getCreationDate()->format('Y-m-d H:i:s')
                // Ajoutez d'autres champs si nécessaire
            ];
        }, $messages);

        return $this->json([
            'users' => $userData,
            'relations' => $relationData,
            'messages' => $messageData,
        ]);
    }
}