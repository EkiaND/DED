<?php
// Fichier pour gérer les entités pays


//Récupérer tout les pays
function getPays(){
	
	$conn = getBDD();
	$pays = [];
	$req = "SELECT code_pays, nom_pays FROM jouets;";

	$stmt = mysqli_prepare($conn,$req);

	if( $stmt === false){
		echo mysqli_error($conn);
	}
	else {
		if (mysqli_stmt_execute($stmt)){
            $res = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_assoc($res,MYSLI_ASSOC);
		}
	}	
}


// Récupérer pays par région
function getPaysParRegion($region){
	$conn = getBDD();
	$pays = null;
	$req = "SELECT nom_pays FROM pays as p JOIN region as r ON r.id_region = p.id_region WHERE region = ?;";
	$stmt = mysqli_prepare($conn,$req);
	if ($stmt === false) {
		echo mysqli_error($conn);
	}
	else{
		mysqli_stmt_bind_params($stmt,"s",$region);
        if (mysqli_stmt_execute($stmt)){
            $res = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_assoc($res,MYSLI_ASSOC);
        }
	}
}
?>
