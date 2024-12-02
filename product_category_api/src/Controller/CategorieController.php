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

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    // Récupérer toutes les catégories (GET)
    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categorieRepository->findAll();
        $categoriesArray = array_map(fn(Categorie $categorie) => $categorie->toArray(), $categories);

        return new JsonResponse($categoriesArray, 200, [], ['json_encode_options' => JSON_UNESCAPED_UNICODE]);
    }

    // Récupérer une catégorie par ID (GET)
    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        return new JsonResponse($categorie->toArray(), 200, []);
    }

    // Créer une nouvelle catégorie (POST)
    #[Route('', name: 'categorie_create', methods: ['POST'])]
    public function createCategorie(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['nom'])) {
            return new JsonResponse(['error' => 'Données invalides. Le champ "nom" est requis.'], 400);
        }

        $categorie = new Categorie();
        $categorie->setNom($data['nom']);

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $entityManager->persist($categorie);
        $entityManager->flush();

        return new JsonResponse($categorie->toArray(), 201);
    }

    // Mettre à jour une catégorie (PUT)
    #[Route('/{id}', name: 'categorie_update', methods: ['PUT'])]
    public function updateCategorie(
        int $id,
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nom'])) {
            $categorie->setNom($data['nom']);
        }

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $entityManager->flush();

        return new JsonResponse($categorie->toArray(), 200);
    }

    // Supprimer une catégorie (DELETE)
    #[Route('/{id}', name: 'categorie_delete', methods: ['DELETE'])]
    public function deleteCategorie(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie supprimée avec succès !'], 200);
    }
}
