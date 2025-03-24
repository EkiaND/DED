<?php
// Fichier pour gérer les entités pays

require_once __DIR__ . '/../config.inc.php'; // Inclure le fichier de configuration

/**
 * Récupérer tous les pays.
 *
 * Cette fonction récupère la liste de tous les pays présents dans la base de données.
 *
 * @return array Liste des pays sous forme de tableaux associatifs (code_pays, nom_pays).
 *               Retourne un tableau vide en cas d'erreur.
 */
function getPays() {
    $conn = getBDD();
    $req = "SELECT code_pays, nom_pays FROM pays;";
    $stmt = mysqli_prepare($conn, $req);

    if ($stmt === false) {
        echo "Erreur dans la préparation de la requête : " . mysqli_error($conn);
        return [];
    }

    if (!mysqli_stmt_execute($stmt)) {
        echo "Erreur dans l'exécution de la requête : " . mysqli_error($conn);
        return [];
    }

    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC); // Récupère tous les résultats
}

/**
 * Récupérer les pays par région.
 *
 * Cette fonction récupère la liste des pays appartenant à une région spécifique.
 *
 * @param string $idRegion Numéro de la région.
 * @return array Liste des pays sous forme de tableaux associatifs (nom_pays).
 *               Retourne un tableau vide en cas d'erreur.
 */
function getPaysParRegion($idRegion) {
    $conn = getBDD();
    $req = "SELECT nom_pays FROM pays AS p 
            JOIN regions AS r ON r.id_region = p.id_region 
            WHERE r.nom_region = ?;";
    $stmt = mysqli_prepare($conn, $req);

    if ($stmt === false) {
        echo "Erreur dans la préparation de la requête : " . mysqli_error($conn);
        return [];
    }

    mysqli_stmt_bind_param($stmt, "s", $idRegion);
    if (!mysqli_stmt_execute($stmt)) {
        echo "Erreur dans l'exécution de la requête : " . mysqli_error($conn);
        return [];
    }

    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC); // Récupère tous les résultats
}

/**
 * Récupérer les détails d'un pays.
 *
 * Cette fonction récupère les informations détaillées d'un pays spécifique, 
 * y compris son nom, sa région et son groupe de revenu.
 *
 * @param string $codePays Code du pays.
 * @return array|null Détails du pays sous forme de tableau associatif (nom_pays, code_pays, nom_region, groupe_revenu).
 *                    Retourne null en cas d'erreur.
 */
function getDetailsPays($codePays) {
    $conn = getBDD();
    $req = "SELECT p.nom_pays, r.nom_region, p.groupe_revenu FROM pays AS p 
            JOIN regions AS r ON r.id_region = p.id_region 
            WHERE p.code_pays = ?;";
    $stmt = mysqli_prepare($conn, $req);

    if ($stmt === false) {
        echo "Erreur dans la préparation de la requête : " . mysqli_error($conn);
        return null;
    }

    mysqli_stmt_bind_param($stmt, "s", $codePays);
    if (!mysqli_stmt_execute($stmt)) {
        echo "Erreur dans l'exécution de la requête : " . mysqli_error($conn);
        return null;
    }

    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res); // Récupère un seul résultat
}
?>