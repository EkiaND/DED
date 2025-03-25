<?php
// Fichier pour gérer les indicateurs économiques

// Vérifier si les indicateurs pour un pays et une année sont disponibles
function getIndicateurDispos($pays,$annee){
	$conn = getBDD();
	$indicateurs = null;
	$req = """  SELECT 'taux_natalite' AS indicateurs FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND taux_natalite IS NOT NULL
                UNION
                SELECT 'taux_mortalite' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND taux_mortalite IS NOT NULL
                UNION
                SELECT 'consommation_electricite' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND consommation_electricite IS NOT NULL
                UNION
                SELECT 'pib' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND pib IS NOT NULL
                UNION
                SELECT 'pib_par_habitant' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND pib_par_habitant IS NOT NULL
                UNION
                SELECT 'utilisation_internet' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND utilisation_internet IS NOT NULL
                UNION
                SELECT 'mortalite_infantile' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND mortalite_infantile IS NOT NULL
                UNION
                SELECT 'esperance_vie' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND esperance_vie IS NOT NULL
                UNION
                SELECT 'densite_population' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND densite_population IS NOT NULL
                UNION
                SELECT 'taux_chomage' FROM indicateurs as i JOIN pays as p ON p.code_pays = i.code_pays WHERE nom_pays = ? AND annee = ? AND taux_chomage IS NOT NULL;
                """;

	$stmt = mysqli_prepare($conn,$req);
	if ($stmt === false) {
		echo mysqli_error($conn);
	}
	else{
		mysqli_stmt_bind_params($stmt,"sisisisisisisisisisi",[$pays,$annee,$pays,$annee,$pays,$annee,$pays,$annee,$pays,$annee,$pays,$annee,$pays,$annee,$pays,$annee,$pays,$annee,$pays,$annee]);
        if (mysqli_stmt_execute($stmt)){
            $res = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_assoc($res,MYSQLI_ASSOC);
        }
	}
}


// Récupérer les indicateurs d'un pays

?>
