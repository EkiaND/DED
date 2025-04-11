<?php // Vue pour les parties A et B ?>
        <table id="graphTable" >
          <tr>
            <td rowspan="5" style="padding: 20px;">
                <label for="pays1">Sélectionnez un Pays :</label><br>
                <select id="pays1" required>
                    <option>Veuillez sélectionner un pays</option>
                </select>
                
                <label for="annee">Veuillez choisir une année : </label><br>
                <input id="annee" type="number" value="2000" min="1960" max="2018" style="width: 92%; padding: 8px; margin-bottom: 15px;"><br><br>

                <label for="test">La liste des indicateurs :</label><br>
                <select id="test">
                    <option value="pib" selected>PIB</option>
                    <option value="esperance_vie">Espérance de vie</option>
                    <option value="densite_population">Population</option>
                    <option value="taux_natalite">Taux de natalité</option>
                    <option value="taux_mortalite">Taux de mortalité</option>
                    <option value="consommation_electricite">Consommation d'Électricité</option>
                    <option value="pib_par_habitant">PIB par habitant</option>
                    <option value="mortalite_infantile">Mortalité infantile</option>
                    <option value="taux_chomage">Taux de chômage</option> 
                </select>

                <br><br>
                <div id="erreur" style="color:red;"></div>
            </td>
          </tr> 

          <tr>
            <td rowspan="1" style="padding: 50px; background-color: #ffffff; border-radius:5px;">
              <div id = "infoPays">
                <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 20px;">
                  Le pays sélectionné est <span style="color: #4e8ef7;">BourseTrack</span>, sa région est : <span style="color: #4e8ef7;">NomDeLaRégion</span> .<br><br>
                  Ci-dessous se trouvent l’ensemble de ses informations ainsi que ses indicateurs.
                </p>
                
                <!-- Premier tableau avec les intitulés des indicateurs -->

                <!-- Deuxième tableau avec les données réelles -->
                <table style="width: 100%; height: 100%; margin-top: 30px; border-collapse: collapse; border: 1px solid #3366CC; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                  <tbody>
                    <tr> 
                      <td>     </td>  <!-- PIB -->
                      <td></td>  <!-- PIB/Habitants -->
                      <td><span></span></td>  <!-- Taux de mortalité / natalité -->
                      <td><span></span></td>  <!-- Utilisation d'internet -->
                      <td><span></span></td>  <!-- Taux de chômage -->
                      <td><span></span></td>  <!-- Espérance de vie -->
                      <td></td>  <!-- Mortalité infantile -->
                      <td></td>  <!-- Densité de population -->
                      <td></td>  <!-- Consommation d’électricité -->
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>

          <tr>
            <td colspan="1" style="background-color: #ffffff; border-radius:5px;">
              <canvas id="graphPIB"></canvas>
            </td>
          </tr>
        </table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/public/js/interaction_info.js"></script>