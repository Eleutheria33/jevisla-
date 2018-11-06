/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
  $("#afficher").click(function() {
         $("#afficher").hide();
         $(".alert").show("slow");
       }); 
       $(".close").click(function() {
         $(".alert").hide("slow");
         $("#afficher").show();
       }); 
});
      // Scrollspy fluide
      
$(document).ready(function(){
        $('#tete a').on('click', function(e) {
          e.preventDefault();
          var hash = this.hash;
          $('html, body').animate({
            scrollTop: $(this.hash).offset().top
          }, 1000, function(){
            window.location.hash = hash;
          });
        });
      // Scrollspy fluide
        $('#tete button').on('click', function(e) {
          e.preventDefault();
          var hash = this.hash;
          $('html, body').animate({
            scrollTop: $(this.hash).offset().top
          }, 1000, function(){
            window.location.hash = hash;
          });
        });
});    
