console.log("se carga animacion")
function animateBadge() {
    $('#newBadge').animate({ 
        top: '49%' 
    }, 500).animate({
        top: '55%'
    }, 500, animateBadge);
}

animateBadge();
