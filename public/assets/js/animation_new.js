function animateBadge() {
    $('#newBadge').animate({ 
        top: '55%' 
    }, 500).animate({
        top: '50%'
    }, 500, animateBadge);
}

animateBadge();
