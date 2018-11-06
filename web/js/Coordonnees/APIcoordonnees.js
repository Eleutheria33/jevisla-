var markers = [];  // tableau des marqueurs
var geocoder;
var radiusWidget;
var rectangle;
var myMap;
var infoWindow;
var distanceWidget;
var nord ;
var sud ;
var est ;
var ouest ;
var interrupteur = '1';
var degre;
var latlng;
var latitude = parseFloat(sessionStorage.getItem('latitude'));
var longitude = parseFloat(sessionStorage.getItem('longitude')) ;
var latitude1;
var latitude2;
var longitude1;
var longitude2;
var ne ;
var sw ;
var pseudonyme ;
var prenomM ;
var nomM ;
var id ; 
var userId = parseFloat(sessionStorage.getItem('id'));
var pseudo ;
var prenom ;
var nom ;
var texteGM ;
var zoomGM ;
var adresseGM;
var email ;
var portable ;
var avatar ;
var devise ;
var adressComLat;
var adressComLng;
var positionEvtsCult;
var slider ;
var output ;
var coordonneesRec ;
var nbrVoisins ;
var markersVoisins = [];
var eventUsr ;
var adresseUrl ;
var date_time ;
var qteNonLus ;

function ajustementMap(codergeo, myMap) {     
    
     var  paramAdr = $('input').val();
     $('#recupAdr').textcontent = '';
        if (paramAdr) { 
            
            requeteAdresseMap("https://api-adresse.data.gouv.fr/search/?q="+ paramAdr, function (reponse) {

                // Transforme la réponse en un tableau d'articles
                      var adresse = JSON.parse(reponse);
                      
                      var touteLadresse = adresse.features[0].properties.id + "<br>";
                      touteLadresse += adresse.features[0].properties.label + "<br>";
                      touteLadresse += adresse.features[0].properties.context + "<br>";
                      touteLadresse += adresse.features[0].properties.city + "<br>";
                      touteLadresse += adresse.features[0].properties.citycode + "<br>";
                      touteLadresse += adresse.features[0].properties.postcode + "<br>";
                      touteLadresse += adresse.features[0].properties.type + "<br>";
                      touteLadresse += adresse.features[0].properties.housenumber + "<br>";
                      touteLadresse += adresse.features[0].properties.street + "<br>";
                      touteLadresse += adresse.features[0].properties.name + "<br>";
                      touteLadresse += adresse.features[0].geometry.coordinates[0] + "<br>";
                      touteLadresse += adresse.features[0].geometry.coordinates[1];
                    
                    var latlon = {lat : adresse.features[0].geometry.coordinates[1], lng: adresse.features[0].geometry.coordinates[0]  };  
                    maPosition(codergeo, myMap, latlon);
                    var lat = adresse.features[0].geometry.coordinates[1];
                    var lon = adresse.features[0].geometry.coordinates[0];  
            });
            
        } else {

        document.getElementById("monJson").innerHTML = 'Vous devez saisir une adresse';

        }
     
};  


function requeteAdresseMap(url, callback) {
    var req = new XMLHttpRequest();
    req.open("GET", url);
    
    req.addEventListener("load", function (e) {
        e.preventDefault();
        if (req.status >= 200 && req.status < 400) {
            // Appelle la fonction callback en lui passant la réponse de la requête
            callback(req.responseText);
        } else {
            console.error(req.status + " " + req.statusText + " " + url);
        }
    });

    req.addEventListener("error", function (e) {
        e.preventDefault();
        console.error("Erreur réseau avec l'URL " + url);
    });
    req.send(null);
}

function maPosition(codergeo,  myMap, position) {
	  var latlng = {lat: parseFloat(position.lat), lng: parseFloat(position.lng)};
	  codergeo.geocode({'location': latlng}, function(results, status) {
		    if (status === 'OK') {
			      if (results[1]) {
			      repositionnement(latlng, myMap); 
			      } else {
			        window.alert('No results found');
			      }
		    } else {
		      window.alert('Geocoder failed due to: ' + 'wouah' + status);
		    }
	    });
}

function repositionnement(repos, myMap) {
	myMap.setCenter(repos);
        
        var marker = new google.maps.Marker({
	        map: myMap,
	        draggable: false,
	        position: repos 
	        });
            marker.setIcon({
                path: google.maps.SymbolPath.CIRCLE,
                scale: 7,
                fillColor: "white",
                fillOpacity: 0.9,
                strokeWeight: 3,
                strokeColor: "red"
            });    
        markers.push(marker);
}



