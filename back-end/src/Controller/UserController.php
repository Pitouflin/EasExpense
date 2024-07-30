<?php

// src/Controller/UserController.php
namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_list', methods: ['GET'])]
    public function list(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $users = $userRepository->findAll();
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user, SerializerInterface $serializer): Response
    {
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('/User/check', name: 'app_user_check', methods: ['POST'])]
    public function checkUser(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, SerializerInterface $serializer): Response
    {
        $login = $request->get('login');
        $password = $request->get('password');

        $user = $userRepository->findOneBy(['login' => $login]);

        if ($user && $passwordHasher->isPasswordValid($user, $password)) {
            $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
            return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }

    #[Route('/search', name: 'app_user_search', methods: ['POST'])]
    public function searchUser(Request $request, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? '';
        $login = $data['login'] ?? '';

        if (empty($name) && empty($login)) {
            $users = [];
        } else {
            $users = $userRepository->findBy([
                'name' => $name,
                'login' => $login
            ]);
        }

        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route('/', name: 'app_user_new', methods: ['POST'])]
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $data = json_decode($request->getContent(), true);
        
        $plainPassword = $data['password'] ?? null;
        if ($plainPassword) {
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

        $dateTime = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $user->setDateCreation($dateTime);
        $user->setEstActive(true);

        $em->persist($user);
        $em->flush();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        $location = $urlGenerator->generate('user_show', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/{id}', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(Request $request, SerializerInterface $serializer, User $currentUser, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $psw = $currentUser->getPassword();
        $updatedUser = $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]
        );

        $data = json_decode($request->getContent(), true);
        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($updatedUser, $data['password']);
            $updatedUser->setPassword($hashedPassword);
        } else {
            $updatedUser->setPassword($psw);
        }

        $em->persist($updatedUser);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}