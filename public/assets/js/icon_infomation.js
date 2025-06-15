//Para abrir la modal cuando se hace clic en el icono de información
$('.informacion a').on('click', function(event) {
    event.preventDefault();
    $('#infoModal').fadeIn();
});
//Para cerrar la modal cuando se hace clic en la X
$('.close').on('click', function() {
    $('#infoModal').fadeOut();
});

//Para cerrar la modal si se hace clic fuera del contenido
$(window).on('click', function(event) {
    if ($(event.target).is('#infoModal')) {
        $('#infoModal').fadeOut();
    }
});