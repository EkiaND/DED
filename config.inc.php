<?php

/**
 * Configuration de l'application (connexion à la base de données)
 *
 * Ce fichier configure la connexion à la base de données SQLite utilisée par l'application.
 *
 * Chemin vers la base de données SQLite:
 * - Le chemin est défini en utilisant la constante magique __DIR__ pour obtenir le répertoire actuel
 *   et en ajoutant le chemin relatif vers le fichier de la base de données.
 *
 * Connexion à la base de données:
 * - Une nouvelle instance de PDO est créée pour se connecter à la base de données SQLite.
 * - Les options de PDO sont configurées pour:
 *   - Lever des exceptions en cas d'erreurs (PDO::ERRMODE_EXCEPTION).
 *   - Récupérer les résultats sous forme de tableaux associatifs par défaut (PDO::FETCH_ASSOC).
 *
 * Gestion des erreurs:
 * - Si une erreur de connexion survient, un message d'erreur est affiché et l'exécution du script est arrêtée.
 *
 * Variables:
 * - $dbPath (string): Chemin vers le fichier de la base de données SQLite.
 * - $pdo (PDO): Instance de PDO utilisée pour la connexion à la base de données.
 *
 * Exceptions:
 * - PDOException: Capture les erreurs liées à la connexion à la base de données et affiche un message d'erreur.
 */


 ################# ANCIENNE VERSION SQLITE #################
 /*
// Configuration de l'application (connexion à la base de données)

// Chemin vers la base de données SQLite
$dbPath = __DIR__ . '/sql/economie_mondiale.db';

try {
    // Création d'une nouvelle instance de PDO pour la connexion à la base de données SQLite
    $pdo = new PDO('sqlite:' . $dbPath);
    // Configuration des options de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Gestion des erreurs de connexion
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
*/

function getBDD(){
    $db_host = "localhost";
    $db_name = "economie_mondiale"
    $db_user = "root"
    $db_pass = "";
    $conn = mysqli_connect($db_host,$db_user,$db_pass,$db_name);

    if mysqli_connect_error() {
        echo mysqli_connect_error();
        exit;
    }

    return $conn;
}
?>
