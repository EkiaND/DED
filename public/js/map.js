document.addEventListener("DOMContentLoaded", function () {
    const width = document.getElementById("map").clientWidth;
    const height = document.getElementById("map").clientHeight;

    const svg = d3.select("#map")
        .append("svg")
        .attr("width", width)
        .attr("height", height);

    const projection = d3.geoMercator()
        .scale(150)
        .translate([width / 2, height / 1.5]);

    const path = d3.geoPath().projection(projection);

    const colorScale = d3.scaleSequential(d3.interpolateBlues);

    const g = svg.append("g");

    const zoom = d3.zoom()
        .scaleExtent([1, 8])
        .on("zoom", (event) => {
            g.attr("transform", event.transform);
        });

    svg.call(zoom);

    const tooltip = d3.select("#map-container")
        .append("div")
        .attr("id", "tooltip");

    let currentView = "regions"; // Vue actuelle : "regions" ou "countries"
    let focusedRegion = null; // Région actuellement sélectionnée (si applicable)
    let isFetching = false; // Prevent duplicate fetch requests

    function updateMap(indicator, year) {
        if (currentView === "regions") {
            updateRegions(indicator, year);
        } else if (currentView === "countries" && focusedRegion) {
            console.log(`Fetching data for countries: indicator=${indicator}, year=${year}, region=${focusedRegion}`);
            fetch(`./controllers/indicateurs.php?action=getDistributionIndicateurParPays&idIndicateur=${indicator}&annee=${year}&region=${encodeURIComponent(focusedRegion)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data || data.length === 0) {
                        console.warn("Aucune donnée valide reçue pour les pays. Vérifiez la requête ou les données retournées.");
                    } else {
                        updateCountries(indicator, year, focusedRegion, data);
                    }
                })
                .catch(error => console.error("Erreur lors du chargement des données :", error));
        }
    }

    function updateCountries(indicator, year, region, data) {
        console.log(`Mise à jour des pays pour la région: ${region}, indicateur: ${indicator}, année: ${year}`);
        console.log("Données récupérées pour les pays :", data);

        if (!data || data.length === 0) {
            console.warn("Aucune donnée valide reçue pour les pays. Vérifiez la requête ou les données retournées.");
            return;
        }

        d3.json("./public/data/world.geojson").then(geojson => {
            const values = {};
            data.forEach(d => {
                values[d.nom_pays_geojson] = d.valeur;
            });

            console.log("Mapping des valeurs par pays :", values);
            console.log("Codes pays dans la base de données :", Object.keys(values));
            console.log("Codes pays dans world.geojson :", geojson.features.map(f => f.properties.ADM0_A3));

            const maxValue = d3.max(Object.values(values));
            colorScale.domain([0, maxValue]);

            const countries = g.selectAll("path")
                .data(geojson.features.filter(f => f.properties.REGION_WB === region));

            countries.enter()
                .append("path")
                .merge(countries)
                .attr("d", path)
                .attr("fill", d => {
                    const countryCode = d.properties.ADM0_A3;
                    const value = values[countryCode];
                    if (value === undefined) {
                        console.warn(`Pas de données pour le pays : ${d.properties.SOVEREIGNT}, Code : ${countryCode}`);
                    }
                    return value ? colorScale(value) : "#ccc";
                })
                .attr("stroke", "#000")
                .attr("stroke-width", 1.5)
                .on("mouseover", function (event, d) {
                    const countryCode = d.properties.ADM0_A3;
                    const value = values[countryCode] || "Données non disponibles";

                    tooltip
                        .html(`<strong>${d.properties.SOVEREIGNT}</strong><br>Valeur: ${value}`)
                        .style("left", `${event.pageX + 15}px`)
                        .style("top", `${event.pageY + 15}px`)
                        .classed("visible", true);
                })
                .on("mousemove", function (event) {
                    tooltip
                        .style("left", `${event.pageX + 15}px`)
                        .style("top", `${event.pageY + 15}px`);
                })
                .on("mouseout", function () {
                    tooltip.classed("visible", false);
                })
                .on("click", function (event, d) {
                    const countryCode = d.properties.ADM0_A3;
                    const countryName = d.properties.SOVEREIGNT;
                    const value = values[countryCode] || "Données non disponibles";

                    console.log(`Pays cliqué : ${countryName}, Code : ${countryCode}, Valeur : ${value}`);

                    // Fetch data specific to the clicked country
                    fetch(`./controllers/indicateurs.php?action=getCountryData&codePays=${countryCode}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Erreur HTTP: ${response.status}`);
                            }
                            return response.json();
                        })
                        .catch(error => console.error("Erreur lors du chargement des données du pays :", error));
                });

            countries.exit().remove();
        }).catch(error => console.error("Erreur lors du chargement des données :", error));
    }

    // Exposer la fonction dans le contexte global
    window.updateCountries = updateCountries;

    function updateRegions(indicator, year) {
        console.log(`Mise à jour des régions pour l'indicateur: ${indicator}, année: ${year}`);
        Promise.all([
            d3.json("./public/data/regions.geojson"),
            fetch(`./controllers/indicateurs.php?action=getDistributionIndicateurParRegion&idIndicateur=${indicator}&annee=${year}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    return response.json();
                })
        ]).then(([geojson, data]) => {
            const values = {};
            data.forEach(d => {
                values[d.nom_region] = d.valeur_moyenne;
            });

            const maxValue = d3.max(Object.values(values));
            colorScale.domain([0, maxValue]);

            const regions = g.selectAll("path")
                .data(geojson.features);

            regions.enter()
                .append("path")
                .merge(regions)
                .attr("d", path)
                .attr("fill", d => {
                    const region = d.properties.region || d.properties.nom_region;
                    return values[region] ? colorScale(values[region]) : "#ccc";
                })
                .attr("stroke", "#000")
                .attr("stroke-width", 1.5)
                .on("mouseover", function (event, d) {
                    const region = d.properties.region || d.properties.nom_region;
                    const value = values[region] || "Données non disponibles";

                    tooltip
                        .html(`<strong>${region}</strong><br>Valeur: ${value}`)
                        .style("left", `${event.pageX + 15}px`)
                        .style("top", `${event.pageY + 15}px`)
                        .classed("visible", true);
                })
                .on("mousemove", function (event) {
                    tooltip
                        .style("left", `${event.pageX + 15}px`)
                        .style("top", `${event.pageY + 15}px`);
                })
                .on("mouseout", function () {
                    tooltip.classed("visible", false);
                })
                .on("click", function (event, d) {
                    const region = d.properties.region || d.properties.nom_region;
                    const value = values[region] || "Données non disponibles";
                    console.log(`Région cliquée : ${region}, Valeur : ${value}`);
                })
                .on("dblclick", function (event, d) {
                    const region = d.properties.region || d.properties.nom_region;
                    console.log(`Zoom sur la région : ${region}`);
                    currentView = "countries";
                    focusedRegion = region;

                    // Déclencher un événement personnalisé pour charger les pays
                    const regionDblClickEvent = new CustomEvent("regionDblClick", {
                        detail: { region }
                    });
                    document.dispatchEvent(regionDblClickEvent);
                });

            regions.exit().remove();
        }).catch(error => console.error("Erreur lors du chargement des données :", error));
    }

    document.getElementById("indicatorSelect").addEventListener("change", () => {
        const indicator = document.getElementById("indicatorSelect").value;
        const year = document.getElementById("yearSelect").value;
        updateMap(indicator, year); // Ensure the correct view is updated
    });

    document.getElementById("yearSelect").addEventListener("input", () => {
        const indicator = document.getElementById("indicatorSelect").value;
        const year = document.getElementById("yearSelect").value;
        updateMap(indicator, year); // Ensure the correct view is updated
    });

    document.addEventListener("regionDblClick", (event) => {
        if (isFetching) {
            console.warn("Une requête est déjà en cours. Ignorée pour éviter les doublons.");
            return;
        }

        isFetching = true; // Lock fetch requests
        const indicator = document.getElementById("indicatorSelect").value;
        const year = document.getElementById("yearSelect").value;
        const region = encodeURIComponent(event.detail.region); // Encode the region name

        console.log(`Requête fetch : indicator=${indicator}, year=${year}, region=${region}`);
        fetch(`./controllers/indicateurs.php?action=getDistributionIndicateurParPays&idIndicateur=${indicator}&annee=${year}&region=${region}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                isFetching = false; // Unlock fetch requests
                if (data.length === 0) {
                    console.warn("Aucune donnée valide reçue pour les pays. Vérifiez la requête ou les données retournées.");
                } else if (typeof updateCountries === "function") {
                    updateCountries(indicator, year, decodeURIComponent(region), data); // Decode the region name
                } else {
                    console.error("La fonction updateCountries n'est pas définie.");
                }
            })
            .catch(error => {
                isFetching = false; // Unlock fetch requests
                console.error("Erreur lors du chargement des données des pays :", error);
            });
    });

    updateMap("pib", 2018);
});
