$(function(){
    var anchura = $(this).width();
    $('#menuHamburguesa').click(function(){
        $('#nav').toggle();
    });
    $(window).resize(function(){
        
        if(anchura > 767){
            $('#nav').show();
        }else {
            $('#nav').hide();
        }
    });
    if(anchura > 992){
    // funcion para submenu del navegador
    $('.dropdown').hover(function() {
        $(this).find('.submenu').stop(true, true).slideDown(200);
      }, function() {
        $(this).find('.submenu').stop(true, true).slideUp(200);
      });
    }
});
