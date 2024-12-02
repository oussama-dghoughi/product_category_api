<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des catégories
        $categorie1 = new Categorie();
        $categorie1->setNom('Électronique');
        $manager->persist($categorie1);

        $categorie2 = new Categorie();
        $categorie2->setNom('Vêtements');
        $manager->persist($categorie2);

        // Création des produits avec la date de création
        $produit1 = new Produit();
        $produit1->setNom('Téléphone')
                 ->setDescription('Smartphone dernière génération')
                 ->setPrix(999.99)
                 ->setCategorie($categorie1)
                 ->setDateCreation(new \DateTime());  // Ajout de la date de création
        $manager->persist($produit1);

        $produit2 = new Produit();
        $produit2->setNom('T-shirt')
                 ->setDescription('T-shirt en coton')
                 ->setPrix(19.99)
                 ->setCategorie($categorie2)
                 ->setDateCreation(new \DateTime());  // Ajout de la date de création
        $manager->persist($produit2);

        // Enregistrer toutes les entités dans la base de données
        $manager->flush();
    }
}