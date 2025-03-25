<?php
// Fichier pour gérer les entités pays

require_once __DIR__ . '/../logger/logger.php'; // Inclure le logger
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
    try {
        $conn = getBDD();
        $req = "SELECT code_pays, nom_pays FROM pays;";
        $stmt = mysqli_prepare($conn, $req);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($res, MYSQLI_ASSOC); // Récupère tous les résultats
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [];
    }
}

/**
 * Récupérer les pays par région.
 *
 * Cette fonction récupère la liste des pays appartenant à une région spécifique.
 *
 * @param string $idRegion Nom de la région.
 * @return array Liste des pays sous forme de tableaux associatifs (nom_pays).
 *               Retourne un tableau vide en cas d'erreur.
 */
function getPaysParRegion($idRegion) {
    try {
        $conn = getBDD();
        $req = "SELECT nom_pays FROM pays AS p 
                JOIN regions AS r ON r.id_region = p.id_region 
                WHERE r.id_region = ?;";
        $stmt = mysqli_prepare($conn, $req);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $idRegion);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($res, MYSQLI_ASSOC); // Récupère tous les résultats
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [];
    }
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
    try {
        $conn = getBDD();
        $req = "SELECT p.nom_pays, r.nom_region, p.groupe_revenu FROM pays AS p 
                JOIN regions AS r ON r.id_region = p.id_region 
                WHERE p.code_pays = ?;";
        $stmt = mysqli_prepare($conn, $req);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $codePays);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($res); // Récupère un seul résultat
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return null;
    }
}

/**
 * Récupérer la liste des régions.
*/

function getListePays() {
	try{
		$conn = getBDD();
        $req = "SELECT nom_region FROM pays;";
		$stmt = mysqli_prepare($conn,$req);

		if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($res, MYSQLI_ASSOC); // Récupère tous les résultats
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [];
    }
}

/**
 * Récupérer la liste des pays par groupe de revenu.
*/

function getPaysParGroupeRevenu($groupeRevenu) {
    try {
        $conn = getBDD();
        $req = "SELECT nom_pays FROM pays 
				WHERE groupe_revenu = ?;";
        $stmt = mysqli_prepare($conn, $req);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $groupeRevenu);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($res, MYSQLI_ASSOC); // Récupère tous les résultats
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [];
    }
}

/**
 * Récupérer le nombre de pays par région.
*/

function getNombrePaysParRegion($idRegion) {
    try {
        $conn = getBDD();
        $req = "SELECT count(nom_pays) as "Nombre de pays" FROM pays AS p 
                JOIN regions AS r ON r.id_region = p.id_region 
                WHERE r.id_region = ?;";
        $stmt = mysqli_prepare($conn, $req);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $idRegion);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($res, MYSQLI_ASSOC); // Récupère tous les résultats
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [];
    }
}

?>