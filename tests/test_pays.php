<?php

/**
 * Ce fichier est un script de test pour les fonctions définies dans le fichier pays.php.
 * Il inclut des tests pour les fonctions suivantes :
 * 
 * - getPays() : Récupère la liste de tous les pays.
 * - getPaysParRegion($region) : Récupère la liste des pays appartenant à une région spécifique.
 * - getDetailsPays($nomPays) : Récupère les détails d'un pays spécifique en fonction de son code.
 * 
 * Fonctions utilitaires :
 * - afficherResultat($titre, $resultat, $limite = 5) : Affiche les résultats de manière lisible
 *   avec une limite optionnelle sur le nombre de résultats affichés.
 * 
 * Tests effectués :
 * - Test de la fonction getPays pour vérifier la récupération de tous les pays.
 * - Test de la fonction getPaysParRegion avec une région spécifique ("East Asia & Pacific").
 * - Test de la fonction getDetailsPays avec un code pays spécifique ("FRA").
 * 
 * Note : Assurez-vous que le fichier pays.php est correctement inclus et que les fonctions
 * testées sont définies et fonctionnelles.
 */

// Inclure le fichier contenant les fonctions
require_once '../models/pays.php';

// Fonction utilitaire pour afficher les résultats de manière lisible
function afficherResultat($titre, $resultat, $limite = 5) {
    echo "<h2>$titre</h2>";
    if (empty($resultat)) {
        echo "<p>Aucun résultat trouvé.</p>";
    } else {
        echo "<pre>";
        // Limiter l'affichage à $limite résultats
        $affichage = array_slice($resultat, 0, $limite);
        print_r($affichage);
        if (count($resultat) > $limite) {
            echo "\n...et " . (count($resultat) - $limite) . " résultat(s) supplémentaire(s) non affiché(s).";
        }
        echo "</pre>";
    }
}

// Tester la fonction getPays
afficherResultat("Test de la fonction getPays", getPays());

// Tester la fonction getPaysParRegion
$region = "East Asia & Pacific";
afficherResultat("Test de la fonction getPaysParRegion (Région : $region)", getPaysParRegion($region));

// Tester la fonction getDetailsPays
$nomPays = "FRA";
afficherResultat("Test de la fonction getDetailsPays (Pays : $nomPays)", getDetailsPays($nomPays));
?>