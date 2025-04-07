<!-- filepath: c:\xampp\htdocs\DED\views\dashboard.php -->
<table id="graphTable">
    <tr>
        <td class="graph-cell">
            <div class="graph-container">
                <canvas id="pibChart"></canvas>
            </div>
        </td>
        <td class="graph-cell">
            <div class="graph-container">
                <canvas id="esperanceVieChart"></canvas>
            </div>
        </td>
    </tr>
    <tr>
        <td class="graph-cell">
            <div class="graph-container">
                <h3>Autre Indicateur</h3>
                <canvas id="autreChart"></canvas>
            </div>
        </td>
        <td class="graph-cell">
            <div class="graph-container">
                <canvas id="ratioRegionsCurve"></canvas>
            </div>
        </td>
    </tr>
    <tr>
        <td class="graph-cell">
            <div class="graph-container">
                <div id="map-container">
                    <div id="controls">
                        <label for="indicatorSelect">Indicateur :</label>
                        <select id="indicatorSelect">
                            <option value="pib">PIB</option>
                            <option value="esperance_vie">Espérance de vie</option>
                            <option value="population">Population</option>
                        </select>
                        <label for="yearSelect">Année :</label>
                        <input type="number" id="yearSelect" value="2018" min="2000" max="2022">
                    </div>
                    <div id="map"></div>
                </div>
            </div>
        </td>
        <td class="graph-cell">
            <!-- Cellule vide pour maintenir la symétrie ou pour un futur graphique -->
            <div class="graph-container">
                <!-- Vous pouvez ajouter un contenu ici si nécessaire -->
            </div>
        </td>
    </tr>
</table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./public/js/graphiques.js"></script>
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="./public/js/map.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const indicatorSelect = document.getElementById("indicatorSelect");
        const yearSelect = document.getElementById("yearSelect");

        // Gestion du double-clic pour afficher les pays d'une région
        document.addEventListener("regionDblClick", function (event) {
            const region = event.detail.region;
            const indicator = indicatorSelect.value;
            const year = yearSelect.value;

            // Appeler une fonction PHP pour récupérer les données des pays
            fetch(`./controllers/indicateurs.php?action=getDistributionIndicateurParPays&idIndicateur=${indicator}&annee=${year}&region=${region}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof updateCountries === "function") {
                        updateCountries(indicator, year, region, data);
                    } else {
                        console.error("La fonction updateCountries n'est pas définie.");
                    }
                })
                .catch(error => console.error("Erreur lors du chargement des données des pays :", error));
        });
    });
</script>