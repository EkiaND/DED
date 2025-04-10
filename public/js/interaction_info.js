document.addEventListener("DOMContentLoaded", function () {
    let graphique = null;
  
    async function afficherDetailsPays(codePays) {
      if (!codePays) return;
  
      try {
        const response = await fetch(`./controllers/regions.php?action=getDetailsPays&code_pays=${codePays}`);
        if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);
        const details = await response.json();
  
        if (!details || !details.nom_pays || !details.nom_region) return;
  
        const paragraphe = document.querySelector("#infoPays p");
        if (paragraphe) {
          paragraphe.innerHTML = `
            Le pays sélectionné est <span style="color: #4e8ef7;">${details.nom_pays}</span>, 
            sa région est : <span style="color: #4e8ef7;">${details.nom_region}</span>.<br><br>
            Ci-dessous se trouvent l’ensemble de ses informations ainsi que ses indicateurs.
          `;
        }
      } catch (error) {
        console.error("Erreur lors de la récupération des détails du pays :", error);
      }
    }
  
    function resetTable() {
      const dataRow = document.querySelector("#infoPays table tbody tr");
      if (!dataRow) return;
      for (let cell of dataRow.cells) {
        if (cell.querySelector("span")) {
          cell.querySelector("span").textContent = "";
        } else {
          cell.textContent = "";
        }
      }
    }
  
    function mettreAJourIndicateursTable(codePays, annee) {
  if (!codePays || !annee) return;

  const url = `./controllers/indicateurs.php?action=getIndicateursParAnneePays&annee=${annee}&code_pays=${codePays}`;
  console.log(`Récupération des données pour le pays ${codePays} en ${annee}`);

  fetch(url)
    .then(response => {
      if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);
      return response.json();
    })
    .then(data => {
      console.log("Données reçues :", data);

      const dataRow = document.querySelector("#infoPays table tbody tr");
      if (!dataRow) return console.warn("Ligne de données non trouvée");

      const formatValue = (label, value, suffix = "") => {
        if (value === "NA") {
          return `<div style="text-align: center;">${label} : <span style="color: red;">Indisponible</span></div>`;
        } else {
          return `<div style="text-align: center;">${label} : ${value}${suffix}</div>`;
        }
      };

      const countryData = Array.isArray(data) ? data[0] : data;
      if (!countryData) return console.warn("Données pays non trouvées", data);

      if (dataRow.cells[0]) dataRow.cells[0].innerHTML = formatValue("PIB", countryData.pib || "NA");
      if (dataRow.cells[1]) dataRow.cells[1].innerHTML = formatValue("PIB par habitant", countryData.pib_par_habitant || "NA");

      if (dataRow.cells[2]) {
        const mort = countryData.taux_mortalite || "NA";
        const nat = countryData.taux_natalite || "NA";

        if (mort === "NA" && nat === "NA") {
          dataRow.cells[2].innerHTML = `<div style="text-align: center;"><span style="color: red;">Mortalité / Natalité : Indisponible</span></div>`;
        } else {
          const mortAff = mort === "NA" ? `<span style="color: red;">Indisponible</span>` : mort;
          const natAff = nat === "NA" ? `<span style="color: red;">Indisponible</span>` : nat;
          dataRow.cells[2].innerHTML = `<div style="text-align: center;">Mortalité / Natalité : ${mortAff} / ${natAff}</div>`;
        }
      }

      if (dataRow.cells[3]) {
        const span = dataRow.cells[3].querySelector("span");
        const val = formatValue("Utilisation internet", countryData.utilisation_internet || "NA");
        span ? (span.innerHTML = val) : (dataRow.cells[3].innerHTML = val);
      }

      if (dataRow.cells[4]) {
        const span = dataRow.cells[4].querySelector("span");
        const val = formatValue("Taux de chômage", countryData.taux_chomage || "NA", "%");
        span ? (span.innerHTML = val) : (dataRow.cells[4].innerHTML = val);
      }

      if (dataRow.cells[5]) {
        const span = dataRow.cells[5].querySelector("span");
        const val = formatValue("Espérance de vie", countryData.esperance_vie || "NA", " ans");
        span ? (span.innerHTML = val) : (dataRow.cells[5].innerHTML = val);
      }

      if (dataRow.cells[6]) dataRow.cells[6].innerHTML = formatValue("Mortalité infantile", countryData.mortalite_infantile || "NA");
      if (dataRow.cells[7]) dataRow.cells[7].innerHTML = formatValue("Densité de population", countryData.densite_population || "NA", " hab/km²");
      if (dataRow.cells[8]) dataRow.cells[8].innerHTML = formatValue("Consommation électricité", countryData.consommation_electricite || "NA");

      console.log("Tableau mis à jour !");
    })
    .catch(error => {
      console.error("Erreur lors de la récupération :", error);
      const erreurDiv = document.getElementById("erreur");
      if (erreurDiv) erreurDiv.textContent = "Impossible de récupérer les informations du pays.";
      resetTable();
    });
}

  
    async function mettreAJourInfosPays(codePays, annee) {
      await afficherDetailsPays(codePays);
      mettreAJourIndicateursTable(codePays, annee);
    }
  
    function creerGraphiqueIndicateur(idCanvas, titre, labels, data) {
      const ctx = document.getElementById(idCanvas).getContext('2d');
      if (graphique) graphique.destroy();
  
      graphique = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: titre,
            data: data,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 1,
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            x: { title: { display: true, text: "Année" } },
            y: { beginAtZero: false, title: { display: true, text: "Évolution de l'indicateur" } }
          }
        }
      });
    }
  
    function chargerDonneesEtAfficherGraphiques() {
      const pays = document.getElementById("pays1").value;
      const annee = parseInt(document.getElementById("annee").value, 10);
      const indicateur = document.getElementById("test").value;
      const erreurDiv = document.getElementById("erreur");
      erreurDiv.textContent = "";
  
      if (!pays || !annee || !indicateur) {
        erreurDiv.textContent = "Veuillez sélectionner un pays, une année et un indicateur.";
        resetTable();
        return;
      }
  
      mettreAJourInfosPays(pays, annee);
  
      fetch(`./controllers/indicateurs.php?action=getValeursIndicateur&code_pays=${pays}&indicateur=${indicateur}`)
        .then(response => {
          if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);
          return response.json();
        })
        .then(data => {
          if (!data || data.length === 0) {
            erreurDiv.textContent = "Aucune donnée disponible pour ce pays et cet indicateur.";
            return;
          }
  
          const annees = [];
          const valeurs = [];
          data.forEach(item => {
            const year = parseInt(item.annee, 10);
            if (year >= annee && year <= 2018) {
              annees.push(year);
              valeurs.push(parseFloat(item.valeur));
            }
          });
  
          if (annees.length === 0) {
            erreurDiv.textContent = `Aucune donnée disponible entre ${annee} et 2018.`;
            return;
          }
  
          creerGraphiqueIndicateur('graphPIB', `Évolution - ${indicateur} - entre ${annee} et 2018`, annees, valeurs);
        })
        .catch(err => {
          console.error("Erreur lors de la récupération des données graphiques :", err);
          erreurDiv.textContent = "Impossible de récupérer les données du graphique.";
        });
    }
  
    function chargerSelect(idSelect, url, labelKey, valueKey, defaultValue = null) {
      fetch(url)
        .then(response => {
          if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);
          return response.json();
        })
        .then(data => {
          const select = document.getElementById(idSelect);
          if (!select) return;
          select.innerHTML = "";
          data.forEach(item => {
            const option = new Option(item[labelKey], item[valueKey]);
            select.add(option);
          });
          if (defaultValue) select.value = defaultValue;
          select.dispatchEvent(new Event("change"));
        })
        .catch(error => {
          console.error(`Erreur lors du chargement du select ${idSelect} :`, error);
        });
    }
  
    function chargerListesPays() {
      const url = './models/pays.php?action=getPays';
      chargerSelect('pays1', url, 'nom_pays', 'code_pays', 'AGO');
    }
  
    // Événements
    document.getElementById("pays1").addEventListener("change", chargerDonneesEtAfficherGraphiques);
    document.getElementById("annee").addEventListener("input", chargerDonneesEtAfficherGraphiques);
    document.getElementById("test").addEventListener("change", chargerDonneesEtAfficherGraphiques);
  
    // Initialisation
    chargerListesPays();
    setTimeout(chargerDonneesEtAfficherGraphiques, 1000);
  });
  