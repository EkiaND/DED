<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure les modèles nécessaires
require_once __DIR__ . '/../models/indicateur.php';
require_once __DIR__ . '/../models/pays.php';
require_once __DIR__ . '/../models/data.php'; // Inclure le fichier contenant la fonction getDistributionIndicateurParRegion

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Vérifier l'action demandée
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'getMoyennePIBMondial':
            echo json_encode(getMoyennePIBMondial());
            break;

        case 'getEsperanceVieMondiale':
            echo json_encode(getEsperanceVieMondiale());
            break;
        
        case 'getRatioParRegionParAnnee':
            echo json_encode(getRatioParRegionParAnnee());
            break;
        
        case 'getPopulationMondiale':
            echo json_encode(getPopulationMondiale());
            break;
            
        case 'getAutreIndicateur':
            echo json_encode(getAutreIndicateur());
            break;
        
        case 'comparerPays':
            $pays1 = $_GET['pays1'] ?? null;
            $pays2 = $_GET['pays2'] ?? null;
            $indicateur = $_GET['indicateur'] ?? null;
        
            if (!$pays1 || !$pays2 || !$indicateur || $pays1 === $pays2) {
                echo json_encode(['error' => 'Paramètres invalides.']);
                exit;
            }
            
            $valeurs1 = getValeursIndicateur($indicateur, $pays1);
            $valeurs2 = getValeursIndicateur($indicateur, $pays2);
            
            $annees = array_map(fn($v) => $v['annee'], $valeurs1);
            $valeursPays1 = array_map(fn($v) => $v['valeur'], $valeurs1);
            $valeursPays2 = array_map(fn($v) => $v['valeur'], $valeurs2);
            
            $details1 = getDetailsPays($pays1);
            $details2 = getDetailsPays($pays2);
            
            echo json_encode([
                'indicateur' => $indicateur,
                'nomPays1' => $details1['nom_pays'] ?? $pays1,
                'nomPays2' => $details2['nom_pays'] ?? $pays2,
                'annees' => $annees,
                'valeurs1' => $valeursPays1,
                'valeurs2' => $valeursPays2
            ]);
            exit;

        case 'getDistributionIndicateurParRegion':
            if (isset($_GET['idIndicateur'], $_GET['annee'])) {
                $idIndicateur = $_GET['idIndicateur'];
                $annee = (int)$_GET['annee'];
                echo getDistributionIndicateurParRegion($idIndicateur, $annee);
            } else {
                echo json_encode(['error' => 'Paramètres manquants']);
            }
            break;

        case 'getDistributionIndicateurParPays':
            if (isset($_GET['idIndicateur'], $_GET['annee'], $_GET['region'])) {
                $idIndicateur = $_GET['idIndicateur'];
                $annee = (int)$_GET['annee'];
                $region = urldecode($_GET['region']); // Décoder le nom de la région
                error_log("Nom de la région après décodage : $region");
                error_log("Paramètres reçus : idIndicateur=$idIndicateur, annee=$annee, region=$region");
                echo getDistributionIndicateurParPays($idIndicateur, $annee, $region);
            } else {
                error_log("Paramètres manquants pour getDistributionIndicateurParPays");
                echo json_encode(['error' => 'Paramètres manquants']);
            }
            break;

        case 'getCountryData':
            if (isset($_GET['codePays'])) {
                $codePays = $_GET['codePays'];
                echo json_encode(getCountryData($codePays));
            } else {
                echo json_encode(['error' => 'Paramètre codePays manquant']);
            }
            break;

        default:
            echo json_encode(['error' => 'Action non reconnue']);
            break;
    }
    exit;
}

// Si aucune action n'est spécifiée, retourner une erreur
echo json_encode(['error' => 'Aucune action spécifiée']);
exit;

