<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategorieController
{
    // Route pour récupérer toutes les catégories (GET)
    #[Route('', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        return new JsonResponse($categories);
    }

    // Route pour récupérer une catégorie par son ID (GET)
    #[Route('/{id}', methods: ['GET'])]
    public function show(Categorie $categorie): JsonResponse
    {
        return new JsonResponse($categorie);
    }

    // Route pour créer une nouvelle catégorie (POST)
    #[Route('/categories', name: 'create_categorie', methods: ['POST'])]
public function createCategorie(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
{
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
    #[Route('/categories/{id}', name: 'update_categorie', methods: ['PUT'])]
public function updateCategorie(int $id, Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
{
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

#[Route('/categories/{id}', name: 'delete_categorie', methods: ['DELETE'])]
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