function geoCodageAdress(lat, lng, myMap) {
    cleanMarkers();
    latlng = {lat: lat, lng: lng};        
    repositionnement(latlng, myMap);
}

// 



function initMap()  {
        recupAdresseUrl()
        id = sessionStorage.getItem('id');
        latlng = {lat: latitude, lng: longitude};  
        //degre = 0.0005;
        myMap = new google.maps.Map(document.getElementById('map'), {
            zoom: 17,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            gestureHandling: 'greedy',
        });	
        repositionnement(latlng, myMap);
        codergeo = new google.maps.Geocoder();
        // deux écoutes sur la zone saisie et assistance 
        $('#recupAdr').on("click", function() {              
            ajustementMap(codergeo, myMap);    
        }); 
        $('#adresse').on("blur", function() {              
            ajustementMap(codergeo, myMap);    
        }); 
        getCoordonnees();
}
//// fin initMap
 
function initMapCulturel()    {
        adressComLat = sessionStorage.getItem('latitudeEC'); 
        adressComLng = sessionStorage.getItem('longitudeEC');
        positionEvtsCult = {lat: latitude, lng: longitude};  
        //degre = 0.0005;
        mapCulturel = new google.maps.Map(document.getElementById('mapCulturel'), {
            zoom: 16,
            center: positionEvtsCult,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            panControl: false,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: true,
            overviewMapControl: true,
            rotateControl: false,
            zoomControlOptions: {style: google.maps.ZoomControlStyle.SMALL}
        });	
        repositionnement(latlng, mapCulturel);
        codergeo = new google.maps.Geocoder();
        // deux écoutes sur la zone saisie et assistance 
        maPosition(codergeo,  mapCulturel, positionEvtsCult); 
}
//// fin initMap




// affiche dans une info bulle les coordonnées de la zone dessinée (rectangle).

      
function showNewRect() {
        
        recalculRectangle();
	 
	var contentString = '<b>Modification zone de contact.</b><br>' +
	    '<strong>Nord-Est</strong> : <br>' + '<div style="margin-left: 30px;">' + ne.lat() + ', <br> ' + ne.lng() + '</div>'  +
	    '<strong>Sud-Ouest</strong> : <br>' + '<div style="margin-left: 30px;">' + sw.lat() + ', <br>' + sw.lng() + '</div>';

	// place l'info des positions dans la fenêtre .
	infoWindow.setContent(contentString);
	infoWindow.setPosition(ne);
	// affiche la fenêtre
	infoWindow.open(myMap);
        //myMap.setCenter(google.maps.LatLng(latitude, longitude));
          $("#sauvegarde").removeClass("btn-warning");
          $("#sauvegarde").addClass("btn-danger glyphicon glyphicon-warning-sign");
           
          
        ajaxCall(); 
}

function zoneContact() {
        
        if (interrupteur === 'NaN' || interrupteur === '1') {
		interrupteur = '2';
	 	afficheZone();
	} else {
		supprimeZone();    
	} 
       
}

function afficheZone() {
 bounds = {
            north: nord,
            south: sud,
            east: est,
            west: ouest
          };
          rectangle = new google.maps.Rectangle({
            bounds: bounds,
            strokeOpacity: 1,
            strokeColor: '#5bc0de',
            opacity: 0.1,
            strokeWeight: -1,
            fillColor: 'lightyellow',
            fillOpacity: 0.35,
            editable: true,
            draggable: false,
            zIndex: -100
          });
           rectangle.setMap(myMap);
           recalculRectangle();
           rectangle.addListener('bounds_changed', showNewRect);
           infoWindow = new google.maps.InfoWindow();   
   
    
}
// efface la zone de contact (zone verte sur la carte 
function supprimeZone(){
            interrupteur = '1';
            rectangle.setMap(null);
            infoWindow.close(myMap);   
    
}
// récupération des coordonnées du rectangle zone de contact
function recalculRectangle() {
            ne = rectangle.getBounds().getNorthEast();
            sw = rectangle.getBounds().getSouthWest();    
}

