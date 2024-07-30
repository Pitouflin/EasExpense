<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Vehicle;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;



#[Route('/vehicle')]
class VehicleController extends AbstractController
{
    #[Route('/', name: 'app_vehicle')]
    
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $vehicles = $em->getRepository(Vehicle::class)->findAll();
        return $this->json($vehicles, 200, [], [AbstractNormalizer::GROUPS => 'vehicle:read']);
    }
    
    public function index(): Response
    {
        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
        ]);
    }

    #[Route('/{id}', name: 'vehicle_show', methods: ['GET'])]
    public function show(Vehicle $vehicle, SerializerInterface $serializer): JsonResponse
    {
        $jsonVehicle = $serializer->serialize($vehicle, 'json', ['groups' => 'vehicle:read']);
        return new JsonResponse($jsonVehicle, Response::HTTP_OK, [], true);
    }
}
