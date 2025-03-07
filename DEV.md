# Guide de développement - EcoDashboard

Ce document détaille les étapes de développement du tableau de bord interactif pour l'analyse de l'économie mondiale. Il présente une todolist structurée et la planification du développement.

## Table des matières
1. [Phases de développement](#phases-de-développement)
2. [Todolist détaillée](#todolist-détaillée)
3. [Structure finale du projet](#structure-finale-du-projet)
4. [Calendrier de développement](#calendrier-de-développement)
5. [Bonnes pratiques](#bonnes-pratiques)

## Phases de développement

Le développement sera divisé en 5 phases principales:

1. **Phase préparatoire**: Configuration de l'environnement, étude du modèle de données
2. **Phase de construction du backend**: Développement des fonctions d'accès aux données
3. **Phase de développement du frontend**: Création des interfaces utilisateur
4. **Phase d'intégration**: Connexion frontend-backend et implémentation des graphiques
5. **Phase de finalisation**: Tests, optimisation et documentation

## Todolist détaillée

### Phase 1: Préparation (Jours 1-2)
- [X] **1.1.** Mettre en place l'environnement de développement XAMPP
- [X] **1.2.** Initialiser le dépôt Git et créer la structure de base du projet
- [X] **1.3.** Étudier le schéma de la base de données SQLite `economie_mondiale.db`
  - [X] Identifier les tables et leurs relations
  - [X] Comprendre la nature des indicateurs disponibles
  - [X] Documenter le modèle de données dans un diagramme
- [ ] **1.4.** Créer le fichier de configuration `config.inc.php` pour la connexion à la base de données
- [ ] **1.5.** Configurer le `.htaccess` pour les règles de réécriture d'URL

### Phase 2: Backend (Jours 3-7)
- [ ] **2.1.** Développer les modèles de base (`models/`)
  - [ ] **2.1.1.** Créer le modèle `pays.php` pour la gestion des pays
    - [ ] Fonction pour récupérer la liste de tous les pays
    - [ ] Fonction pour récupérer les détails d'un pays spécifique
    - [ ] Fonction pour récupérer les pays par région
  - [ ] **2.1.2.** Créer le modèle `indicateur.php` pour la gestion des indicateurs
    - [ ] Fonction pour récupérer la liste des indicateurs disponibles
    - [ ] Fonction pour récupérer les valeurs d'un indicateur pour un pays spécifique
    - [ ] Fonction pour récupérer les moyennes d'un indicateur par région
  - [ ] **2.1.3.** Créer le modèle `data.php` pour les requêtes complexes
    - [ ] Fonction pour récupérer le top 10 des pays selon un indicateur
    - [ ] Fonction pour analyser les corrélations entre indicateurs
    - [ ] Fonction pour obtenir l'évolution d'un indicateur dans le temps par région
- [ ] **2.2.** Créer le module de connexion à la base de données dans `config.inc.php` avec approche Singleton
- [ ] **2.3.** Tester les requêtes SQL et optimiser les performances

### Phase 3: Frontend - Structure de base (Jours 8-12)
- [ ] **3.1.** Développer la structure HTML de base
  - [ ] Créer le template principal dans `index.php`
  - [ ] Organiser le tableau de bord en 4 sections selon le cahier des charges
- [ ] **3.2.** Développer les vues (`views/`)
  - [ ] **3.2.1.** Créer `dashboard.php` pour l'interface générale
  - [ ] **3.2.2.** Créer `infos_pays.php` pour les parties A et B
  - [ ] **3.2.3.** Créer `comparaison_pays.php` pour les parties C et D
- [ ] **3.3.** Développer les styles CSS (`public/style.css`)
  - [ ] Définir une palette de couleurs cohérente
  - [ ] Créer des classes pour les éléments de l'interface
  - [ ] Implémenter un design responsive

### Phase 4: Intégration (Jours 13-20)
- [ ] **4.1.** Développer les contrôleurs (`controllers/`)
  - [ ] **4.1.1.** Créer le contrôleur principal `indicateurs.php`
    - [ ] Fonction pour gérer les sélections d'utilisateur
    - [ ] Fonction pour préparer les données des graphiques
- [ ] **4.2.** Intégrer Chart.js et développer les visualisations (`public/graphiques.js`)
  - [ ] **4.2.1.** Créer les fonctions pour la partie A (Vue d'ensemble pays)
    - [ ] Afficher les indicateurs clés sous forme de fiches
  - [ ] **4.2.2.** Créer les fonctions pour la partie B (Évolution d'un indicateur)
    - [ ] Graphique linéaire d'évolution temporelle
  - [ ] **4.2.3.** Créer les fonctions pour la partie C (Comparaisons)
    - [ ] Diagrammes à barres pour les classements
    - [ ] Diagrammes camembert pour les corrélations
  - [ ] **4.2.4.** Créer les fonctions pour la partie D (Analyse par région)
    - [ ] Graphiques en aires empilées
    - [ ] Graphique linéaire pour le taux de chômage par région
- [ ] **4.3.** Ajouter l'interactivité aux visualisations
  - [ ] Implémentation des filtres et sélections
  - [ ] Ajout d'info-bulles au survol
  - [ ] Gestion des animations de transition

### Phase 5: Finalisation (Jours 21-25)
- [ ] **5.1.** Effectuer des tests complets de l'application
  - [ ] Tester avec différents jeux de données
  - [ ] Vérifier l'exactitude des calculs et des visualisations
- [ ] **5.2.** Optimiser les performances
  - [ ] Optimiser les requêtes SQL
  - [ ] Minimiser le chargement des ressources JavaScript et CSS
- [ ] **5.3.** Ajouter le storytelling aux visualisations
  - [ ] Rédiger des commentaires explicatifs pour chaque visualisation
  - [ ] Mettre en évidence les tendances importantes
- [ ] **5.4.** Finaliser la documentation
  - [ ] Compléter le rapport PDF
  - [ ] Commenter le code selon les normes PHPDoc
- [ ] **5.5.** Préparer la livraison finale
  - [ ] Vérifier que toutes les fonctionnalités sont implémentées
  - [ ] Créer l'archive compressée au format spécifié

## Structure finale du projet

```
EcoDashboard/
│-- models/
│   │-- pays.php                 # Gestion des entités pays
│   │-- indicateur.php           # Gestion des indicateurs économiques
│   │-- data.php                 # Requêtes complexes et analyses
│   │-- bdd.php                  # Classe de connexion à la base de données
│
│-- views/
│   │-- dashboard.php            # Vue principale du tableau de bord
│   │-- infos_pays.php           # Vue pour les parties A et B
│   │-- comparaison_pays.php     # Vue pour les parties C et D
│   │-- partials/                # Fragments de vue réutilisables
│       │-- header.php           # En-tête de page
│       │-- footer.php           # Pied de page
│       │-- selecteurs.php       # Éléments de sélection communs
│
│-- controllers/
│   │-- indicateurs.php          # Contrôleur principal
│   │-- api.php                  # Point d'entrée pour les requêtes AJAX
│
│-- public/
│   │-- css/
│       │-- style.css            # Styles principaux
│       │-- responsive.css       # Styles pour l'adaptation mobile
│   │-- js/
│       │-- graphiques.js        # Fonctions de génération des graphiques
│       │-- interactions.js      # Gestion des interactions utilisateur
│   │-- images/                  # Ressources graphiques
│   │-- lib/                     # Bibliothèques tierces
│       │-- chart.js/            # Chart.js
│
│-- config.inc.php               # Configuration de l'application
│-- index.php                    # Point d'entrée de l'application
│-- .htaccess                    # Configuration du serveur
│-- sql/
│   │-- economie_mondiale.db     # Base de données SQLite
│   │-- schema.sql               # Schéma de la base de données
│
│-- docs/
│   │-- rapport.pdf              # Rapport final du projet
│   │-- modele_donnees.png       # Diagramme du modèle de données
│
│-- README.md                    # Documentation principale
│-- DEV.md                       # Guide de développement (ce document)
│-- LICENCE                      # Licence du projet
```

## Calendrier de développement

Basé sur une échéance au 13/04:

| Semaine | Dates      | Objectifs                                           |
|---------|------------|-----------------------------------------------------|
| 1       | 13/03-19/03| Phase 1 (Préparation) & début Phase 2 (Backend)     |
| 2       | 20/03-26/03| Fin Phase 2 (Backend) & début Phase 3 (Frontend)    |
| 3       | 27/03-02/04| Fin Phase 3 (Frontend) & début Phase 4 (Intégration)|
| 4       | 03/04-09/04| Fin Phase 4 (Intégration) & Phase 5 (Finalisation)  |
| 5       | 10/04-13/04| Revue finale, tests et préparation de la livraison  |

## Bonnes pratiques

### Conventions de nommage
- **Fichiers PHP**: Nommez les fichiers en minuscules avec des underscores (ex: `info_pays.php`)
- **Fonctions**: Utilisez le camelCase pour les noms de fonctions (ex: `getPaysParRegion()`)
- **Variables**: Utilisez des noms explicites en camelCase (ex: `$listePays`)
- **Constantes**: Utilisez des majuscules avec underscores (ex: `DB_HOST`)

### Documentation du code
- Commentez chaque fonction avec le format PHPDoc
- Ajoutez des commentaires pour expliquer la logique métier complexe
- Documentez les requêtes SQL particulières

### Gestion de version
- Créez des branches par fonctionnalité
- Faites des commits fréquents avec des messages clairs
- Effectuez des merges réguliers vers la branche principale

### Sécurité
- Utilisez des requêtes préparées pour toutes les requêtes SQL
- Validez et filtrez toutes les entrées utilisateur
- N'exposez pas d'informations sensibles dans les messages d'erreur