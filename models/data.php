<?php
// Fichier pour les requêtes complexes et analyses

//require_once __DIR__ . '/../logger/logger.php'; // Inclure le logger
require_once __DIR__ . '/../config.inc.php'; // Inclure le fichier de configuration

/**
 * Récupérer le top 10 des pays selon un indicateur.
 *
 * @param string $idIndicateur Nom de l'indicateur (ex. 'pib', 'esperance_vie').
 * @param string $ordre Ordre de tri ('ASC' ou 'DESC'). Par défaut 'DESC'.
 * @return array Liste des 10 pays avec leurs valeurs pour l'indicateur.
 */
function getTop10PaysParIndicateur($idIndicateur, $ordre = 'DESC', $annee = null) {
    try {
        $conn = getBDD();
        $query = "SELECT p.nom_pays, MAX(i.$idIndicateur) AS valeur, i.annee 
                  FROM indicateurs AS i
                  JOIN pays AS p ON i.code_pays = p.code_pays
                  WHERE i.$idIndicateur IS NOT NULL";
        
        // Ajouter une condition pour l'année si elle est spécifiée
        if ($annee !== null) {
            $query .= " AND i.annee = ?";
        }

        $query .= " GROUP BY p.nom_pays, i.annee
                    ORDER BY valeur $ordre
                    LIMIT 10;";
        
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        // Lier les paramètres si l'année est spécifiée
        if ($annee !== null) {
            mysqli_stmt_bind_param($stmt, "i", $annee);
        }

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
 * Analyser les corrélations entre deux indicateurs.
 *
 * @param string $idIndicateur1 Nom du premier indicateur (ex. 'pib').
 * @param string $idIndicateur2 Nom du second indicateur (ex. 'esperance_vie').
 * @param string|null $filtre Code du pays ou ID de la région pour filtrer les données (optionnel).
 * @param string $typeFiltre Type de filtre à appliquer : 'pays' pour un pays ou 'region' pour une région. Par défaut 'pays'.
 * @return array Résultat contenant le coefficient de corrélation, le filtre appliqué, et le type de filtre. Retourne null pour la corrélation en cas d'erreur.
 */
function getCorrelationEntreIndicateurs($idIndicateur1, $idIndicateur2, $filtre = null, $typeFiltre = 'pays') {
    try {
        $conn = getBDD();
        $query = "SELECT 
                    (SUM(i1.$idIndicateur1 * i2.$idIndicateur2) - 
                     SUM(i1.$idIndicateur1) * SUM(i2.$idIndicateur2) / COUNT(*)) /
                    (SQRT(SUM(POW(i1.$idIndicateur1, 2)) - POW(SUM(i1.$idIndicateur1), 2) / COUNT(*)) *
                     SQRT(SUM(POW(i2.$idIndicateur2, 2)) - POW(SUM(i2.$idIndicateur2), 2) / COUNT(*))) AS correlation";

        // Ajouter les jointures nécessaires
        $query .= " FROM indicateurs AS i1
                    JOIN indicateurs AS i2 ON i1.code_pays = i2.code_pays
                    JOIN pays AS p ON i1.code_pays = p.code_pays";

        // Ajouter une condition pour le filtre (pays ou région)
        if ($filtre !== null) {
            if ($typeFiltre === 'pays') {
                $query .= " WHERE p.code_pays = ?";
            } elseif ($typeFiltre === 'region') {
                $query .= " WHERE p.id_region = ?";
            }
        }

        $query .= " AND i1.$idIndicateur1 IS NOT NULL AND i2.$idIndicateur2 IS NOT NULL;";

        $stmt = mysqli_prepare($conn, $query);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        // Lier les paramètres si un filtre est spécifié
        if ($filtre !== null) {
            mysqli_stmt_bind_param($stmt, "s", $filtre);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);

        // Ajouter le filtre (pays ou région) dans la sortie
        return [
            'correlation' => $row['correlation'] ?? null,
            'filtre' => $filtre,
            'typeFiltre' => $typeFiltre
        ];
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [
            'correlation' => null,
            'filtre' => $filtre,
            'typeFiltre' => $typeFiltre
        ];
    }
}


/**
 * Obtenir l'évolution d'un indicateur dans le temps pour une région donnée.
 *
 * @param string $idIndicateur Nom de l'indicateur.
 * @param string $idRegion ID de la région.
 * @return array Liste des années avec les statistiques suivantes pour l'indicateur dans la région :
 *               - valeur_moyenne : Moyenne des valeurs pour l'année.
 *               - mediane : Médiane des valeurs pour l'année.
 *               - ecart_type : Écart-type des valeurs pour l'année.
 *               - q1 : Premier quartile des valeurs pour l'année.
 *               - q3 : Troisième quartile des valeurs pour l'année.
 */
function getEvolutionIndicateurParRegion($idIndicateur, $idRegion) {
    try {
        $conn = getBDD();
        $query = "SELECT i.annee, i.$idIndicateur AS valeur
                  FROM indicateurs AS i
                  JOIN pays AS p ON i.code_pays = p.code_pays
                  WHERE p.id_region = ? AND i.$idIndicateur IS NOT NULL
                  ORDER BY i.annee;";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $idRegion);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        $res = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_all($res, MYSQLI_ASSOC);

        // Regrouper les données par année
        $result = [];
        $groupedData = [];
        foreach ($data as $row) {
            $groupedData[$row['annee']][] = $row['valeur'];
        }

        // Calculer les statistiques pour chaque année
        foreach ($groupedData as $annee => $valeurs) {
            sort($valeurs); // Trier les valeurs pour les calculs de médiane et quartiles
            $count = count($valeurs);
            $mean = array_sum($valeurs) / $count;
            $median = $valeurs[floor(($count - 1) / 2)];
            if ($count % 2 === 0) {
                $median = ($median + $valeurs[$count / 2]) / 2;
            }
            $q1 = $valeurs[floor(($count - 1) / 4)];
            $q3 = $valeurs[floor(3 * ($count - 1) / 4)];
            $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $valeurs)) / $count;
            $stdDev = sqrt($variance);

            $result[] = [
                'annee' => $annee,
                'valeur_moyenne' => $mean,
                'mediane' => $median,
                'ecart_type' => $stdDev,
                'q1' => $q1,
                'q3' => $q3,
            ];
        }

        return $result;
    } catch (Exception $e) {
        logError($e->getMessage(), __FILE__, __LINE__);
        return [];
    }
}

/**
 * Récupérer la distribution d'un indicateur par région pour une année donnée.
 *
 * @param string $idIndicateur Nom de l'indicateur.
 * @param int $annee Année pour laquelle récupérer la distribution.
 * @return array Liste des régions et des valeurs moyennes de l'indicateur.
 */
function getDistributionIndicateurParRegion($idIndicateur, $annee) {
    try {
        $conn = getBDD();
        $query = "SELECT r.nom_region, AVG(i.$idIndicateur) AS valeur_moyenne
                  FROM indicateurs AS i
                  JOIN pays AS p ON i.code_pays = p.code_pays
                  JOIN regions AS r ON p.id_region = r.id_region
                  WHERE i.annee = ? AND i.$idIndicateur IS NOT NULL
                  GROUP BY r.nom_region
                  ORDER BY valeur_moyenne DESC;";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $annee);
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
?>