// Fonctions de génération des graphiques

document.addEventListener("DOMContentLoaded", function () {
    fetch('/DED/controllers/indicateurs.php') // ← Ajuste selon ton chemin réel
        .then(response => response.json())
        .then(data => {
            const labels = Object.keys(data).sort();
            const valeurs = labels.map(annee => data[annee]);

            const donnees = {
                labels: labels,
                datasets: [{
                    label: "Moyenne du PIB Mondial (en $ US)",
                    data: valeurs,
                    borderColor: "blue",
                    backgroundColor: "rgba(0, 0, 255, 0.1)",
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.3, // ← Adoucit les courbes
                    fill: true
                }]
            };

            const whiteBackgroundPlugin = {
                id: 'whiteBackground',
                beforeDraw: (chart) => {
                    const ctx = chart.canvas.getContext('2d');
                    ctx.save();
                    ctx.globalCompositeOperation = 'destination-over';
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, chart.width, chart.height);
                    ctx.restore();
                }
            };

            const config = {
                type: 'line',
                data: donnees,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'black',
                                font: { size: 14 }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Évolution du PIB Mondial par Année',
                            color: 'black',
                            font: { size: 18 }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: 'black' },
                            title: {
                                display: true,
                                text: "Années",
                                color: 'black',
                                font: { size: 14 }
                            }
                        },
                        y: {
                            ticks: { color: 'black' },
                            title: {
                                display: true,
                                text: "PIB moyen (USD)",
                                color: 'black',
                                font: { size: 14 }
                            },
                            beginAtZero: false
                        }
                    }
                },
                plugins: [whiteBackgroundPlugin]
            };

            const ctx = document.getElementById("pibChart").getContext("2d");
            new Chart(ctx, config);
        })
        .catch(error => console.error("Erreur lors du chargement des données:", error));
});
