<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\ExpenseReport;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/expense_report')]
class ExpenseReportController extends AbstractController
{
    #[Route('/', name: 'app_expense_report')]
    
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $expenseReport = $em->getRepository(ExpenseReport::class)->findAll();
        return $this->json($expenseReport, 200, [], [AbstractNormalizer::GROUPS => 'expenseReport:read']);
    }

    public function index(): Response
    {
        return $this->render('expense_report/index.html.twig', [
            'controller_name' => 'ExpenseReportController',
        ]);
    }

    #[Route('/{id}', name: 'expenseReport_show', methods: ['GET'])]
    public function show(ExpenseReport $expenseReport, SerializerInterface $serializer): JsonResponse
    {
        $jsonExpenseReport = $serializer->serialize($expenseReport, 'json', ['groups' => 'expenseReport:read']);
        return new JsonResponse($jsonExpenseReport, Response::HTTP_OK, [], true);
    }
}