// Fonction pour récupérer la moyenne du PIB mondial par année
function getMoyennePIBMondial() {
    $pays = getPays();
    $pibParAnnee = [];

    // Vérifier si les pays ont été récupérés correctement
    if ($pays === null || !is_array($pays)) {
        return ['error' => 'Aucun pays trouvé ou erreur de récupération des pays.'];
    }

    // Parcourir chaque pays pour récupérer les valeurs du PIB
    foreach ($pays as $paysInfo) {
        $codePays = $paysInfo['code_pays'];
        $valeurs = getValeursIndicateur('pib', $codePays);

        // Ignorer les pays sans données valides
        if ($valeurs === null || empty($valeurs)) {
            continue;
        }

        // Regrouper les données par année
        foreach ($valeurs as $data) {
            $annee = $data['annee'];
            $pib = $data['valeur'];

            // Ignorer les valeurs invalides
            if (is_null($pib) || $pib <= 0) {
                continue;
            }

            // Initialiser l'année si elle n'existe pas encore
            if (!isset($pibParAnnee[$annee])) {
                $pibParAnnee[$annee] = ['total' => 0, 'nb_pays' => 0];
            }

            // Ajouter les données pour l'année
            $pibParAnnee[$annee]['total'] += $pib;
            $pibParAnnee[$annee]['nb_pays']++;
        }
    }

    // Calculer la moyenne du PIB par année
    $moyennePIB = [];
    foreach ($pibParAnnee as $annee => $data) {
        if ($data['nb_pays'] > 0) {
            $moyennePIB[$annee] = $data['total'] / $data['nb_pays'];
        }
    }

    return $moyennePIB;
}

// Fonction pour récupérer l'espérance de vie mondiale par année
function getEsperanceVieMondiale() {
    $pays = getPays();
    $esperanceParAnnee = [];

    // Vérifier si les pays ont été récupérés correctement
    if ($pays === null || !is_array($pays)) {
        return ['error' => 'Aucun pays trouvé ou erreur de récupération des pays.'];
    }

    // Parcourir chaque pays pour récupérer les valeurs de l'espérance de vie
    foreach ($pays as $paysInfo) {
        $codePays = $paysInfo['code_pays'];
        $valeurs = getValeursIndicateur('esperance_vie', $codePays);

        // Ignorer les pays sans données valides
        if ($valeurs === null || empty($valeurs)) {
            continue;
        }

        // Regrouper les données par année
        foreach ($valeurs as $data) {
            $annee = $data['annee'];
            $esperance = $data['valeur'];

            // Ignorer les valeurs invalides
            if (is_null($esperance) || $esperance <= 0) {
                continue;
            }

            // Initialiser l'année si elle n'existe pas encore
            if (!isset($esperanceParAnnee[$annee])) {
                $esperanceParAnnee[$annee] = ['total' => 0, 'nb_pays' => 0];
            }

            // Ajouter les données pour l'année
            $esperanceParAnnee[$annee]['total'] += $esperance;
            $esperanceParAnnee[$annee]['nb_pays']++;
        }
    }

    // Calculer la moyenne de l'espérance de vie par année
    $moyenneEsperance = [];
    foreach ($esperanceParAnnee as $annee => $data) {
        if ($data['nb_pays'] > 0) {
            $moyenneEsperance[$annee] = $data['total'] / $data['nb_pays'];
        }
    }

    return $moyenneEsperance;
}
function getRatioParRegionParAnnee() {
    $conn = getBDD();
    $pays = getPays();
    $dataParRegionAnnee = [];

    if ($pays === null || !is_array($pays)) {
        return ['error' => 'Aucun pays trouvé ou erreur de récupération des pays.'];
    }

    foreach ($pays as $paysInfo) {
        $codePays = $paysInfo['code_pays'];

        
        $details = getDetailsPays($codePays);
        if ($details === null || !isset($details['nom_region'])) {
            continue;
        }

        $region = $details['nom_region'];

        $valeursNatalite = getValeursIndicateur('taux_natalite', $codePays);
        $valeursMortalite = getValeursIndicateur('taux_mortalite', $codePays);

        if (empty($valeursNatalite) || empty($valeursMortalite)) {
            continue;
        }

        foreach ($valeursNatalite as $index => $nataliteData) {
            $annee = $nataliteData['annee'];
            $tauxNatalite = $nataliteData['valeur'];
            $tauxMortalite = $valeursMortalite[$index]['valeur'] ?? null;

            if (is_null($tauxNatalite) || is_null($tauxMortalite) || $tauxMortalite <= 0) {
                continue;
            }

            if (!isset($dataParRegionAnnee[$region][$annee])) {
                $dataParRegionAnnee[$region][$annee] = ['total' => 0, 'nb' => 0];
            }

            $dataParRegionAnnee[$region][$annee]['total'] += $tauxNatalite / $tauxMortalite;
            $dataParRegionAnnee[$region][$annee]['nb']++;
        }
    }

    // Moyenne par région et année
    $resultat = [];
    foreach ($dataParRegionAnnee as $region => $annees) {
        foreach ($annees as $annee => $data) {
            if ($data['nb'] > 0) {
                $resultat[$region][$annee] = round($data['total'] / $data['nb'], 2);
            }
        }
    }

    return $resultat;
}

