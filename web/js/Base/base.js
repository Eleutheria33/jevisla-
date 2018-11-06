
/* global hash */

// Scrollspy fluide
 
  $('#tete .pageScroll').on('click', function(e) {
    // remove all items all class  
        var liS = document.querySelectorAll('#tete li');
        for (let i = 0; i < liS.length; i++) {
            liS[i].classList.remove('active');
        }  
    //this.classList.add("active");
    this.offsetParent.classList.add("active");
     
    var hash = this.hash;
    
    $('html, body').animate({
        scrollTop: $(this.hash).offset().top
    }, 1000, function(){
        window.location.hash = hash;
    });
     
  });

// on referme le menu en responsive à chaque clic  
 $('#tete li a').click(function (){
    if ($("#tete .navbar-header button").is(":visible") ){
        $('.navbar-toggle').click(); 
    }
});

// ajuste l'écran après affichage de l'entête
window.onload = ajustScreen();

function ajustScreen() {
    if ($(".header").is(":visible") ){
        setTimeout(function(){
            var headHeight = document.querySelector(".header").getBoundingClientRect().height;
            var top =  headHeight;
            $('html, body').animate({
                scrollTop:  top
            }, 3000);
        }, 10);
    }
}
 
// action menu accueil 

window.onscroll = function(e) {
    // mouvement de la carte événementsCulturels 
    
        if (document.querySelector("#mapCulturel")) {
            
            var searchMap = document.querySelector("#moveSearch").getBoundingClientRect();
            var pdp = document.querySelector(".piedDePage").getBoundingClientRect();   
            var head = document.querySelector(".header").getBoundingClientRect();
            var testTop = document.getElementById("moveMap"); 
            var posmapCulturel = $('#moveMap').offset();
            var sizePage = document.body.offsetHeight;
            var rect =  testTop.getBoundingClientRect(); 
            var limitH = head.height + searchMap.height + 150;  // 150 --> classe slider
            var limitB = sizePage - pdp.height;

            if (window.matchMedia("(min-width: 992px)").matches) {
                
                if(window.pageYOffset >= limitH || window.pageYOffset <= limitB) {
                    posmapCulturel.top = window.pageYOffset + 100 ;
                    $('#moveMap').offset(posmapCulturel);
                }
                
                if (window.pageYOffset <= limitH ) {
                    posmapCulturel.top = (limitH + 50);
                    $('#moveMap').offset(posmapCulturel);
                } 
                
                if (window.pageYOffset >= limitB ) {
                    posmapCulturel.top = (limitB - 100);
                    $('#moveMap').offset(posmapCulturel);
                }
            } 
            if (window.matchMedia("(min-width: 768px) and (max-width: 992px)").matches) {
                
                if(window.pageYOffset >= limitH || window.pageYOffset <= limitB) {
                    posmapCulturel.top = window.pageYOffset + 100 ;
                    $('#moveMap').offset(posmapCulturel);
                }
                
                if (window.pageYOffset <= limitH ) {
                    posmapCulturel.top = (limitH + 150);
                    $('#moveMap').offset(posmapCulturel);
                } 
                
                if (window.pageYOffset >= limitB ) {
                    posmapCulturel.top = (limitB - 100);
                    $('#moveMap').offset(posmapCulturel);
                }
            } 
        }
    
    e.preventDefault();
    myFunction();
};


function myFunction() {
    
    var navbar = document.getElementById("navbar");
    var jvlMenu = document.getElementById("jvlMenu"); 
    var dropbtn = document.querySelector(".dropbtn"); 
    var header = document.querySelector(".header"); // on prend la mesure de la zone top texte

   
    if (window.pageYOffset >= header.offsetHeight) {
        navbar.classList.add("navbar-fixed-top");
        if(sessionStorage.getItem('id')) {
            jvlMenu ? jvlMenu.style.display = "block": "" ;
            dropbtn ? dropbtn.style.display = "block": "";
        }
    } else {
        navbar.classList.remove("navbar-fixed-top");
        if(sessionStorage.getItem('id')) {
            jvlMenu ? jvlMenu.style.display = "none": "" ;
            dropbtn ? dropbtn.style.display = "none": "";
        }    
    }
}


