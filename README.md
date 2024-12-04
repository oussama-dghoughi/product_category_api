
# **Product Category API**

Une plateforme pour la gestion des catégories et des produits, construite avec **React** pour le frontend et **Symfony** pour le backend.

---

## **Table des matières**

1. [Introduction](#introduction)  
2. [Fonctionnalités](#fonctionnalités)  
3. [Prérequis](#prérequis)  
4. [Installation](#installation)  
   - [Frontend](#installation-du-frontend)  
   - [Backend (Product Category API)](#installation-du-backend-product_category_api)  
5. [Utilisation](#utilisation)  
6. [Choix Techniques](#choix-techniques)  
7. [Structure du Projet](#structure-du-projet)  
8. [Tests et Validation](#tests-et-validation)
9. [Démo](#Démo)
10. [Contributions](#contributions)   

---

## **Introduction**

Ce projet est une application web complète pour gérer :  
- Les **catégories** de produits.  
- Les **produits** eux-mêmes avec leurs attributs associés.  

Il est conçu pour offrir une interface utilisateur moderne et intuitive, connectée à une API backend robuste et sécurisée.

---

## **Fonctionnalités**

### **Frontend**
- Interface utilisateur intuitive avec navigation fluide grâce à **React Router**.  
- Affichage des listes de catégories et produits.  
- Actions CRUD pour ajouter, modifier ou supprimer des catégories et produits.  

### **Backend (Product Category API)**  
- API REST pour gérer les catégories et produits.  
- Validation des données en entrée avec des règles strictes.  
- Gestion des relations entre les entités (catégories et produits).  
- Documentation Swagger/OpenAPI pour faciliter l'intégration.  

---

## **Prérequis**

- **npm** ou **yarn** pour le frontend.  
- **PHP** (v8.1 ou supérieur) avec **Composer** pour le backend.  
- **MySQL** pour la base de données.  
- **Postman** ou **Swagger UI** pour tester les endpoints de l'API (optionnel).  

---

## **Installation**

### **Installation du Frontend**

1. **Cloner le dépôt** :  
   ```bash
   git clone https://github.com/votre-utilisateur/votre-projet.git
   cd votre-projet/frontend
   ```

2. **Installer les dépendances** :  
   ```bash
   npm install
   ```

3. **Configurer les variables d'environnement** :  
   Créez un fichier `.env` à la racine :  
   ```env
   REACT_APP_API_URL=http://localhost:8000
   ```

4. **Lancer le projet** :  
   ```bash
   npm start
   ```

   L'application frontend sera disponible sur `http://localhost:3000`.

---

### **Installation du Backend (Product Category API)**

1. **Accéder au répertoire backend** :  
   ```bash
   cd ../product_category_api
   ```

2. **Installer les dépendances PHP avec Composer** :  
   ```bash
   composer install
   ```

3. **Configurer la base de données** :  
   - Créez un fichier `.env.local` :  
     ```env
     DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/nom_base"
     ```

4. **Créer et migrer la base de données** :  
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Démarrer le serveur Symfony** :  
   ```bash
   symfony server:start
   ```

   L'API backend sera accessible sur `http://localhost:8000`.

---

## **Utilisation**

1. Lancez le **backend** avec :  
   ```bash
   symfony server:start
   ```

2. Lancez le **frontend** avec :  
   ```bash
   npm start
   ```

3. Accédez à l'application sur [http://localhost:3000](http://localhost:3000).

---

## **Choix Techniques**

### **Frontend**
- **React et Tailwind CSS** : Pour construire une interface réactive et modulaire.  
- **Axios** : Simplifie les appels API.  
- **React Router** : Gestion de la navigation dynamique.  

### **Backend**
- **Symfony** : Framework PHP flexible et performant pour créer des APIs REST.  
- **Doctrine ORM** : Gestion efficace des interactions avec MySQL.  
- **Validation des données** : Utilisation des contraintes Symfony Validator.  

---

## **Structure du Projet**

### **Frontend**

```plaintext
frontend/
│
├── src/
│   ├── components/        # Composants réutilisables
│   ├── services/          # Gestion des appels API
│   ├── App.js             # Point d'entrée de l'application
│   └── index.js           # Initialisation React
└── package.json
```

### **Backend**

```plaintext
product_category_api/
│└── migrations/        # Scripts de migration
├── src/
│   ├── Controller/        # Contrôleurs pour les endpoints
│   ├── Entity/            # Entités Doctrine
│   ├── Repository/        # Requêtes spécifiques
│   
├── config/                # Configuration Symfony
├── public/                # Point d'entrée (index.php)
├── .env                   # Variables d'environnement
└── composer.json
```

---

## **Tests et Validation**

### **Frontend**
- Tests manuels avec les navigateurs modernes pour vérifier l'interface utilisateur.  

### **Backend**
- Tests des endpoints API avec **Postman** ou **Swagger UI**.  
- Validation des migrations de base de données et des contraintes.  

---
# **Démo**

https://youtu.be/TqWf3M1sHgM

## **Contributions**

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une **issue** ou une **pull request**.  


---


