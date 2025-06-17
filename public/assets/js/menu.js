$(function(){
    var anchura = $(this).width();
    $('#menuHamburguesa').click(function(){
        $('#nav, #nav_proveedor').toggle();
    });
    $(window).resize(function(){
        var anchura = $(window).width();
        if(anchura > 576){
            $('#nav, #nav_proveedor').show();
        }else {
            $('#nav, #nav_proveedor').hide();
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
