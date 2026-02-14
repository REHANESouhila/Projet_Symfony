# Projet Symfony – Gestion d'Établissements et Commentaires

Ce projet est une application web développée avec Symfony, illustrant l'utilisation avancée du framework pour créer une application complète avec gestion de contenus et interactions utilisateur.

## Description

L'application permet aux utilisateurs de consulter des établissements, de laisser des commentaires et de gérer ces derniers de manière sécurisée. Le projet met en œuvre les bonnes pratiques suivantes :

- Architecture MVC (Modèle-Vue-Contrôleur) avec Symfony
- Gestion des entités et de la base de données via Doctrine ORM
- Création et validation de formulaires avec Symfony Forms
- Utilisation des Routes et contrôleurs pour organiser les fonctionnalités
- Gestion des états d'un établissement via Enums
- Gestion des fixtures pour préremplir la base de données

L'objectif est de proposer une application web robuste et maintenable, tout en démontrant une maîtrise de la programmation orientée objet en PHP et des concepts clés de Symfony.

## Fonctionnalités principales

- Affichage des commentaires par établissement
- Ajout, modification et suppression de commentaires
- Système de confirmation pour la suppression d'un commentaire
- Gestion des états des établissements (ouvert, à ouvrir, à fermer) via une Enum
- Préparation des données initiales avec Doctrine Fixtures
- Prise en charge des environnements de développement et production

## Technologies utilisées

- **Symfony (6)** : Framework PHP pour le développement structuré
- **PHP (POO)** : Logique métier et architecture orientée objet
- **Twig** : Templates pour le rendu dynamique des vues
- **Doctrine ORM** : Gestion des entités et interaction avec la base de données
- **HTML5 & CSS3** : Structure et style des pages
- **JavaScript** (optionnel) : Interactions côté client
- **PHPUnit / Symfony PHPUnit Bridge** : Tests unitaires et fonctionnels
