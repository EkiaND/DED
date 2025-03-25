
<?php
function getIndicateurDispos($pays, $annee) {
    $conn = getBDD();

    $req = """
        SELECT COLUMN_NAME AS indicateurs
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'indicateurs' AND COLUMN_NAME != 'code_pays' AND COLUMN_NAME != 'annee'
        AND EXISTS (
            SELECT 1 FROM indicateurs AS i
            JOIN pays AS p ON p.code_pays = i.code_pays
            WHERE p.nom_pays = ? AND i.annee = ? AND i.`""" . COLUMN_NAME . """` IS NOT NULL
        );
    """;

    $stmt = mysqli_prepare($conn, $req);
    if ($stmt === false) {
        echo mysqli_error($conn);
        return null;
    }

    mysqli_stmt_bind_param($stmt, "si", $pays, $annee);

    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $indicateurs = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $indicateurs[] = $row['indicateurs'];
        }
        return $indicateurs;
    } else {
        echo mysqli_stmt_error($stmt);
        return null;
    }
}
?>



