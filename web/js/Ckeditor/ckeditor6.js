/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// cuztomisation du ckeditor de la page d'accueil
CKEDITOR.replace('editor6',
{   type: 'textarea',
    toolbarLocation : 'top',    
    language: 'fr',
    uiColor: '#5bc0de',
    height: '100px',
    background:  '#0c84b2',  
    width: '100%',
    toolbar: [
                { name: 'insert', items: [ 'Image','Smiley','SpecialChar' ] },
                { name: 'document', items: [ 'Print' ] },
                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline' ] },
                { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'links', items: [ 'Link' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', 'Blockquote' ] },
                { name: 'tools', items: [ 'Maximize' ] },
                { name: 'styles', items: [ 'Format', 'FontSize' ] }
	     ]
           
});     

