const API_URL = "https://s3-4676.nuage-peda.fr/jeux/public/api/produits";
const sortOptions = document.querySelectorAll('.sort-option');

async function getProduits(page, plateforme, sortValue,searchValue) {
    try {
        const response = await fetch(`${API_URL}?page=${page}&plateformes.libelle=${plateforme}&designation=${searchValue}&order%5Bprix%5D=${sortValue}`);
        console.log(response);
        if (!response.ok) {
            throw new Error(`Erreur : ${response.statusText}`);
        }
        const data = await response.json();
        afficherProduits(data); // Appeler une fonction pour afficher les produits
    } catch (erreur) {
        console.error('Erreur lors de la récupération', erreur);
        throw erreur;
    }
}


function afficherProduits(produits) {
    const produitsContainer = document.querySelector('.row.justify-content-start');
    produitsContainer.innerHTML = ''; // Effacer le contenu actuel
    var lesProduits = produits["hydra:member"];
    console.log(lesProduits);
    for (let produit of lesProduits) {
        // Créer le HTML pour chaque produit
        const produitHTML = `
            <div class="col-md-4 p-0">
                <div class="item">
                    <a href="${produit.designation}">
                        <picture>
                            <img class="picture" src="https://s3-4676.nuage-peda.fr/jeux/public/images/produits/${produit.image}" style="max-width: 100%;">
                        </picture>
                    </a>
                    <div class="information">
                        <div class="text">
                            <div class="name">
                                <span class="title">${produit.designation}</span>
                            </div>
                        </div>
                        <div class="price px-2">${produit.prix}€</div>
                    </div>
                </div>
            </div>
        `;
        // Ajouter le produit au conteneur
        produitsContainer.insertAdjacentHTML('beforeend', produitHTML);
    };
}


// Ajouter un gestionnaire d'événements pour chaque élément
sortOptions.forEach(option => {
    option.addEventListener('click', function () {
        // Récupérer la désignation de plateforme actuelle
        document.addEventListener('DOMContentLoaded', () => {
            // Récupérer la désignation de plateforme actuelle depuis le Twig
            const designation = "{{ designation }}";
        });

                    // Récupérer la valeur de tri à partir de l'attribut "data-sort"
                    const sortValue = this.getAttribute('data-sort');

                    // Appeler la fonction afficherProduits avec la désignation de plateforme et la valeur de tri
                    getProduits(1, designation, sortValue, "");
    });
});


const searchInput = document.querySelector("input[name='search-input']");
// Écouter les modifications dans la barre de recherche
searchInput.addEventListener('input', function () {
    // Récupérer la valeur de recherche
    const searchValue = this.value.trim().toLowerCase();

    // Appeler la fonction afficherProduits avec la désignation de plateforme et la valeur de recherche
    getProduits(1, designation, "asc", searchValue);
});


document.addEventListener('DOMContentLoaded', () => {
    getProduits(1, designation,"asc",""); // Appel de getProduits avec le numéro de page 1 et la désignation de la plateforme actuelle
});