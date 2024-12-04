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
    private ProduitRepository $produitRepository;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(
        ProduitRepository $produitRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->produitRepository = $produitRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $produits = $this->produitRepository->findAll();
        $data = array_map(fn(Produit $produit) => $produit->toArray(), $produits);

        return new JsonResponse($data, 200);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function showProduit(int $id): JsonResponse
    {
        $produit = $this->produitRepository->find($id);

        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé.'], 404);
        }

        return new JsonResponse($produit->toArray(), 200);
    }

    // Ajouter un produit (POST)
    #[Route('', methods: ['POST'])]
public function addProduit(Request $request): JsonResponse
{
    // Récupération des données envoyées dans la requête
    $data = json_decode($request->getContent(), true);

    // Vérification si toutes les données nécessaires sont présentes
    if (!isset($data['nom'], $data['description'], $data['prix'], $data['dateCreation'], $data['categorie'])) {
        return new JsonResponse(['message' => 'Données invalides'], 400);
    }

    // Récupérer la catégorie en fonction de l'ID envoyé
    $categorie = $this->entityManager->getRepository(Categorie::class)->find($data['categorie']);
    
    // Vérification si la catégorie existe
    if (!$categorie) {
        return new JsonResponse(['message' => 'Catégorie introuvable'], 404);
    }

    // Création du produit
    $produit = new Produit();
    $produit->setNom($data['nom']);
    $produit->setDescription($data['description']);
    $produit->setPrix($data['prix']);
    $produit->setDateCreation(new \DateTime($data['dateCreation']));
    $produit->setCategorie($categorie); // Assigner la catégorie trouvée

    // Enregistrement du produit dans la base de données
    $this->entityManager->persist($produit);
    $this->entityManager->flush();

    // Retourner le produit ajouté sous forme de JSON
    return new JsonResponse($produit->toArray(), 201);
}



    #[Route('/{id}', name: 'update_produit', methods: ['PUT'])]
    public function updateProduit(int $id, Request $request): JsonResponse
    {
        $produit = $this->produitRepository->find($id);

        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['categorie'])) {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($data['categorie']);
            if (!$categorie) {
                return new JsonResponse(['error' => 'Catégorie associée non trouvée.'], 404);
            }
            $produit->setCategorie($categorie);
        }

        $produit->setNom($data['nom'] ?? $produit->getNom())
                ->setDescription($data['description'] ?? $produit->getDescription())
                ->setPrix(isset($data['prix']) ? (float)$data['prix'] : $produit->getPrix())
                ->setDateCreation(isset($data['dateCreation']) ? new \DateTime($data['dateCreation']) : $produit->getDateCreation());

        $errors = $this->validator->validate($produit);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], 400);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Produit mis à jour avec succès !', 'produit' => $produit->toArray()], 200);
    }

    #[Route('/{id}', name: 'delete_produit', methods: ['DELETE'])]
    public function deleteProduit(int $id): JsonResponse
    {
        $produit = $this->produitRepository->find($id);

        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé.'], 404);
        }

        $this->entityManager->remove($produit);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Produit supprimé avec succès !'], 200);
    }
}