// recalcul de la zone contact
function calculzoneContact(coordonnees) {
        // création de rectangle nouvel utilisateur
        if (coordonnees.latitudeNE === null ) {
            nord =  latitude + 0.001; // parseFloat(coordonnees.latitudeNE);
            sud  =  latitude - 0.001; // parseFloat(coordonnees.latitudeSW);
            est  =  longitude + 0.001;  //parseFloat(coordonnees.longitudeNE);
            ouest =  longitude - 0.001; //parseFloat(coordonnees.longitudeSW);  
        // création d'une zone par défaut
           coordonneesRec = [nord, est, sud, ouest]; 
           setCoordonnees();
        } else {
            nord =  parseFloat(coordonnees.latitudeNE);
            sud  =   parseFloat(coordonnees.latitudeSW);
            est  =   parseFloat(coordonnees.longitudeNE);
            ouest = parseFloat(coordonnees.longitudeSW);    
        }
	  
        zoneContact();
        ajaxCall();
}
// récupération des coordonnées dans base de données user
function getCoordonnees() {
        $.ajax({
            url : Routing.generate('jevisla_map_coordonnees'), // on appelle le script JSON
            data: { "id": id }, 
            success: function(coordonnees){
                calculzoneContact(coordonnees);
            }
        });
}

function validCoordonnees() {
    coordonneesRec = [ne.lat(), ne.lng(), sw.lat(), sw.lng()];
    setCoordonnees();
    
}

// enregistrement des nouveaux coordonnées sélectées par l'utilisateur
function setCoordonnees() {
        $.ajax({
            url : Routing.generate('jevisla_map_coordonnees'), // on appelle le script JSON
            data: { "zone" : coordonneesRec, 'id': id }, 
            success: function(donnee){
                //alert('Vos nouvelles coordonnées sont enregistrées'); 
                var alerteS = document.querySelector(".success");
                alerteS.style.display = 'inline-block';
                alerteS.style.opacity = "1";
                $("#sauvegarde").removeClass("btn-danger glyphicon glyphicon-warning-sign");
                $("#sauvegarde").addClass("btn-warning");
                setTimeout(function(){
                    alerteS.style.display = 'none';  
                }, 4000);
            }
        });
}
// récupération des coordonnées des voisins autorisées de la zone contact utilisateurs
function ajaxCall() {
        recalculRectangle();   
        coordonnees = [ne.lat(), ne.lng(), sw.lat(), sw.lng(), latitude, longitude];
    
        $.ajax({
            url : Routing.generate('jevisla_map_recupvoisins'), // on appelle le script JSON
            data: { "zone" : coordonnees }, 
            success: function(donnee){
               
                creationMapMarkers(donnee);
            }
        });
}


// création des marqueurs
function creationMapMarkers(tableauMarkers) {
        cleanMarkers(); 
        $("#voisinFiche").children().remove();
        nbrVoisins = tableauMarkers.length;
        for (var i = 0; i < tableauMarkers.length; i++) {
            var position = {lat : tableauMarkers[i].latitude, lng: tableauMarkers[i].longitude  };  
            if (tableauMarkers[i].ficheGoogle !== null) {
                pseudo = tableauMarkers[i].ficheGoogle.pseudo1; 
                devise = tableauMarkers[i].ficheGoogle.devise;
                texteGM = tableauMarkers[i].ficheGoogle.texte;
                adresseGM = tableauMarkers[i].ficheGoogle.adresse;
                email = tableauMarkers[i].ficheGoogle.mail;
                portable = tableauMarkers[i].ficheGoogle.phone;
                if (tableauMarkers[i].ficheGoogle.avatar !== null) {
                    avatar = tableauMarkers[i].ficheGoogle.avatar.webPath;
                } else {
                    avatar = "img/user.png";    
                }
            } else {
                pseudo = tableauMarkers[i].pseudo; 
                devise = "";
                texteGM = "";
                adresseGM = "";
                email = "";
                portable = "";
                avatar = "img/user.png";
            }
               
              
            
           var marker = creationMarker(position, pseudo, devise, nom, texteGM, adresseGM, email, portable, avatar, i);
           creationvoisinFiche(avatar, pseudo, texteGM, i, marker, tableauMarkers[i].id); 
        }	
}



