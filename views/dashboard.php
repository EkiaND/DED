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
                <canvas id="populationChart"></canvas>
            </div>
        </td>
        <td class="graph-cell">
            <div class="graph-container">
                <h3>Autre Indicateur</h3>
                <canvas id="autreChart"></canvas>
            </div>
        </td>
    </tr>

    <tr>
        <td class="graph-cell">
            <div class="graph-container">
            <canvas id="ratioRegionsCurve"></canvas>
            </div>
        </td>
    </tr>
    
</table>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./public/js/graphiques.js"></script>