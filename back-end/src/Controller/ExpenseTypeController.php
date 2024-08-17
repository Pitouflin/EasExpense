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
    #[Route('/', name: 'app_expense_type', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        // Récupérer tous les ExpenseTypes
        $expenseTypes = $em->getRepository(ExpenseType::class)->findAll();

        // Vérifier si des ExpenseTypes ont été trouvés
        if (!$expenseTypes) {
            return $this->json([], 200); // Renvoie un tableau vide si aucun type n'a été trouvé
        }

        // Retourner les données JSON avec le groupe de sérialisation correct
        return $this->json($expenseTypes, 200, [], [AbstractNormalizer::GROUPS => 'expenseType:read']);
    }

    #[Route('/{id}', name: 'expenseType_show', methods: ['GET'])]
    public function show(ExpenseType $expenseType, SerializerInterface $serializer): JsonResponse
    {
        // Sérialiser un seul ExpenseType
        $jsonExpenseType = $serializer->serialize($expenseType, 'json', ['groups' => 'expenseType:read']);
        return new JsonResponse($jsonExpenseType, Response::HTTP_OK, [], true);
    }
}