// création div du répertoire des fiches voisins
function creationvoisinFiche(avatar, pseudo, texte, i, marker, idVoisin){
    var voisinFiche = document.getElementById("voisinFiche"); 
    var colorBg = i % 2; 
    var messagesBtn ;
        qteNonLus = '';
        // récupération message non lus et affichage du bouton messagerie 
        $.ajax({
            url : Routing.generate('jevisla_messagerie_count_non_lus'), // on appelle le script JSON
            data: { "idUser" : userId, "idVoisin" : idVoisin }, 
            success: function(donnee){
               nbrMessNLu =  JSON.parse(donnee);
               qteNonLus = nbrMessNLu[0].number;
           
                var voisinFicheBodyRow = document.createElement("div");
                voisinFicheBodyRow.classList.add("voisin" + i);
                    voisinFicheBodyRow.classList.add("row");
                        var voisinFicheBodyColImg3 = document.createElement("div");
                        voisinFicheBodyColImg3.classList.add("col-xs-4", "col-sm-4", "col-md-4", "col-lg-4", "encadrement");
                        voisinFicheBodyColImg3.prepend(createImage(avatar));
                        voisinFicheBodyRow.prepend(voisinFicheBodyColImg3);
                        
                        var voisinFicheBodyColPseudo3 = document.createElement("div");
                        voisinFicheBodyColPseudo3.classList.add("col-xs-8", "col-sm-8", "col-md-8", "col-lg-8","encadrement");
                        voisinFicheBodyColPseudo3.style.color =  "black";  //' #'+(Math.random()*0xFFFFFF<<0).toString(16);
                        voisinFicheBodyColPseudo3.style.fontWeight = "bolder";
                        
                        var voisinFichePseudo = document.createElement("div");
                        voisinFichePseudo.classList.add("col-xs-12", "col-sm-12", "col-md-12", "col-lg-12");
                        voisinFichePseudo.style.color =  "black";  //' #'+(Math.random()*0xFFFFFF<<0).toString(16);
                        voisinFichePseudo.style.fontWeight = "bolder";
                        voisinFichePseudo.prepend(document.createTextNode(pseudo));
                        
                // bouton pour envoyer un message à un voisin
                // pas de discussion avec soi-même
                    if (idVoisin !== userId) {
                        var voisinFicheBodyColBtn = document.createElement("a");
                        voisinFicheBodyColBtn.classList.add("btn", "btn-info", "col-xs-12", "col-sm-12", "col-md-12", "col-lg-12");
                        voisinFicheBodyColBtn.setAttribute('data-id', idVoisin);
                        voisinFicheBodyColBtn.setAttribute('id', 'sendMessage');
                        
                        var url = Routing.generate('jevisla_messagerie_conversation', {id: idVoisin});
                        voisinFicheBodyColBtn.setAttribute('href', url);
                        
                        var voisinFicheBodyColSpan = document.createElement("span");
                        var SpanNumberNlus = document.createElement("span");
                                SpanNumberNlus.style.color = "#ffffff";
                                if (qteNonLus) { 
                                    SpanNumberNlus.innerHTML = 'Message' + "<strong style='color: #d9534f;'>" + ' (' + qteNonLus + ')' + "</strong>";
                                } else {
                                    SpanNumberNlus.innerHTML = 'Message';
                               }
                        
                        voisinFicheBodyColSpan.prepend(SpanNumberNlus);
                        voisinFicheBodyColBtn.append(voisinFicheBodyColSpan);
                        voisinFicheBodyColPseudo3.append(voisinFicheBodyColBtn);
                    }
                        voisinFicheBodyColPseudo3.prepend(voisinFichePseudo);
                        voisinFicheBodyRow.append(voisinFicheBodyColPseudo3);

                if (colorBg === 0) { // alternance (pair/impair) de gris et blanc sur les lignes voisins 
                    voisinFicheBodyRow.style.background = '#f2f0f1';    
                } else {
                    voisinFicheBodyRow.style.background = '#ffffff';      
                }
                voisinFiche.append(voisinFicheBodyRow);
            
// alternative entre téléphone mobile et autres appareils            
             
                $(".voisin" + i).click(function(){
                    reinitFicheVoisins();     // réinitialise les backgrounds div voisins
                    reinitMarkers();  // réinitialise les couleurs markers
                    var areaVoisin = document.querySelector(".voisin" + i);
                    var colorRandom = ' #'+(Math.random()*0xFFFFFF<<0).toString(16);
                    areaVoisin.style.background = colorRandom;
                    marker.setIcon({
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 16,
                        fillColor: colorRandom,
                        fillOpacity: 0.9,
                        strokeWeight: 0,
                        strokeColor: "red"
                    });
                    marker.setOptions({
                        zIndex: 1000                
                    });
                });
                         
            
                $(".voisin" + i).mouseover(function(){
                    reinitFicheVoisins();     // réinitialise les backgrounds div voisins
                    reinitMarkers();  // réinitialise les couleurs markers
                    var areaVoisin = document.querySelector(".voisin" + i);
                    var colorRandom = ' #'+(Math.random()*0xFFFFFF<<0).toString(16);
                    areaVoisin.style.background = colorRandom;
                    marker.setIcon({
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 16,
                        fillColor: colorRandom,
                        fillOpacity: 0.9,
                        strokeWeight: 0,
                        strokeColor: "red"
                    });
                });
            }
        });        
             
}
// réinitialisation des backgrounds alternatifs blanc//gris
function reinitFicheVoisins() {
 
    for (var i = 0; i < nbrVoisins; i++) { 
        var ficheVoisin = document.querySelector(".voisin" + i);
        if (ficheVoisin) {
            if ((i % 2)=== 0) { // alternance (pair/impair) de gris et blanc sur les lignes voisins 
                ficheVoisin.style.background = '#f2f0f1';    
            } else {
                ficheVoisin.style.background = '#ffffff';      
            } 
        }
    }   
    
}
// remise à l'état initial des markers
function reinitMarkers() {
    
    $.each(markers, function() {
        this.setIcon({
            path: google.maps.SymbolPath.CIRCLE,
            scale: 7,
            fillColor: "white",
            fillOpacity: 0.9,
            strokeWeight: 3,
            strokeColor: "red"
        }); 
        this.setOptions({
                zIndex: 0                
                });
    });
    
} 


