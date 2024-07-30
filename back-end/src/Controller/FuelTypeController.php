<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\FuelType;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/fuel_type')]
class FuelTypeController extends AbstractController
{
    #[Route('/', name: 'app_fuel_type')]

    public function list(EntityManagerInterface $em): JsonResponse
    {
        $fuelTypes = $em->getRepository(FuelType::class)->findAll();
        return $this->json($fuelTypes, 200, [], [AbstractNormalizer::GROUPS => 'fuelType:read']);
    }

    public function index(): Response
    {
        return $this->render('fuel_type/index.html.twig', [
            'controller_name' => 'FuelTypeController',
        ]);
    }

    #[Route('/{id}', name: 'fuelType_show', methods: ['GET'])]
    public function show(FuelType $fuelType, SerializerInterface $serializer): JsonResponse
    {
        $jsonFuelType = $serializer->serialize($fuelType, 'json', ['groups' => 'fuelType:read']);
        return new JsonResponse($jsonFuelType, Response::HTTP_OK, [], true);
    }
}
