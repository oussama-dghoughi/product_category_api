<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/produits')]
class ProduitController
{
    private $produitRepository;
    private $entityManager;
    private $validator;

    // Injection des dépendances via le constructeur
    public function __construct(ProduitRepository $produitRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->produitRepository = $produitRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    #[Route('', methods: ['GET'])]
public function index(): JsonResponse
    {
        dd('Index method reached');
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $produit = $this->produitRepository->find($id);

        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé.'], 404);
        }

        return new JsonResponse($produit);
    }

    #[Route('', name: 'create_produit', methods: ['POST'])]
    public function createProduit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier si la catégorie existe
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($data['categorie'] ?? null);
        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie associée non trouvée.'], 404);
        }

        // Créer un nouveau produit
        $produit = new Produit();
        $produit->setNom($data['nom'] ?? null)
                ->setDescription($data['description'] ?? null)
                ->setPrix($data['prix'] ?? null)
                ->setDateCreation(new \DateTime($data['dateCreation'] ?? 'now'))
                ->setCategorie($categorie);

        // Validation de l'entité
        $errors = $this->validator->validate($produit);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        // Sauvegarder le produit en base
        $this->entityManager->persist($produit);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Produit créé avec succès !'], 201);
    }

    #[Route('/{id}', name: 'update_produit', methods: ['PUT'])]
    public function updateProduit(int $id, Request $request): JsonResponse
    {
        // Trouver le produit par son ID
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé.'], 404);
        }

        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);

        // Mettre à jour la catégorie si elle existe
        if (isset($data['categorie'])) {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($data['categorie']);
            if (!$categorie) {
                return new JsonResponse(['error' => 'Catégorie associée non trouvée.'], 404);
            }
            $produit->setCategorie($categorie);
        }

        // Mise à jour des autres champs
        $produit->setNom($data['nom'] ?? $produit->getNom())
                ->setDescription($data['description'] ?? $produit->getDescription())
                ->setPrix($data['prix'] ?? $produit->getPrix())
                ->setDateCreation(isset($data['dateCreation']) ? new \DateTime($data['dateCreation']) : $produit->getDateCreation());

        // Validation de l'entité
        $errors = $this->validator->validate($produit);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        // Sauvegarder les modifications
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Produit mis à jour avec succès !'], 200);
    }

    #[Route('/{id}', name: 'delete_produit', methods: ['DELETE'])]
    public function deleteProduit(int $id): JsonResponse
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé.'], 404);
        }

        // Suppression du produit
        $this->entityManager->remove($produit);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Produit supprimé avec succès !'], 200);
    }
   
    
}
