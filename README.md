# Restaurant Management System

## Description
Ce projet est un site web complet pour la gestion d'un restaurant, permettant aux clients de :
- Réserver des tables.
- Consulter le menu avec les prix et descriptions.
- Passer des commandes en ligne pour les plats à emporter.

Et aux administrateurs de :
- Gérer les réservations et les commandes.
- Ajouter des tables et des plats au menu.
- Mettre à jour les statuts des commandes.

## Fonctionnalités Principales

### Pour les clients :
1. **Réservation de Tables** :
   - Les clients peuvent réserver une table via un formulaire en ligne.
   - Les tables disponibles sont affichées dynamiquement.

2. **Consultation du Menu** :
   - Le menu est généré dynamiquement à partir de la base de données.
   - Les plats incluent une image, un nom, une description, un prix et une catégorie (entrée, plat principal, dessert).

3. **Commandes en Ligne** :
   - Les clients peuvent commander des plats en sélectionnant un item du menu.
   - Chaque commande est ajoutée à la base de données avec un statut initial "En cours de préparation".

### Pour les administrateurs :
1. **Accès Sécurisé** :
   - Les administrateurs accèdent à une interface dédiée via une page de connexion sécurisée (login.php).

2. **Gestion des Réservations** :
   - Liste des réservations.
   - Suppression des réservations (changement du statut des tables en "Disponible").

3. **Gestion des Commandes** :
   - Liste des commandes.
   - Mise à jour du statut des commandes ("En cours de préparation" à "Livrée").
   - Option de suppression des commandes.

4. **Gestion du Menu** :
   - Ajout de nouveaux plats dans le menu.
   - Liste des plats avec leurs détails.

5. **Gestion des Tables** :
   - Ajout de nouvelles tables.
   - Liste des tables avec leurs statuts (Disponible/Réservée).

## Technologies Utilisées
- **Frontend** : HTML, CSS (style.css)
- **Backend** : PHP
- **Base de Données** : MySQL

## Structure des Fichiers
- `index.php` : Page principale contenant les fonctionnalités client (réservation, menu, commande).
- `admin.php` : Interface d'administration pour gérer les réservations, commandes, tables, et menu.
- `login.php` : Page de connexion pour accéder à l'interface admin.
- `reservation.php` : Script pour gérer les réservations des clients.
- `process_commande.php` : Script pour traiter les commandes des clients.
- `style.css` : Feuille de style principale pour le site.

## Installation
1. Clonez ce dépôt ou téléchargez les fichiers.
2. Importez la base de données MySQL à l'aide du fichier `restaurant.sql`.
3. Configurez vos informations de connexion à la base de données dans chaque fichier contenant la ligne `mysqli_connect()`.
4. Placez tous les fichiers dans votre serveur local (e.g., XAMPP, WAMP) dans le dossier racine du serveur (e.g., `htdocs` pour XAMPP).
5. Accédez au site via votre navigateur : `http://localhost/index.php`.

## Instructions pour les Administrateurs
1. Accédez à la page de connexion : `http://localhost/login.php`.
2. Connectez-vous avec un nom d'utilisateur et un mot de passe présents dans la table `admin` de la base de données.
3. Utilisez l'interface pour gérer les commandes, réservations, tables, et menu.

## Auteurs
- **Ghassen** : Développement complet du projet, incluant frontend, backend, et base de données.

