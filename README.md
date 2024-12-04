product_category_api

Une plateforme pour la gestion des catégories et des produits, construite avec React pour le frontend et Symfony pour le backend.

Table des matières

Introduction

Fonctionnalités

Prérequis

Installation

Frontend

product_category_api (Backend)

Utilisation

Choix Techniques

Structure du Projet

Contributions

Introduction

Ce projet est une application web complète qui permet :

De gérer des catégories et des produits via une interface utilisateur moderne.
De connecter un frontend React avec un backend Symfony pour fournir des fonctionnalités robustes et sécurisées.

Fonctionnalités

Frontend

Interface utilisateur intuitive pour gérer les catégories et produits.
Navigation fluide entre les différentes sections grâce à React Router.

product_category_api(Backend)

API REST pour gérer les actions CRUD (Create, Read, Update, Delete).
Validation des données avant l'enregistrement.

Prérequis

Node.js (v16 ou supérieur) et npm ou yarn pour le frontend.

PHP (v8.1 ou supérieur) avec Composer pour le backend.

MySQL pour la base de données.

Postman (optionnel) pour tester les endpoints API.

Installation

Installation du Frontend

Cloner le dépôt :

git clone https://github.com/votre-utilisateur/votre-projet.git

cd votre-projet/frontend

Installer les dépendances :


npm install

Configurer les variables d'environnement :

Créez un fichier .env :

REACT_APP_API_URL=http://localhost:8000

Lancer le projet :


npm start
L'application frontend sera disponible sur http://localhost:8000.

Installation du Backend

Accéder au répertoire backend :


cd ../product_category_api

Installer les dépendances PHP avec Composer :


composer install

Configurer la base de données :

Créez un fichier .env.local si nécessaire.

Configurez les paramètres MySQL :

env

DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/nom_base"

Créer et migrer la base de données :


php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

Démarrer le serveur Symfony :

symfony server:start

L'API backend sera accessible sur http://localhost:8000.


Utilisation

Lancez le backend avec symfony server:start.

Lancez le frontend avec npm start.

Accédez à l'application sur http://localhost:8000.

Choix Techniques

Frontend

React : Pour sa modularité et sa simplicité d'intégration avec des APIs REST.

Axios : Pour les appels API.

CSS Modules : Gestion localisée des styles.

Backend

Symfony : Framework robuste pour créer des APIs sécurisées et performantes.

Doctrine ORM : Pour l'interaction avec la base de données MySQL.

Validation des données : Utilisation de ValidatorInterface pour vérifier la conformité des entrées utilisateur.

Structure du Projet

Frontend

frontend/
│
├── src/
│   ├── components/        # Composants réutilisables
│   ├── pages/             # Pages principales
│   ├── services/          # Gestion des appels API
│   ├── App.js             # Point d'entrée
│   └── index.js           # Initialisation React
└── package.json
Backend

product_category_api/
│└── migrations/            # Scripts de migration
├── src/
│   ├── Controller/        # Contrôleurs Symfony
│   ├── Entity/            # Entités Doctrine
│   ├── Repository/        # Requêtes spécifiques
│         
├── config/                # Configuration Symfony
├── .env                   # Variables d'environnement
└── composer.json

Contributions

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request.



