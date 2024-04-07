const API_URL = "https://s3-4676.nuage-peda.fr/jeux/public/api/produits";

async function getProduits(page,plateforme){
    try{
        const response = await fetch(`${API_URL}?page=${page}&plateformes.libelle=${plateforme}`);
        console.log(response);
        if(!response.ok){
            throw new Error(`Erreur : ${response.statusText}`);
        }
        const data = await response.json();
        afficherProduits(data); // Appeler une fonction pour afficher les produits
    }catch(erreur){
        console.error('Erreur lors de la récupération',erreur);
        throw erreur;
    }
}

function afficherProduits(produits) {
    const produitsContainer = document.querySelector('.row.justify-content-start');
    produitsContainer.innerHTML = ''; // Effacer le contenu actuel
    var lesProduits = produits["hydra:member"];
    console.log(lesProduits);
    for(let produit of lesProduits) {
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

document.addEventListener('DOMContentLoaded', () => {
    getProduits(1, designation); // Appel de getProduits avec le numéro de page 1 et la désignation de la plateforme actuelle
});