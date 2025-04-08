<?php
// Vue pour les parties A et B
echo "Informations sur les pays!";
?>

<?php
// Vue pour les parties A et B
?>
<table id = "graphTable">
  <tr>
    <td rowspan="5">
        <label for="pays1">Sélectionnez un Pays:</label><br>
        <select id="pays1"></select><br><br>

        <label for="indicateur">Veuillez choisir une année : </label><br>
        <input type="number" value="2000" min="2000" max="2018" style="width: 92%; padding: 8px; margin-bottom: 15px;"><br><br>

        <br><br>
        <button id="bouton_comparer">Visualisation</button>
        <div id="erreur"></div>
    </td>
  
</tr>   

  <tr>
  <td rowspan1="2" style="padding: 80px; background-color: #ffffff; border-radius:5px;">
          graphiques</td>

    <td rowspan="1" style="padding: 80px; background-color: #ffffff; border-radius:5px ;">
      
    graphiques
    </td>

</tr>

<tr>
  <td colspan="2" style="padding: 50px; background-color: #ffffff; border-radius:5px ;">
      <div>

        <table style="width: 100%; margin-top: 30px; border-collapse: collapse; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
          <thead>
            <tr style="background-color: #4e8ef7; color: white;">
              <th style="padding: 12px;">Test</th>
              <th style="padding: 12px;">Test</th>
              <th style="padding: 12px;">test</th>
              <th style="padding: 12px;">État</th>
            </tr>
          </thead>
          <tbody>
            <tr style="text-align: center;">
              <td style="padding: 12px;">BourseTrack</td>
              <td style="padding: 12px;">Aminata</td>
              <td style="padding: 12px;">19/12/2024</td>
              <td style="padding: 12px;">
                <span style="background-color: #2ecc71; color: white; padding: 5px 10px; border-radius: 20px; font-weight: bold;">Terminé</span>
              </td>
            </tr>
            <tr style="text-align: center;">
              <td style="padding: 12px;">Analyse Élections EU</td>
              <td style="padding: 12px;">Léo</td>
              <td style="padding: 12px;">06/01/2025</td>
              <td style="padding: 12px;">
                <span style="background-color: #f39c12; color: white; padding: 5px 10px; border-radius: 20px; font-weight: bold;">En cours</span>
              </td>
            </tr>
            <tr style="text-align: center;">
              <td style="padding: 12px;">Visualisation Data Géo</td>
              <td style="padding: 12px;">Sophie</td>
              <td style="padding: 12px;">30/04/2025</td>
              <td style="padding: 12px;">
                <span style="background-color: #e74c3c; color: white; padding: 5px 10px; border-radius: 20px; font-weight: bold;">En retard</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </td>
  </tr>

</table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./public/js/graphiques.js"></script>
<script src="./public/js/interactions.js"></script>

