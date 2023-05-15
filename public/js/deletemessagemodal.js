$(document).ready(function() {
    $('.deleteee').click(function(){
        var id = $(this).data('nummessage'); // Utilisation de 'nummessage' en minuscules

        $('#numMessage').text(id);
        $('#lien').attr('href', 'http://s3-4676.nuage-peda.fr/jeux/public/admin-supprimer-support-message/' + id);
        $('#lien-support-message').attr('href', 'http://s3-4676.nuage-peda.fr/jeux/public/produit/admin-support-message/' + id);
    });
});
