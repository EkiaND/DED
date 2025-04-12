<?php
require_once __DIR__ . '/../config.inc.php';

/**
 * Récupérer les indices de développement pour un pays et une année donnés.
 *
 * @param string $codePays Code du pays.
 * @param int $annee Année.
 * @return array|null Indices de développement sous forme de tableau associatif.
 */
function getIndicesParPaysEtAnnee($codePays, $annee) {
    try {
        $conn = getBDD();
        $query = "SELECT genre, idh, esperance_vie, annees_scolarisation_attendues, annees_scolarisation_moyenne, 
                         revenu_national_brut, indice_developpement_genre, idh_inegalite, coefficient_inegalite, 
                         perte_humaine, inegalite_esperance_vie, inegalite_education, inegalite_revenu, 
                         indice_inegalite_genre, taux_mortalite_maternelle, taux_naissance_adolescents, 
                         education_secondaire, representation_parlementaire, taux_participation, empreinte_materielle, 
                         emissions_co2
                  FROM indices_dvpt
                  WHERE code_pays = ? AND annee = ?;";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "si", $codePays, $annee);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($res) ?: null;
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return null;
    }
}

/**
 * Récupérer les indices de développement pour une région donnée et une année.
 *
 * @param string $idRegion ID de la région.
 * @param int $annee Année.
 * @return array Liste des indices de développement pour les pays de la région.
 */
function getIndicesParRegionEtAnnee($idRegion, $annee) {
    try {
        $conn = getBDD();
        $query = "SELECT p.nom_pays, i.genre, i.idh, i.esperance_vie, i.annees_scolarisation_attendues, 
                         i.annees_scolarisation_moyenne, i.revenu_national_brut, i.indice_developpement_genre, 
                         i.idh_inegalite, i.coefficient_inegalite, i.perte_humaine, i.inegalite_esperance_vie, 
                         i.inegalite_education, i.inegalite_revenu, i.indice_inegalite_genre, 
                         i.taux_mortalite_maternelle, i.taux_naissance_adolescents, i.education_secondaire, 
                         i.representation_parlementaire, i.taux_participation, i.empreinte_materielle, i.emissions_co2
                  FROM indices_dvpt AS i
                  JOIN pays AS p ON i.code_pays = p.code_pays
                  JOIN regions AS r ON p.id_region = r.id_region
                  WHERE r.id_region = ? AND i.annee = ?;";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "si", $idRegion, $annee);
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
 * Récupérer les indices de développement pour une région donnée et une année.
 *
 * @param string $codePays code du Pays.
 * @return array IDH le plus récent disponible pour le pays.
 */
function getDernierIdhParPays($codePays) {
    $conn = getBDD();
    
    $stmt = mysqli_prepare($conn, "
        SELECT annee, idh 
        FROM indices_dvpt 
        WHERE code_pays = ? AND idh IS NOT NULL  AND genre = 'total'
        ORDER BY annee DESC 
        LIMIT 1
    ");
    
    mysqli_stmt_bind_param($stmt, "s", $codePays);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($res) ?: null;
}
?>
