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
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;

    // Constructor injection for dependencies
    public function __construct(CategorieRepository $categorieRepository, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->categorieRepository = $categorieRepository;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    // Récupérer toutes les catégories (GET)
    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categorieRepository->findAll();
        $categoriesArray = array_map(fn(Categorie $categorie) => $categorie->toArray(), $categories);

        return new JsonResponse($categoriesArray, 200);
    }

    // Récupérer une catégorie par ID (GET)
    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        return new JsonResponse($categorie->toArray(), 200);
    }

    // Créer une nouvelle catégorie (POST)
    #[Route('', name: 'categorie_create', methods: ['POST'])]
    public function createCategorie(Request $request): JsonResponse
    {
        // Récupération des données envoyées
        $data = json_decode($request->getContent(), true);

        // Vérification des données requises
        if (!$data || !isset($data['nom'])) {
            return new JsonResponse(['error' => 'Données invalides. Le champ "nom" est requis.'], 400);
        }

        // Création de l'entité Categorie
        $categorie = new Categorie();
        $categorie->setNom($data['nom']);

        // Validation de l'entité
        $errors = $this->validator->validate($categorie);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        // Enregistrement de la catégorie en base de données
        try {
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de l\'ajout de la catégorie : ' . $e->getMessage()], 500);
        }

        // Retour du résultat
        return new JsonResponse($categorie->toArray(), 201);
    }

    // Mettre à jour une catégorie (PUT)
    #[Route('/{id}', name: 'categorie_update', methods: ['PUT'])]
    public function updateCategorie(int $id, Request $request): JsonResponse
    {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nom'])) {
            $categorie->setNom($data['nom']);
        }

        // Validation de l'entité
        $errors = $this->validator->validate($categorie);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        // Enregistrement de la catégorie mise à jour
        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la mise à jour de la catégorie : ' . $e->getMessage()], 500);
        }

        return new JsonResponse($categorie->toArray(), 200);
    }

    // Supprimer une catégorie (DELETE)
    #[Route('/{id}', name: 'categorie_delete', methods: ['DELETE'])]
    public function deleteCategorie(int $id): JsonResponse
    {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée.'], 404);
        }

        try {
            $this->entityManager->remove($categorie);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la suppression de la catégorie : ' . $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => 'Catégorie supprimée avec succès !'], 200);
    }
}
