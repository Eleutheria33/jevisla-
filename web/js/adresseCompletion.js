/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//
// pavé d'autocomplétion de recherche adresse
var dept =  $('#departement').val();
var indice ;


$('#adresse').autocomplete({
    autoFocus: true,
    source: function(requete, reponse){
        $.ajax({
            url : 'https://api-adresse.data.gouv.fr/search', // on appelle le script JSON
            dataType : 'json', // on spécifie bien que le type de données est en JSON
            data : {
                q : $('#adresse').val() // on donne la chaîne de caractère tapée dans le champ de recherche
            },
            success : function(donnee){
                reponse($.map(donnee.features, function(objet){
                   var touteLadresse = ''; 
                    touteLadresse = objet.properties.label;
                    console.log(touteLadresse);
                    return touteLadresse; // on retourne cette forme de suggestion
                }));
            }
        });
    },   
    minLength : 3, // on indique qu'il faut taper au moins 3 caractères pour afficher l'autocomplétion
    classes: {
    "ui-autocomplete": "highlight"
    },
    _renderMenu: function( ul, items ) {
        var that = this;
        $.each( items, function( index, item ) {
          that._renderItemData( ul, item );
        });
        $( ul ).find( "li:odd" ).addClass( "odd" );
      },
    select : function(event, ui){ // lors de la sélection d'une proposition
        $('#adresse').val( ui.item.desc ); // on ajoute la description de l'objet dans un bloc
    }
});     

// pavé d'autocomplétion de recherche adresse
$('#commune').autocomplete({
        source: function(requete, reponse){
        $('.buttonload').show();   
        $.ajax({
            url : Routing.generate('jevislaevts_searchCommune_homepage'), // on appelle le script JSON
            dataType : 'json', // on spécifie bien que le type de données est en JSON
            data : {
                commune : String($('#commune').val()) // on donne la chaîne de caractère tapée dans le champ de recherche
            },
            success: function(donnee){
            reponse($.map(donnee, function(donnee){    
                //dump(donnee.nom);
                var communeAll = ''; 
                communeAll = donnee.nom ;
                communeAll += ' : ';
                //communeAll += objet.properties.departement.code;
                communeAll += donnee.nomDept;
                $('.buttonload').hide(); 
                return communeAll; // on retourne cette forme de suggestion
            
             }));
            $('.buttonload').hide(); 
            }
        });
    },   
    minLength : 3, // on indique qu'il faut taper au moins 3 caractères pour afficher l'autocomplétion
    classes: {
    "ui-autocomplete": "highlight"
    },
    _renderMenu: function( ul, items ) {
        var that = this;
        $.each( items, function( index, item ) {
          that._renderItemData( ul, item );
        });
      },
    select : function(event, ui){ // lors de la sélection d'une proposition
        console.log(ui);
       // $('#commune').val( ui.item.desc ); // 
        var zon = ui.item.label;
        var pos = zon.indexOf(":");
        console.log(zon);
        console.log(pos);
        var commun = zon.substring(0, pos);
        console.log(commun);
        var dept = zon.substring(pos+1, pos+50);
        console.log(dept);
        $('#commune').val(commun);
        var nomC = document.getElementById('communeC');
        $('#communeC').val(commun);
        $('#deptC').val(dept);
        console.log($('#deptC').val());
        console.log($('#communeC').val());
    }    
}); 

