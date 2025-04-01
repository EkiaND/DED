<?php
// Fichier pour gérer les entités indicateurs

//require_once __DIR__ . '/../logger/logger.php'; // Inclure le logger
require_once __DIR__ . '/../config.inc.php'; // Inclure le fichier de configuration

/**
 * Récupérer la liste des indicateurs disponibles.
 *
 * Cette fonction utilise la table système `INFORMATION_SCHEMA.COLUMNS` pour récupérer dynamiquement
 * les colonnes de la table `indicateurs` qui représentent des indicateurs, tout en excluant les colonnes
 * techniques comme `id`, `code_pays`, et `annee`.
 *
 * @return array Liste des indicateurs sous forme de tableaux associatifs (id, nom de l'indicateur).
 *               Retourne un tableau vide en cas d'erreur.
 */
function getIndicateurs() {
    try {
        // 1. Obtenir une connexion à la base de données
        $conn = getBDD(); // Appelle la fonction `getBDD()` pour établir une connexion MySQL.

        // 2. Définir la requête SQL
        // La table système `INFORMATION_SCHEMA.COLUMNS` contient des métadonnées sur toutes les colonnes
        // de toutes les tables de la base de données. Cette requête récupère les noms des colonnes
        // de la table `indicateurs`, tout en excluant les colonnes `id`, `code_pays`, et `annee`.
        $req = "SELECT COLUMN_NAME AS nom_indicateur 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = 'indicateurs' AND COLUMN_NAME NOT IN ('id', 'code_pays', 'annee');";

        // 3. Préparer la requête SQL
        // La préparation de la requête permet d'éviter les injections SQL et d'assurer la sécurité.
        $stmt = mysqli_prepare($conn, $req);

        // 4. Vérifier si la préparation a échoué
        if ($stmt === false) {
            // Si la préparation échoue, lever une exception avec le message d'erreur de MySQL.
            throw new Exception(mysqli_error($conn));
        }

        // 5. Exécuter la requête SQL
        // Exécute la requête préparée. Si l'exécution échoue, une exception est levée.
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        // 6. Récupérer les résultats de la requête
        // `mysqli_stmt_get_result` récupère les résultats de la requête sous forme d'objet MySQLi.
        $res = mysqli_stmt_get_result($stmt);

        // 7. Retourner les résultats sous forme de tableau associatif
        // `mysqli_fetch_all` convertit les résultats en tableau associatif.
        // Si aucun résultat n'est trouvé, retourne un tableau vide grâce à l'opérateur `?:`.
        return mysqli_fetch_all($res, MYSQLI_ASSOC) ?: [];
    } catch (Exception $e) {
        // 8. En cas d'erreur, enregistrer l'erreur dans les logs
        // Appelle la fonction `logError` pour enregistrer l'erreur avec le message, le fichier, et la ligne.
        logError($e->getMessage(), __FILE__, __LINE__);

        // 9. Retourner un tableau vide en cas d'erreur
        // Retourne un tableau vide pour éviter de casser le programme appelant.
        return [];
    }
}

/**
 * Récupérer les valeurs d'un indicateur pour un pays spécifique sur plusieurs années.
 *
 * @param string $idIndicateur Nom de l'indicateur (ex. 'pib', 'esperance_vie').
 * @param string $codePays Code du pays (ex. 'FRA').
 * @return array Liste des valeurs de l'indicateur pour le pays sur plusieurs années.
 *               Retourne un tableau vide en cas d'erreur.
 */
function getValeursIndicateur($idIndicateur, $codePays) {
    try {
        $conn = getBDD();
        $query = "SELECT annee, $idIndicateur AS valeur 
                  FROM indicateurs 
                  WHERE code_pays = ? AND $idIndicateur IS NOT NULL";

        $stmt = mysqli_prepare($conn, $query);
        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $codePays);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($res, MYSQLI_ASSOC) ?: [];
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [];
    }
}

/**
 * Récupérer la moyenne d'un indicateur pour une région donnée.
 *
 * @param string $idIndicateur Nom de l'indicateur (ex. 'pib', 'esperance_vie').
 * @param string $idRegion ID de la région.
 * @return float|null Moyenne de l'indicateur pour la région. Retourne null en cas d'erreur.
 */
function getMoyenneIndicateurParRegion($idIndicateur, $idRegion) {
    
}

/**
 * Récupérer les indicateurs disponibles pour une année spécifique.
 *
 * @param int $annee Année pour laquelle récupérer les indicateurs.
 * @return array Liste des indicateurs sous forme de tableaux associatifs (nom_indicateur, valeur).
 *               Retourne un tableau vide en cas d'erreur.
 */
function getIndicateursParAnnee($annee) {
    
}
?>