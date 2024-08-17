<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ExpenseReport;
use App\Entity\ExpenseState;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\ExpenseType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/expense_report')]
class ExpenseReportController extends AbstractController
{
    private $logger;
    private $validator;

    public function __construct(LoggerInterface $logger, ValidatorInterface $validator)
    {
        $this->logger = $logger;
        $this->validator = $validator;
    }

    #[Route('/', name: 'app_expense_report')]
public function list(EntityManagerInterface $em): JsonResponse
{
    $this->logger->info('Fetching pending expense reports.');

    // Récupérer les notes de frais avec un état de 1 (En attente)
    $expenseReports = $em->getRepository(ExpenseReport::class)->findBy(['state' => $em->getRepository(ExpenseState::class)->find(1)]);

    $data = array_map(function ($report) {
        return [
            'id' => $report->getId(),
            'userId' => [
                'id' => $report->getUserId()->getId(),
                'name' => $report->getUserId()->getName(),
            ],
            'value' => $report->getValue(),
            'comment' => $report->getComment(),
            'vehicle' => $report->getVehicle() ? [
                'id' => $report->getVehicle()->getId(),
                'name' => $report->getVehicle()->getName(),
            ] : null,
            'state' => [
                'id' => $report->getState()->getId(),
                'value' => $report->getState()->getValue(),
            ],
            'date' => $report->getDate()->format('Y-m-d'), // Assurez-vous que la date est correctement formatée
            'expenseType' => $report->getExpenseType() ? [
                'id' => $report->getExpenseType()->getId(),
                'name' => $report->getExpenseType()->getName(),
            ] : null
        ];
    }, $expenseReports);

    return $this->json($data, 200);
}




    // src/Controller/ExpenseReportController.php

#[Route('/create', name: 'expense_report_create', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!isset($data['userId'], $data['vehicleId'], $data['expenseTypeId'], $data['expenseValue'], $data['comment'])) {
        return new JsonResponse(['error' => 'Champs manquants'], Response::HTTP_BAD_REQUEST);
    }

    $user = $em->getRepository(User::class)->find($data['userId']);
    $vehicle = $em->getRepository(Vehicle::class)->find($data['vehicleId']);
    $expenseType = $em->getRepository(ExpenseType::class)->find($data['expenseTypeId']);

    if (!$user || !$vehicle || !$expenseType) {
        return new JsonResponse(['error' => 'Utilisateur, véhicule ou type de dépense invalide'], Response::HTTP_BAD_REQUEST);
    }

    $expenseReport = new ExpenseReport();
    $expenseReport->setUserId($user);
    $expenseReport->setVehicle($vehicle);
    $expenseReport->setExpenseType($expenseType);
    $expenseReport->setValue($data['expenseValue']);
    $expenseReport->setComment($data['comment'] ?? '');

    // Ajout de la date de création
    $expenseReport->setDate(new \DateTime());

    // Par défaut, l'état est "En attente"
    $expenseReport->setState($em->getRepository(ExpenseState::class)->find(1));

    $errors = $this->validator->validate($expenseReport);
    if (count($errors) > 0) {
        return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
    }

    $em->persist($expenseReport);
    $em->flush();

    $this->logger->info('Expense report created.', ['expense_report_id' => $expenseReport->getId()]);

    return new JsonResponse(['status' => 'Note de frais créée avec succès', 'id' => $expenseReport->getId()], Response::HTTP_CREATED);
}


#[Route('/update_state/{id}', name: 'expense_report_update_state', methods: ['PUT'])]
public function updateState(Request $request, ExpenseReport $expenseReport, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!isset($data['stateId'])) {
        return new JsonResponse(['error' => 'Champs manquants'], Response::HTTP_BAD_REQUEST);
    }

    $expenseState = $em->getRepository(ExpenseState::class)->find($data['stateId']);
    if (!$expenseState) {
        return new JsonResponse(['error' => 'État de dépense invalide'], Response::HTTP_BAD_REQUEST);
    }

    // Mettre à jour uniquement l'état de la note de frais
    $expenseReport->setState($expenseState);

    // Mettre à jour adminComment uniquement pour les rejets (état 3)
    if ($expenseState->getId() === 3) {
        if (!isset($data['adminComment'])) {
            return new JsonResponse(['error' => 'Commentaire administratif manquant'], Response::HTTP_BAD_REQUEST);
        }
        $expenseReport->setAdminComment($data['adminComment']);
    } else {
        // Si ce n'est pas un rejet, ne pas modifier adminComment
        $expenseReport->setAdminComment(null);
    }

    // Ne pas modifier le champ comment
    // Le champ comment reste inchangé, il n'est pas mis à jour ici

    $errors = $this->validator->validate($expenseReport);
    if (count($errors) > 0) {
        return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
    }

    $em->flush();

    $this->logger->info('Expense report state updated.', ['expense_report_id' => $expenseReport->getId()]);

    return new JsonResponse(['status' => 'État de la note de frais mis à jour avec succès'], Response::HTTP_OK);
}


#[Route('/{id}', name: 'expense_report_show', methods: ['GET'])]
    public function show(ExpenseReport $expenseReport, SerializerInterface $serializer): JsonResponse
    {
        $this->logger->info('Displaying expense report.', ['expense_report_id' => $expenseReport->getId()]);

        $data = [
            'id' => $expenseReport->getId(),
            'userId' => [
                'id' => $expenseReport->getUserId()->getId(),
                'name' => $expenseReport->getUserId()->getName(),
            ],
            'value' => $expenseReport->getValue(),
            'comment' => $expenseReport->getComment(),
            'vehicle' => $expenseReport->getVehicle() ? [
                'id' => $expenseReport->getVehicle()->getId(),
                'name' => $expenseReport->getVehicle()->getName(),
            ] : null,
            'state' => [
                'id' => $expenseReport->getState()->getId(),
                'value' => $expenseReport->getState()->getValue(),
            ],
            'date' => $expenseReport->getDate()->format('Y-m-d'), // Assurez-vous que la date est correctement formatée
            'expenseType' => [
                'id' => $expenseReport->getExpenseType()->getId(),
                'name' => $expenseReport->getExpenseType()->getName(),
            ] 
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
