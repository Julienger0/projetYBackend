<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserRelation;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private function createConversation(ObjectManager $manager, User $user1, User $user2)
    {
        $messages = [
            "Hello, how are you?",
            "I'm good, thanks! And you?",
            "I'm doing well, thanks for asking!",
            "Great to hear that. What are you up to?",
            "Just working on my Symfony project."
        ];

        foreach ($messages as $text) {
            $message = new Message();
            $message->setIdSender($user1->getId());
            $message->setIdReceiver($user2->getId());
            $message->setText($text);
            $message->setCreationDate(new \DateTime());

            $manager->persist($message);
        }
    }

    public function load(ObjectManager $manager)
    {
        // Créer l'utilisateur spécifié
        $specificUser = new User();
        $specificUser->setEmail("123456789@com");
        $specificUser->setPassword('password'); 
        $specificUser->setFirstName("test");
        $specificUser->setLastName("test");
        $specificUser->setBirthDate(new \DateTime("2023-10-29"));
        $specificUser->setGender("male");
        $specificUser->setLocation("Budapest");
        $specificUser->setInterests(['Running', 'Gaming', 'Dance']);
        $specificUser->setFavoriteBook("/works/OL30880017W");
        $specificUser->setPhotos([
            "https://res.cloudinary.com/dybhpxiai/image/upload/v1701264354/hkg6z2fnkzvb5y0y1jjy.jpg",
            "https://res.cloudinary.com/dybhpxiai/image/upload/v1701264355/tyhi3ldscf8p7j2onejg.jpg",
            "https://res.cloudinary.com/dybhpxiai/image/upload/v1701264355/tn9kf3ragrrds1nuapgc.png"
        ]);
        $specificUser->setBio("test budapest zlazlaz");
        $manager->persist($specificUser);

        // Créer des utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("user$i@example.com");
            $user->setPassword('password');
            $user->setFirstName("FirstName$i");
            $user->setLastName("LastName$i");
            $user->setBirthDate(new \DateTime());
            $user->setGender(['male', 'female'][rand(0, 1)]);
            $user->setLocation("Location$i");
            $user->setInterests(['Music', 'Reading']);
            $user->setFavoriteBook("/works/OL4049432W");
            $user->setPhotos([
                "https://images.unsplash.com/photo-1551963831-b3b1ca40c98e",
                "https://images.unsplash.com/photo-1551782450-a2132b4ba21d",
                "https://images.unsplash.com/photo-1522770179533-24471fcdba45"
            ]);
            $user->setBio("test biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest biotest bio ");

            $manager->persist($user);
        }

        $manager->flush();

        // Créer des relations de match et des messages
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            if ($user->getId() > $specificUser->getId() && $user->getId() < $specificUser->getId()+6) {
                $relation = new UserRelation();
                $relation->setUser($specificUser);
                $relation->setTargetUser($user);
                $relation->setType('like');
                $relation->setCreatedAt(new \DateTime());
                $manager->persist($relation);
                
                $relation2 = new UserRelation();
                $relation2->setUser($user);
                $relation2->setTargetUser($specificUser);
                $relation2->setType('like');
                $relation2->setCreatedAt(new \DateTime());
                $manager->persist($relation2);

                $this->createConversation($manager, $specificUser, $user);
                $this->createConversation($manager, $user, $specificUser);
            }
        }

        $manager->flush();
    }
}
