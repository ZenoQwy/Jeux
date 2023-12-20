$(document).ready(function() {
    $('.deletee').click(function(){
        var id = $(this).data('numproduit');
        var nom = $(this).data('nomproduit');
        var url = '/supprimer/produits/' + id;

        $('#numProduit').text(id);
        $('#nomProduit').text(nom);
        $('#deleteArticleForm').attr('action', url);
        $('#lien').attr('href','http://s3-4676.nuage-peda.fr/jeux/public/admin-supprimer-produit/'+id);
        $('#lienarticle').attr('href','http://s3-4676.nuage-peda.fr/jeux/public/produit/admin-gestion-produit-afficher/'+id);
    })
});
