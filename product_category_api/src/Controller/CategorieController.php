<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/categories')]
class CategorieController
{
    private CategorieRepository $categorieRepository;

    // Injection du CategorieRepository via le constructeur
    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    // Route pour récupérer toutes les catégories (GET)
    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categorieRepository->findAll();
        return new JsonResponse($categories);
    }

    // Route pour récupérer une catégorie par son ID (GET)
    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        // Récupérer la catégorie par son ID
        $categorie = $this->categorieRepository->find($id);

        // Si la catégorie n'est pas trouvée, renvoyer une erreur 404
        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        // Sinon, retourner la catégorie en réponse
        return new JsonResponse($categorie);
    }


    // Route pour créer une nouvelle catégorie (POST)
    #[Route('', name: 'categorie_create', methods: ['POST'])]
    public function createCategorie(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $categorie = new Categorie();
        $categorie->setNom($data['nom'] ?? null);

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $entityManager->persist($categorie);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie créée avec succès !'], 201);
    }

    // Route pour mettre à jour une catégorie (PUT)
    #[Route('/{id}', name: 'categorie_update', methods: ['PUT'])]
    public function updateCategorie(
        int $id,
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $categorie->setNom($data['nom'] ?? $categorie->getNom());

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie mise à jour avec succès !'], 200);
    }

    // Route pour supprimer une catégorie (DELETE)
    #[Route('/{id}', name: 'categorie_delete', methods: ['DELETE'])]
    public function deleteCategorie(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie supprimée avec succès !'], 200);
    }
}