function createWindow(contentString, pos){
  		// place l'info des positions dans la fenêtre .
	infoWindow.setContent(contentString);
	infoWindow.setPosition(pos);
	// affiche la fenêtre
	infoWindow.open(myMap);
    } 

function cleanMarkers(){
  		// place l'info des positions dans la fenêtre .
	$.each(markers, function() {
        this.setMap(null);
        });
    }
    
function createImage (avatar) {
    var imageImg = new Image();
            imageImg.style.width = "50px";
            imageImg.style.height = "50px";
            // composition alternative (développement vs Production) de l'adresse de l'image avatar
            if (avatar !== "none") {
                 imageImg.src = adresseUrl + avatar;
            }  
            imageImg.style.borderRadius = '50%';
            imageImg.style.padding = '1px 1px 1px 1px'; 
            imageImg.alt = "Ma photo"; 
            
    return imageImg; 
}    

function creationMarker (position, pseudo, devise, nom, texteGM, adresseGM, email, portable, avatar, i){
    
// création de l'infowindow du marker
    var infoWindowContent = document.createElement("div");
    infoWindowContent.classList.add("sizeWindowMap");
        var classPanelSuccess = document.createElement("div");
        classPanelSuccess.classList.add("panel","panel-success", "sizeWindowMap");
        infoWindowContent.prepend(classPanelSuccess);
            var classPanelBody = document.createElement("div");
            classPanelBody.classList.add("panel-body");
            classPanelSuccess.prepend(classPanelBody); 
                var classBodyRow = document.createElement("div");
                classBodyRow.classList.add("row");
                classPanelBody.prepend(classBodyRow);
                    var classBodyRowCol7 = document.createElement("div");
                    classBodyRowCol7.classList.add("col-xs-12", "col-md-12", "col-lg-12");
                    classBodyRow.prepend(classBodyRowCol7);
                        var classBodyInfos = document.createElement("div");
                        classBodyRowCol7.prepend(classBodyInfos);
                        classBodyInfos.classList.add("col-xs-12", "col-md-12", "col-lg-12");
                        classBodyInfos.style.border = '1px ' + ' groove ' + ' #DFF0D8';
                            var classBodyRowStrong2 = document.createElement("div");
                            classBodyInfos.prepend(classBodyRowStrong2);
                                var classBodyRowSpan2 = document.createElement("span");
                                classBodyRowSpan2.style.color = "#811411";
                                classBodyRowSpan2.classList.add("glyphicon", "glyphicon-pencil");
                                classBodyRowSpan2.innerHTML =  "<strong style='color: #428bca;'> Ma devise : </strong>" + devise;
                                classBodyRowStrong2.prepend(classBodyRowSpan2);
                            var classBodyRowStrong3 = document.createElement("div");
                            classBodyInfos.prepend(classBodyRowStrong3);
                                var classBodyRowSpan3 = document.createElement("span");
                                classBodyRowSpan3.style.color = "#f0ad4e";
                                classBodyRowSpan3.classList.add("glyphicon", "glyphicon-home");
                                classBodyRowSpan3.prepend(document.createTextNode(adresseGM));
                                classBodyRowSpan3.innerHTML =  "<strong style='color: #428bca;'> Mon adresse : </strong>" + adresseGM;
                                classBodyRowStrong3.prepend(classBodyRowSpan3);
                            var classBodyRowStrong4 = document.createElement("div");
                            classBodyInfos.prepend(classBodyRowStrong4);
                                var classBodyRowSpan4 = document.createElement("span");
                                classBodyRowSpan4.style.color = "#ea4335";
                                classBodyRowSpan4.classList.add("glyphicon", "glyphicon-phone");
                                classBodyRowSpan4.innerHTML =  "<strong style='color: #428bca;'> Mon téléphone : </strong>" + portable;
                                classBodyRowStrong4.prepend(classBodyRowSpan4);
                            var classBodyRowStrong5 = document.createElement("div");
                            classBodyInfos.prepend(classBodyRowStrong5);
                                var classBodyRowSpan5 = document.createElement("span");
                                classBodyRowSpan5.style.color = "green";
                                classBodyRowSpan5.classList.add("glyphicon", "glyphicon-envelope");
                                classBodyRowSpan5.innerHTML =  "<strong style='color: #428bca;'> Mon mail : </strong>" + email;
                                classBodyRowStrong5.prepend(classBodyRowSpan5);
                        var classBodyRowStrong7 = document.createElement("strong");
                        classBodyRowCol7.prepend(classBodyRowStrong7);        
                            var classBodyRowSpan7 = document.createElement("div");
                            classBodyRowSpan7.style.border = '1px ' + ' groove ' + ' #DFF0D8';
                            classBodyRowSpan7.classList.add("col-xs-12", "col-md-12", "col-lg-12");
                            classBodyRowSpan7.style.color = "#f22e2e";
                            classBodyRowSpan7.classList.add("glyphicon", "glyphicon-heart");
                            classBodyRowSpan7.innerHTML =  "<strong style='color: #428bca;'> Mon message : </strong>" + texteGM;
                            classBodyRowStrong7.append(classBodyRowSpan7);
                        var classBodyRowStrong6 = document.createElement("strong");
                        classBodyRowCol7.prepend(classBodyRowStrong6);
                            var classCol12 = document.createElement("div");
                            classCol12.style.border = '1px ' + ' groove ' + ' #DFF0D8';
                            var classCol12Span = document.createElement("div");
                            classCol12.style.color = "#1e90ff";
                            classCol12.classList.add("col-xs-12", "col-md-12", "col-lg-12", "glyphicon", "glyphicon-camera" );
                                classCol12Span.innerHTML =  "<strong style='color: #428bca;'> Mon avatar : </strong>";
                                classCol12.prepend(createImage(avatar));
                                classCol12.prepend(classCol12Span); 
                                classBodyRowStrong6.prepend(classCol12);    
            var classPanelHeading = document.createElement("div");
            classPanelHeading.classList.add("panel-heading");
            classPanelSuccess.prepend(classPanelHeading);
                var classPanelHeadingH3 = document.createElement("h2");
                classPanelHeadingH3.classList.add("panel-title");
                //classPanelHeadingH3.prepend(document.createTextNode('Je suis ' + '<strong>' + pseudo + '</strong>'));
                classPanelHeadingH3.innerHTML =  "<strong style='color: #428bca;'>" + pseudo + '</strong>';
                classPanelHeading.prepend(classPanelHeadingH3);  
     
    
    
    
// création du marqueur et de son infowindow        
        pseudonyme = pseudo;
        prenomM = prenom;
        nomM = nom;
        var infoWindow = new google.maps.InfoWindow({maxWidth: 300});
        marqueur =  new google.maps.Marker({
                          position: position,
                          map: myMap,
                          animation: google.maps.Animation.Drop
                    });
        marqueur.setIcon({
            path: google.maps.SymbolPath.CIRCLE,
            scale: 7,
            fillColor: "white",
            fillOpacity: 0.9,
            strokeWeight: 3,
            strokeColor: "red"
        });             
            
        (function(i, pseudonyme, prenomM, nomM, position){
            // gestion des clics en mode mobile
            if (window.matchMedia("(max-width: 768px)").matches) {
                eventUsr = 'click';
            } else {
                eventUsr = 'mouseover';    
            }
            // affichage en mouseover ou click selon l'appareil 
            google.maps.event.addListener(marqueur, eventUsr, function(){
                reinitFicheVoisins();     // réinitialise les backgrounds div voisins
                reinitMarkers();  // réinitialise les couleurs markers
                var colorRandom = ' #'+(Math.random()*0xFFFFFF<<0).toString(16);
                var voisinSelect = document.querySelector(".voisin" + i);
                voisinSelect.style.background = colorRandom; 
                this.setIcon({
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 16,
                    fillColor: colorRandom,
                    fillOpacity: 0.9,
                    strokeWeight: 0,
                    strokeColor: "red" 
                    
                }); 
            });
             // affichage double click de la fenêtre infowindow du marqueur    
            google.maps.event.addListener(marqueur, 'dblclick', function(){
                this.setOptions({
                    zIndex: 10                
                });    
                infoWindow.close();
                infoWindow.setContent(classPanelSuccess);
                infoWindow.setPosition(position);
                // affiche la fenêtre ferme la précédente
                infoWindow.open(myMap, this);
                    if (typeof( window.infoopened ) !== 'undefined') 
                       infoopened.close();
                        infoWindow.open(myMap, this);
                        infoopened = infoWindow;
                        
             });
             
        })(i);       
        
        markers.push(marqueur); //sauvegarde de l'objet marqueur dans tableau des markers'
        return marqueur;  // récupération du marqueur pour le hover du tableau voisins
 } 
 // fin fonction creation marker
 
