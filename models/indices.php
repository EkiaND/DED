<?php
require_once __DIR__ . '/../config.inc.php';

/**
 * Donne les indices d’un pays pour une année.
 */
function getIndicesParPaysEtAnnee($codePays, $annee) {
    $conn = getBDD();
    $sql = "SELECT * FROM indices_dvpt WHERE code_pays = ? AND annee = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $codePays, $annee);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

/**
 * Donne les indices des pays d’une région pour une année.
 */
function getIndicesParRegionEtAnnee($idRegion, $annee) {
    $conn = getBDD();
    $sql = "
        SELECT * 
        FROM indices_dvpt i
        JOIN pays p ON i.code_pays = p.code_pays
        WHERE p.id_region = ? AND i.annee = ?
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $idRegion, $annee);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}
