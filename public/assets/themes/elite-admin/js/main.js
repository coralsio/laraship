$(document).ajaxStart(function () {
    $(".preloader").fadeIn();
});

$(document).ajaxComplete(function () {
    $(".preloader").fadeOut();
    initThemeElements();
});

$(document).ready(function () {
    initThemeElements();
});