$(".reponse:nth-child(n)").click(function(){ 
    if (!$(this).hasClass("collapsed")) {
        $($(this).attr("href")).css('background-image', 'radial-gradient(circle at center, white, #0c84b2)');
        $(this).css('background-image', 'radial-gradient(circle at center, white, #0c84b2)'); 
        $(this).toggleClass('radial-gradient(circle at center, white, #0c84b2)');
    } else {
        $("#accordion .reponse").css('background-image', 'radial-gradient(circle at center, white, #0c84b2)');
        var color = ' #'+(Math.random()*0xFFFFFF<<0).toString(16);
        $($(this).attr("href")).css('background-image', 'radial-gradient(circle at center, white,' + color +')');
        $(this).css('background-image', 'radial-gradient(circle at center, white,' + color +')');
        $(this).toggleClass(color);
    }
});
// suppression par clic du message de confirmation d'envoi mail contact
function deleteFlash(){
 setTimeout(function(){
  $('#flashMessage').css('display', 'none')   
 }, 100);   
}

function formulaireContact(){
    var email = $('.mailContact').val();
    var content = CKEDITOR.instances.editor6.getData();
    if (content !== '') {
        $.ajax({
            url : Routing.generate('jevisla_general_sendContact'), // on appelle le script JSON
            data: { "message" : content, "email" : email }, 
            success: function(donnee){
                
                var answer = JSON.parse(donnee);
                afficheMessage(answer.reponse);
            }
        });  
    } else {
         
        afficheMessage('no');  
    }    
}

function afficheMessage(donnee){
    if (donnee === 'yes' ) {
            if($('#flashMessage').hasClass('panel-warning')){
                $('#flashMessage').removeClass('panel-warning');
                $('#flashMessage').addClass('panel-success');
            }    
        document.querySelector('.messageFlash').innerHTML = 'Votre message a été envoyé avec succès !!'; 
        $('.mailContact').val(null); 
    }else{
        $('#flashMessage').removeClass('panel-success');     
        $('#flashMessage').addClass('panel-warning');    
        document.querySelector('.messageFlash').innerHTML = 'Votre message est vide !!';    
    }
    $('#flashMessage').css('display','block'); 
    setTimeout(function(){
        $('#flashMessage').css('display','none'); 
    }, 4000);
    CKEDITOR.instances.editor6.setData('');    
} 

// récupération coordonnées map à l'inscription 
function calculLatLng(choix) { 
      var adress;  
        if (choix === "1") // choix représente l'origine de la demande modification ou création profil 1:création profil 2: modification
        {  // préparation adresse pour inscription profil
            adress = $('#fos_user_registration_form_numeroAd').val();  
            adress += $('#fos_user_registration_form_voieAd').val(); 
            adress += $('#fos_user_registration_form_nomVoieAd').val();  
            adress += $('#fos_user_registration_form_villeAd').val();   
            adress += $('#fos_user_registration_form_codePostal').val(); 
        } else { // préparation adresse pour modification profil
            adress = $('#fos_user_profile_form_numeroAd').val();  
            adress += $('#fos_user_profile_form_voieAd').val(); 
            adress += $('#fos_user_profile_form_nomVoieAd').val();  
            adress += $('#fos_user_profile_form_villeAd').val();   
            adress += $('#fos_user_profile_form_codePostal').val(); 
        }
        
	newAdresse = adress;
	geocoder = new google.maps.Geocoder();
        
    	    geocoder.geocode( { 'address': newAdresse}, function(results, status) {
		      if (status === 'OK') {
                       if (choix === "1")
                            {  // affichage adresse pour inscription profil
                                $("#fos_user_registration_form_latitude").val(results[0].geometry.location.lat());
                        	$("#fos_user_registration_form_longitude").val(results[0].geometry.location.lng());
                            } else { // affichage adresse pour modification profil
                                $("#fos_user_profile_form_latitude").val(results[0].geometry.location.lat());
                                $("#fos_user_profile_form_longitude").val(results[0].geometry.location.lng());
                            }
		      	
		      } else {
		        alert('Geocode was not successful for the following reason: ' + status);
		      }
	    });
 
}

$("#photoUserVoisin:nth-child(n)").click(function(){ 
    var adresseImgVoisin = document.getElementById('imageUserVoisin');
    adresseImgVoisin.src = this.src;
    var modal = document.getElementById('photoUserShow'); 
    var caption = document.getElementById('caption');
    caption.innerHTML = "<span style='font-size:30px; font-weight:bolder;'>" + $(this).attr("data-idVoisin") + '</span>';

    modal.style.display = "block";
 });  
 
$('#photoUserShow .close').click(function(){
    var modal = document.getElementById('photoUserShow');
    modal.style.display = "none";
});         
         