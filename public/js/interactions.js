document.addEventListener("DOMContentLoaded", function () {
    // Fonction générique pour remplir un select à partir d'une API
    function chargerSelect(idSelect, url, labelKey, valueKey) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const select = document.getElementById(idSelect);
                if (!select) {
                    console.warn(`Élément #${idSelect} introuvable.`);
                    return;
                }

                data.forEach(item => {
                    const option = new Option(item[labelKey], item[valueKey]);
                    select.add(option);
                });
            })
            .catch(error => {
                console.error(`Erreur lors du chargement du select #${idSelect} :`, error);
            });
    }

    // Fonction pour remplir les 2 listes de pays (pays1 & pays2)
    function chargerListesPays() {
        const url = '/DED/models/pays.php?action=getPays';
        chargerSelect('pays1', url, 'nom_pays', 'code_pays');
        chargerSelect('pays2', url, 'nom_pays', 'code_pays');
    }

    // Fonction pour remplir la liste des indicateurs
    function chargerListeIndicateurs() {
        const url = '/DED/models/indicateur.php?action=getIndicateurs';
        chargerSelect('indicateur', url, 'nom_indicateur', 'id_indicateur');
    }

    // Gestion du bouton de comparaison
    const btn = document.getElementById("bouton_comparer");
    if (btn) {
        btn.addEventListener("click", function () {
            const pays1 = document.getElementById("pays1").value;
            const pays2 = document.getElementById("pays2").value;
            const indicateur = document.getElementById("indicateur").value;
            const erreurDiv = document.getElementById("erreur");

            erreurDiv.textContent = "";

            if (!pays1 || !pays2 || !indicateur) {
                erreurDiv.textContent = "Veuillez sélectionner les deux pays et un indicateur.";
                return;
            }

            if (pays1 === pays2) {
                erreurDiv.textContent = "Les deux pays doivent être différents.";
                return;
            }

            fetch(`/DED/controllers/indicateurs.php?action=comparerPays&pays1=${pays1}&pays2=${pays2}&indicateur=${indicateur}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        erreurDiv.textContent = `Erreur : ${data.error}`;
                        return;
                    }

                    // Appel à la fonction du graphique (à définir dans graphiques.js)
                    creerGraphiqueComparaison(
                        'comparaisonChart',
                        `Évolution de ${data.indicateur} pour ${data.nomPays1} et ${data.nomPays2}`,
                        data.annees,
                        data.valeurs1,
                        data.valeurs2,
                        data.nomPays1,
                        data.nomPays2
                    );
                })
                .catch(error => {
                    console.error("Erreur lors de la requête :", error);
                    erreurDiv.textContent = "Une erreur s'est produite lors de la comparaison.";
                });
        });
    }

    // Chargement au démarrage
    chargerListesPays();
    chargerListeIndicateurs();
});
