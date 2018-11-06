/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 // récupération des coordonnées des voisins autorisées de la zone contact utilisateurs
var connexion ;
var _connectionStatus = document.getElementById('ws-connection-status'); 
var activRecept = document.getElementById('receptionMess');
var infoConnexion = document.querySelector('.infoConnexion');
var stopping;

// configuration du wyswyg CKeditor de conversation
CKEDITOR.replace('editor5',
{
    toolbarLocation : 'bottom',    
    language: 'fr',
    uiColor: '#428bca',
    height: '100px',
    colorButton_colors : 'CF5D4E,454545,FFF,CCC,DDD,CCEAEE,66AB16',
    colorButton_enableAutomatic : false, 
    toolbar: [
                { name: 'insert', items: [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
                { name: 'document', items: [ 'Print' ] },
                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'links', items: [ 'Link', 'Unlink' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks','-','About' ] },
                { name: 'editing', items: [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] }
	     ] 
}); 
// envoi et traitement du dernier message de l'user 
$('#messageVoisin').on('click', function(){
    converse = document.getElementById('converse');
    idOne = converse.getAttribute('data-idOne');
    idTwo = converse.getAttribute('data-idTwo');
    var data = CKEDITOR.instances.editor5.getData();
    CKEDITOR.instances.editor5.setData('');
        $.ajax({
            url : Routing.generate('jevisla_messagerie_message'), // on appelle le script JSON
            data: { "message" : data, "idOne" : idOne, "idTwo" : idTwo }, 
            success: function(donnee){
                integrationMessage(JSON.parse(donnee));
            }
        });
// intégration visuelle après enregistrement dans la base du dernier message de l'utilisateur     
    function integrationMessage (donnee) {
        var areaMessages = $('#conversation').val();
        divText = document.getElementById('conversation');
        $("#conversation").children().removeClass("newMessage");
        var newMessage = document.createElement("div");
        newMessage.setAttribute("class", "lastMessage"); 
        newMessage.setAttribute("data-datetime", donnee.date_time); 
        var contentMessage = document.createElement("div");
        var spanNewMessage = document.createElement("span");
        var dateMessage = document.createElement("span");
        dateMessage.classList.add("pull-right");
        dateMessage.style.fontSize = '9px';
        dateMessage.innerHTML = ("Le " + donnee.date);
        spanNewMessage.classList.add("pull-left");
        spanNewMessage.innerHTML = (areaMessages + donnee.donnee);
        contentMessage.append(spanNewMessage);
        contentMessage.append(dateMessage);
        contentMessage.classList.add("messagerieVoisinId", "col-sm-7", "col-xs-7", "col-md-7", "newMessage");
        newMessage.append(contentMessage);
        divText.append(newMessage);
        // on ajuste l'affichage de la scrollbar dernier message visible
        var readLastMessages = document.getElementById('conversation');
        readLastMessages.scrollTop = readLastMessages.scrollHeight; 
        JouerSon();
    }
});
// indicateur Online ou Offline et moteur de recherche des derniers messages  
function receiveMessage(){
    if ($('#ws-connection-status').hasClass('active')) {
        clearInterval(connexion); 
        activRecept.innerHTML = ('Offline');
        $("#receptionMess").removeClass("label-success");
        $("#receptionMess").addClass("label-danger");
        _connectionStatus.classList.remove('active');
        infoConnexion.innerHTML = "Lancer la réception des messages >>";
    } else { 
        infoConnexion.innerHTML = "Couper la réception des messages >>";
        $("#receptionMess").removeClass("label-danger");
        $("#receptionMess").addClass("label-success");
        activRecept.innerHTML = ('Online');
        _connectionStatus.classList.add('active');
        connexion = setInterval(function(){ 
            converse = document.getElementById('converse');
            idOne = converse.getAttribute('data-idOne');
            idTwo = converse.getAttribute('data-idTwo');
            converse = converse.getAttribute('data-converse');
            date_time = $(".lastMessageVoisin:last").attr('data-datetime');
                $.ajax({
                    url : Routing.generate('jevisla_messagerie_last_message'), // on appelle le script JSON
                    data: { "converse" : converse, "idTwo" : idTwo, "datetime" : date_time }, 
                    success: function(donnee){
                        integrationMessageVoisin(JSON.parse(donnee));
                    }
                });

        }, 15000);
    }
}
// récupération de la liste des derniers messages du voisin
function integrationMessageVoisin (donnee) {
    if (Array.isArray(donnee)){
        for (var i = 0; i < donnee.length; i++) {    
            var areaMessages = $('#conversation').val();
            divText = document.getElementById('conversation');
            $("#conversation").children().removeClass("newMessage");
            var newMessage = document.createElement("div");
            newMessage.setAttribute("class", "lastMessageVoisin"); 
            var dateAffich = donnee[i].dateCreation.date.substring(0,19); 
            var yy = dateAffich.substring(0,4);
            var mm = dateAffich.substring(5,7);
            var dd = dateAffich.substring(8,10);
            var hhmmss = dateAffich.substring(11,19);
            var dateAffiche = dd + '-' + mm + '-' + yy + ' à ' + hhmmss;
            newMessage.setAttribute("data-datetime", dateAffich); 
            var contentMessage = document.createElement("div");
            var spanNewMessage = document.createElement("span");
            var dateMessage = document.createElement("span");
            dateMessage.classList.add("pull-right");
            dateMessage.style.fontSize = '9px';
            dateMessage.innerHTML = ("Le " + dateAffiche);
            spanNewMessage.classList.add("pull-left");
            spanNewMessage.innerHTML = (areaMessages + donnee[i].message);
            contentMessage.append(spanNewMessage);
            contentMessage.append(dateMessage);
            contentMessage.classList.add("messagerieVoisinVoisin", "col-md-offset-5", "col-md-7", "col-sm-offset-5", "col-sm-7", "col-xs-offset-5", "col-xs-7", "newMessage");
            newMessage.append(contentMessage);
            divText.append(newMessage);
            // on ajuste l'affichage de la scrollbar dernier message visible
            var readLastMessages = document.getElementById('conversation');
            readLastMessages.scrollTop = readLastMessages.scrollHeight; 
            JouerSon();
        } 
    }
} 

function JouerSon() {
    var sound = document.getElementById("beep");
    sound.play();
}

