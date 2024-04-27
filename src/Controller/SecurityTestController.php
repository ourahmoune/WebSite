<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/security', name: '_security')]
class SecurityTestController extends AbstractController
{

    #[Route('/addusers', name: '_addusers')]
    public function addUsersAction(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
      

        $user = new User();
        $user
            ->setLogin('ak1')
            ->setName('alireza')
            ->setRoles(['ROLE_SUPER_ADMIN']);
            
            
        $hashedPassword = $passwordHasher->hashPassword($user, 'code');
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();

        $user = new User();
        $user
            ->setLogin('sadmin')
            ->setName('sadmin')
            ->setRoles(['ROLE_SUPER_ADMIN']);
            
            
        $hashedPassword = $passwordHasher->hashPassword($user, 'nimdas');
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();

        $user = new User();
        $user
            ->setLogin('gilles')
            ->setName('gilles')
            ->setRoles(['ROLE_ADMIN']);
            
            
        $hashedPassword = $passwordHasher->hashPassword($user, 'sellig');
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();

        $user = new User();
        $user
            ->setLogin('rita')
            ->setName('rita')
            ->setRoles(['ROLE_USER']);
            
            
        $hashedPassword = $passwordHasher->hashPassword($user, 'atir');
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();


        $user = new User();
        $user
            ->setLogin('simon')
            ->setName('simon')
            ->setRoles(['ROLE_USER']);
            
            
        $hashedPassword = $passwordHasher->hashPassword($user, 'nomis');
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();

       

        return new Response('<body></body>');
    }
}