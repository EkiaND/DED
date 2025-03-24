<?php

/**
 * Configuration de l'application (connexion à la base de données)
 *
 * Ce fichier configure la connexion à la base de données MySQL utilisée par l'application.
 *
 * Connexion à la base de données:
 * - La fonction `getBDD` établit une connexion à la base de données MySQL en utilisant `mysqli`.
 * - En cas d'erreur, un message est affiché et l'exécution est arrêtée.
 *
 * Variables:
 * - $db_host (string): Adresse du serveur de base de données.
 * - $db_name (string): Nom de la base de données.
 * - $db_user (string): Nom d'utilisateur pour la connexion.
 * - $db_pass (string): Mot de passe pour la connexion.
 *
 * Retour:
 * - mysqli: Instance de connexion MySQLi.
 *
 * Exceptions:
 * - Arrête l'exécution en cas d'erreur de connexion.
 */

function getBDD() {
    $db_host = "localhost";
    $db_name = "economie_mondiale";
    $db_user = "root";
    $db_pass = "";

    // Établir la connexion à la base de données
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Vérifier les erreurs de connexion
    if (!$conn) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    return $conn;
}
?>