// pavé de retour message de modification de la zone contact 
var close = document.getElementsByClassName("closebtn");
var ii;
for (ii = 0; ii < close.length; ii++) {
    close[ii].onclick = function(){
        var div = this.parentElement;
        div.style.opacity = "0";
        setTimeout(function(){ div.style.display = "none"; }, 600);
        supprimeZone();
        getCoordonnees();
    };
}

function recupAdresseUrl() {
    
    if (document.location.href === "http://jevisla.prope-me.com/localisation") {
       adresseUrl = "http://jevisla.prope-me.com/" ;
    } else {
       adresseUrl = "http://localhost/jevisla/web/" ; 
    }    
}

// affichage bulle d'aide imput saisie adresse écran localisation
function aideUser() {
var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
}   

// affiche bulle d'aide bouton zonecontact
function aideUserZone() {
var myZone = document.getElementById("myZone");
    myZone.classList.toggle("show");
}

//
// affiche bulle d'aide bouton zonecontact
function infoRegion() {
var myZone = document.getElementById("myZone");
    myZone.classList.toggle("show");
}

// var x = location.pathname /jevisl%C3%A0/web/app_dev.php/evenementsCulturels

//______ extraction de la page active pour test  
 
var pageLoad = location.pathname; 
var posiL = (pageLoad.lastIndexOf("localisation")); 
var posiEC = (pageLoad.lastIndexOf("evenementsCulturels"));
//var long = pageLoad.length;
var finPageEC = posiEC + 19;
var pageActiveEC = pageLoad.slice(posiEC, finPageEC);

var finPageL = posiL + 12;
var pageActiveL = pageLoad.slice(posiL, finPageL);

 
if (pageActiveEC === 'evenementsCulturels' ) {
    slider = document.getElementById("myRange");
    output = document.getElementById("radius");
    output.innerHTML = slider.value; // Display the default slider value
   // Update the current slider value (each time you drag the slider handle)
    slider.oninput = function() {
        output.innerText = this.value;
        $('#radius').val(this.value);
    };  
    window.onload = initMapCulturel;
} 

if (pageActiveL === 'localisation' ) {
    window.onload = initMap;
}

//________________ fin page active


$('.okSelect').on('click', function(){
lat = parseFloat($(this).data('lat')); 
lng = parseFloat($(this).data('lng')); 
if (!isNaN(lat) && !isNaN(lng)) {
    geoCodageAdress(lat, lng, mapCulturel);
}
});


/*$('#recupCommune').click(function () {
  var btn = $(this);
  $(btn).buttonLoader('start');
  setTimeout(function () {
    $(btn).buttonLoader('stop');
  }, 5000);
});*/ 
 
