<?php
// Vue pour les parties C et D
?>
<table id = "graphTable">
  <tr>
    <td rowspan="5">
        <label for="pays1">Sélectionnez le Pays 1 :</label><br>
        <select id="pays1"></select><br><br>

        <label for="pays2">Sélectionnez le Pays 2 :</label><br>
        <select id="pays2"></select><br><br>

        <label for="indicateur">Sélectionnez un Indicateur :</label><br>
        <select id="indicateur"></select><br><br>

        <br><br>
        <button id="bouton_comparer">Comparer</button>
        <div id="erreur"></div>
    </td>
    <td>Ligne 1 - Colonne 2

    </td>
    <td>Ligne 1 - Colonne 3

    </td>
  </tr>
  <tr>
    <td rowspan="4" colspan="2" class="graph-cell">
        <div class="graph-container">
            <canvas id="comparaisonChart" ></canvas>
        </div>
    </td>
  </tr>

</table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./public/js/graphiques.js"></script>
<script src="./public/js/interactions.js"></script>

