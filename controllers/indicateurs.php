<?php
// Fichier pour le contrôleur principal des indicateurs
require_once __DIR__ . '/../models/indicateur.php';
require_once __DIR__ . '/../models/pays.php';

// Récupérer le PIB Mondial par année
function getMoyennePIBMondial() {
    $pays = getPays();  
    $pibParAnnee = [];  

    
    if ($pays === null || !is_array($pays)) {
        echo "Aucun pays trouvé ou erreur de récupération des pays.";
        return []; 
    }

   
    foreach ($pays as $paysInfo) {
        $codePays = $paysInfo['code_pays'];  

        
        $valeurs = getValeursIndicateur('pib', $codePays);


        
        if ($valeurs === null || empty($valeurs)) {
            continue;  
        }

     
        foreach ($valeurs as $data) {
            $annee = $data['annee'];  
            $pib = $data['valeur'];  

            
            if (is_null($pib) || $pib <= 0) {
                continue; 
            }

           
            if (!isset($pibParAnnee[$annee])) {
                $pibParAnnee[$annee] = ['total' => 0, 'nb_pays' => 0];
            }

            
            $pibParAnnee[$annee]['total'] += $pib;
            $pibParAnnee[$annee]['nb_pays']++;
        }
    }

    
    if (empty($pibParAnnee)) {
        echo "Aucune donnée PIB valide trouvée.";
        return [];
    }

   
    $moyennePIB = [];
    foreach ($pibParAnnee as $annee => $data) {
        
        if ($data['nb_pays'] > 0) {
            $moyennePIB[$annee] = $data['total'] / $data['nb_pays'];
        }
    }

    
    return $moyennePIB; 
}

echo json_encode(getMoyennePIBMondial());
