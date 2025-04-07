<?php

require_once __DIR__ . '/../config.inc.php';

/**
 * Récupère la liste des noms d’indicateurs dynamiquement depuis la table `indicateurs`
 * en excluant les colonnes techniques.
 */
function getIndicateurs() {
    $conn = getBDD();
    $indicateurs = [];

    $req = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'indicateurs' 
            AND COLUMN_NAME NOT IN ('id', 'code_pays', 'annee') 
            AND TABLE_SCHEMA = DATABASE();";

    $res = mysqli_query($conn, $req);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $indicateurs[] = $row['COLUMN_NAME'];
        }
    }

    return $indicateurs;
}

/**
 * Récupère les valeurs d’un indicateur pour un pays spécifique sur plusieurs années.
 */
function getValeursIndicateur($idIndicateur, $codePays) {
    $conn = getBDD();
    $valeurs = [];

    $stmt = mysqli_prepare($conn, 
        "SELECT annee, `$idIndicateur` AS valeur 
         FROM indicateurs 
         WHERE code_pays = ? 
         AND `$idIndicateur` IS NOT NULL 
         ORDER BY annee ASC"
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $codePays);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($res)) {
            $valeurs[] = $row;
        }
    }

    return $valeurs;
}

/**
 * Récupère la moyenne d’un indicateur pour une région spécifique.
 */
function getMoyenneIndicateurParRegion($idIndicateur, $idRegion) {
    $conn = getBDD();
    $moyenne = null;

    $stmt = mysqli_prepare($conn, 
        "SELECT AVG(i.`$idIndicateur`) AS moyenne 
         FROM indicateurs i 
         JOIN pays p ON i.code_pays = p.code_pays 
         WHERE p.id_region = ? 
         AND i.`$idIndicateur` IS NOT NULL"
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $idRegion);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        $moyenne = $row['moyenne'];
    }

    return $moyenne;
}

/**
 * Récupère les indicateurs disponibles pour une année spécifique.
 */
function getIndicateursParAnnee($annee) {
    $conn = getBDD();
    $indicateursDisponibles = [];

    $indicateurs = getIndicateurs(); // utilise la fonction précédente
    foreach ($indicateurs as $indicateur) {
        $stmt = mysqli_prepare($conn, 
            "SELECT COUNT(*) AS nb 
             FROM indicateurs 
             WHERE annee = ? 
             AND `$indicateur` IS NOT NULL"
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $annee);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            if ($row['nb'] > 0) {
                $indicateursDisponibles[] = $indicateur;
            }
        }
    }

    return $indicateursDisponibles;
}



echo "<h2>Liste des indicateurs :</h2>";
$indicateurs = getIndicateurs();
echo "<pre>";
print_r($indicateurs);
echo "</pre>";

echo "<h2>Valeurs de l’indicateur pour un pays donné :</h2>";
$valeurs = getValeursIndicateur('population', 'FRA'); // Exemple : indicateur 'population' pour la France
echo "<pre>";
print_r($valeurs);
echo "</pre>";

echo "<h2>Moyenne d’un indicateur pour une région :</h2>";
$moyenne = getMoyenneIndicateurParRegion('population', 1); // Exemple : région avec ID 1
echo "<pre>";
echo "Moyenne : " . $moyenne;
echo "</pre>";

echo "<h2>Indicateurs disponibles pour une année :</h2>";
$disponibles = getIndicateursParAnnee(2020); // Exemple : année 2020
echo "<pre>";
print_r($disponibles);
echo "</pre>";
?>