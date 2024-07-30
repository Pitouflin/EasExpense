<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\ExpenseType;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/expense_type')]
class ExpenseTypeController extends AbstractController
{
    #[Route('/', name: 'app_expense_type')]

    public function list(EntityManagerInterface $em): JsonResponse
    {
        $ExpenseType = $em->getRepository(ExpenseType::class)->findAll();
        return $this->json($ExpenseType, 200, [], [AbstractNormalizer::GROUPS => 'ExpenseType:read']);
    }


    public function index(): Response
    {
        return $this->render('expense_type/index.html.twig', [
            'controller_name' => 'ExpenseTypeController',
        ]);
    }

    #[Route('/{id}', name: 'expenseType_show', methods: ['GET'])]
    public function show(ExpenseType $expenseType, SerializerInterface $serializer): JsonResponse
    {
        $jsonExpenseType = $serializer->serialize($expenseType, 'json', ['groups' => 'expenseType:read']);
        return new JsonResponse($jsonExpenseType, Response::HTTP_OK, [], true);
    }
}
