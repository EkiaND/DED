<?php
/**
 * 
 * Ceci est le point d'entrée principal de l'application DED.
 * 
 * La structure HTML inclut un menu de navigation avec des liens vers différentes sections de l'application :
 * - Tableau de bord
 * - Informations sur les pays
 * - Comparaison des pays
 * 
 * Le code PHP récupère le paramètre d'URL demandé et inclut le fichier correspondant.
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Application DED</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php?url=dashboard">Tableau de bord</a></li>
            <li><a href="index.php?url=infos_pays">Informations sur les pays</a></li>
            <li><a href="index.php?url=comparaison_pays">Comparaison des pays</a></li>
        </ul>
    </nav>

    <?php
    // Point d'entrée de l'application

    // Récupérer l'URL demandée
    $url = isset($_GET['url']) ? $_GET['url'] : '';

    // Charger les fichiers en fonction de l'URL
    switch ($url) {
        case 'dashboard':
            include 'views/dashboard.php';
            break;  
        case 'infos_pays':
            include 'views/infos_pays.php';
            break;
        case 'comparaison_pays':
            include 'views/comparaison_pays.php';
            break;
        default:
            include 'views/dashboard.php';
            break;
    }
    ?>
</body>
</html>