<div align="center">

# SAE - Tableau de Bord Interactif pour l'Analyse de l'Économie Mondiale

## Description

Ce projet vise à concevoir un tableau de bord interactif dédié à l’analyse de l’économie mondiale. Il permettra de visualiser les principaux indicateurs économiques (PIB, croissance démographique, espérance de vie, développement humain, consommation énergétique, etc.) afin de mettre en évidence les grandes tendances et les relations entre ces facteurs. À travers des visualisations claires et dynamiques, cet outil offrira une compréhension approfondie des évolutions économiques globales, facilitant ainsi l’identification des dynamiques de croissance, des disparités régionales et des opportunités de développement.

</div>

<div align="center">

## Fonctionnalités

</div>

- Sélection d’un pays et vue d’ensemble des indicateurs économiques.
- Analyse de l’évolution d’un indicateur clé sur plusieurs décennies.
- Comparaison des pays en fonction de divers classements et corrélations.
- Analyse des indicateurs économiques par région.
- Visualisations interactives et dynamiques des données économiques.

<div align="center">

## Technologies

</div>

- **Langage** : PHP, HTML, CSS, JavaScript
- **Frameworks / Bibliothèques** : Chart.js
- **Base de données** : SQLite
- **Outils de visualisation** : Chart.js
- **Gestion de versions** : Git
- **Serveur local** : XAMPP

<div align="center">

## Installation

</div>

### Prérequis

- **OS** : Windows, macOS, Linux
- **Dépendances** : PHP, SQLite
- **Serveur local** : XAMPP

### Étapes d'installation

> [!TIP]
> Vous pouvez passer par l'application GitHub Desktop si vous n'êtes pas à l'aise avec le terminal.

1. Cloner le projet :
   ```sh
   git clone https://github.com/EkiaND/DED.git
   ```
2. Accéder au répertoire du projet :
   ```sh
   cd DED
   ```
3. Configurer la base de données :
   - Assurez-vous que le fichier `economie_mondiale.db` est présent dans le répertoire `sql/`.
   - Configurez les paramètres de connexion à la base de données dans `config.inc.php` pour utiliser SQLite.
4. Démarrer XAMPP et placer le projet dans le répertoire `htdocs` de XAMPP.
5. Accéder à l'application via `http://localhost/DED`.

> [!CAUTION]
> Si vous ne respectez pas ces étapes, attendez-vous à ce que le projet ne fonctionne pas correctement.

<div align="center">

## Structure du projet

</div>

```
DED/
│-- models/
│   │-- pays.php                 # Gestion des entités pays
│   │-- indicateur.php           # Gestion des indicateurs économiques
│   │-- data.php                 # Requêtes complexes et analyses
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
│
│-- public/
│   │-- css/
│       │-- style.css            # Styles principaux
│       │-- responsive.css       # Styles pour l'adaptation mobile
│   │-- js/
│       │-- graphiques.js        # Fonctions de génération des graphiques
│       │-- interactions.js      # Gestion des interactions utilisateur
│   │-- images/                  # Ressources graphiques
│
│-- config.inc.php               # Configuration de l'application (connexion à la base de données)
│-- index.php                    # Point d'entrée de l'application
│-- .htaccess                    # Configuration du serveur
│-- sql/
│   │-- economie_mondiale.db     # Base de données SQLite
│
│-- docs/
│   │-- rapport.pdf              # Rapport final du projet
│   │-- MCD.png                  # Diagramme du Modèle Conceptuel de Données
│   │-- MLD.png                  # Diagramme du Modèle Logique de Données
|
│-- README.md                    # Documentation principale
│-- DEV.md                       # Guide de développement
│-- LICENCE                      # Licence du projet      
```

<div align="center">

## Auteurs et répartition des tâches

</div>

### Auteurs
- **LESUEUR Romain**
- **YON Anthony**
- **DIOP Mandir**

### Répartition des tâches
- **X** :
  - Développement des fonctionnalités de sélection et vue d’ensemble des indicateurs.
- **X** :
  - Analyse de l’évolution des indicateurs et comparaison des pays.
- **X** :
  - Analyse des indicateurs par région et visualisations interactives.

<div align="center">

## Licence

[![MIT Image](https://upload.wikimedia.org/wikipedia/commons/0/0c/MIT_logo.svg)](https://fr.wikipedia.org/wiki/Licence_MIT)

</div>
