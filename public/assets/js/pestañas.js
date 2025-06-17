$(document).ready(function () {
  // console.log('pestañas cargado');
  $(".pestaña").on("click", function () {
    $(".pestaña").removeClass("active");
    $(".tab-content").removeClass("active");

    $(this).addClass("active");
    const tabId = $(this).data("tab");
    $(`#tab-${tabId}`).addClass("active");
  });
});
