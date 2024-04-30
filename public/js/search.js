// Intercepter les événements de saisie dans le champ de recherche
$('#search-input').on('input', function() {
    // Récupérer le terme de recherche saisi par l'utilisateur
    var searchTerm = $(this).val();

    // Envoyer une requête AJAX au serveur avec le terme de recherche
    $.ajax({
        url: '/afficher_act', // Endpoint de recherche dans votre contrôleur Symfony
        method: 'GET',
        data: { nom_centre: searchTerm }, // Passer le terme de recherche comme paramètre GET
        success: function(response) {
            // Mettre à jour la table des activités avec les résultats de la recherche
            $('#activite-table-body').empty(); // Vider le contenu actuel de la table
            response.forEach(function(activite) {
                // Ajouter chaque activité dans la table
                var row = '<tr>' +
                    '<td>' + activite.id_activite + '</td>' +
                    '<td>' + activite.nom_activite + '</td>' +
                    '<td>' + activite.description + '</td>' +
                    '<td>' + activite.type + '</td>' +
                    '<td>' + activite.centre.nom_centre + '</td>' +
                    '<td><img src="' + activite.centre.photo + '" alt="Photo du centre" class="centre-photo" width="100" height="100"></td>' +
                    '<td>' +
                    '<a href="/supprimer/' + activite.id_activite + '" class="btn">Supprimer</a>' +
                    '<a href="/modifier/' + activite.nom_activite + '" class="btn">Modifier</a>' +
                    '</td>' +
                    '</tr>';
                $('#activite-table-body').append(row);
            });
        }
    });
});
