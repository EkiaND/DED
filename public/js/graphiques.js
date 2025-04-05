document.addEventListener("DOMContentLoaded", function () {
    // Stocker les instances des graphiques pour éviter les doublons
    let chartInstances = {};

    // Fonction générique pour créer un graphique
    function creerGraphique(idCanvas, titre, labels, data, labelDataset, couleur) {
        const ctx = document.getElementById(idCanvas).getContext("2d");

        // Détruire le graphique existant s'il existe
        if (chartInstances[idCanvas]) {
            chartInstances[idCanvas].destroy();
        }

        const donnees = {
            labels: labels,
            datasets: [{
                label: labelDataset,
                data: data,
                borderColor: couleur,
                backgroundColor: `rgba(0, 0, 255, 0.1)`,
                borderWidth: 2,
                pointRadius: 3,
                tension: 0.3,
                fill: true
            }]
        };

        const config = {
            type: 'line',
            data: donnees,
            options: {
                responsive: true, // Activer le redimensionnement automatique
                maintainAspectRatio: false, // Désactiver le maintien des proportions
                plugins: {
                    legend: {
                        labels: {
                            color: 'black',
                            font: { size: 14 }
                        }
                    },
                    title: {
                        display: true,
                        text: titre,
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
                            text: "Valeurs",
                            color: 'black',
                            font: { size: 14 }
                        },
                        beginAtZero: false
                    }
                }
            }
        };

        // Créer une nouvelle instance de graphique et la stocker
        chartInstances[idCanvas] = new Chart(ctx, config);
    }

    // Fonction pour charger les données d'un graphique
    function chargerDonnees(action, idCanvas, titre, labelDataset, couleur) {
        fetch(`/DED/controllers/indicateurs.php?action=${action}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP ! statut : ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log(`Données reçues pour ${idCanvas}:`, data);

                if (data.error) {
                    console.error(`Erreur pour le graphique ${idCanvas}:`, data.error);
                    document.getElementById(idCanvas).parentElement.innerHTML = `<p style="color: red;">Erreur : ${data.error}</p>`;
                    return;
                }

                const labels = Object.keys(data).sort(); // Années
                const valeurs = labels.map(annee => data[annee]); // Valeurs correspondantes

                console.log(`Labels pour ${idCanvas}:`, labels);
                console.log(`Valeurs pour ${idCanvas}:`, valeurs);

                creerGraphique(idCanvas, titre, labels, valeurs, labelDataset, couleur);
            })
            .catch(error => {
                console.error(`Erreur lors du chargement des données pour ${idCanvas}:`, error);
                document.getElementById(idCanvas).parentElement.innerHTML = `<p style="color: red;">Erreur lors du chargement des données.</p>`;
            });
    }

    function chargerDonneesMultiplesCourbes(action, idCanvas, titre) {
        fetch(`/DED/controllers/indicateurs.php?action=${action}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(`Erreur pour le graphique ${idCanvas}:`, data.error);
                    document.getElementById(idCanvas).parentElement.innerHTML = `<p style="color: red;">Erreur : ${data.error}</p>`;
                    return;
                }
    
                
                const allYears = new Set();
                Object.values(data).forEach(regionData => {
                    Object.keys(regionData).forEach(year => allYears.add(year));
                });
                const labels = Array.from(allYears).sort();
    
                
                const couleurs = ['red', 'blue', 'green', 'orange', 'purple', 'brown', 'teal', 'pink'];
                let colorIndex = 0;
    
                
                const datasets = Object.entries(data).map(([region, values]) => {
                    const dataParAnnee = labels.map(annee => values[annee] ?? null);
                    return {
                        label: region,
                        data: dataParAnnee,
                        borderColor: couleurs[colorIndex++ % couleurs.length],
                        fill: false,
                        tension: 0.3,
                        spanGaps: true
                    };
                });
    
                const ctx = document.getElementById(idCanvas).getContext("2d");
    

    
                
                chartInstances[idCanvas] = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: titre,
                                color: 'black',
                                font: { size: 18 }
                            },
                            legend: {
                                labels: { color: 'black' }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: 'black' },
                                title: {
                                    display: true,
                                    text: "Années",
                                    color: 'black'
                                }
                            },
                            y: {
                                ticks: { color: 'black' },
                                title: {
                                    display: true,
                                    text: "Ratio natalité / mortalité",
                                    color: 'black'
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error(`Erreur lors du chargement des données pour ${idCanvas}:`, error);
                document.getElementById(idCanvas).parentElement.innerHTML = `<p style="color: red;">Erreur lors du chargement des données.</p>`;
            });
    }

    // Charger les données pour les graphiques
    chargerDonnees(
        'getMoyennePIBMondial',
        'pibChart',
        "Évolution du PIB Mondial par Année",
        "Moyenne du PIB Mondial (en $ US)",
        "blue"
    );

    chargerDonnees(
        'getEsperanceVieMondiale',
        'esperanceVieChart',
        "Évolution de l'Espérance de Vie Mondiale",
        "Moyenne de l'Espérance de Vie (en années)",
        "green"
    );

    chargerDonnees(
        'getPopulationMondiale',
        'populationChart',
        "Évolution de la Population Mondiale",
        "Population Mondiale (en milliards)",
        "red"
    );

    chargerDonnees(
        'getAutreIndicateur',
        'autreChart',
        "Autre Indicateur",
        "Valeurs de l'Indicateur",
        "purple"
    );

    chargerDonneesMultiplesCourbes(
        'getRatioParRegionParAnnee',
        'ratioRegionsCurve',
        "Évolution du Ratio Natalité / Mortalité par Région"
    );
});