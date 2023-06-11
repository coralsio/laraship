"use strict";

$(document).ajaxStart(function () {
    // Pace.restart();
});

$(document).ajaxComplete(function () {
    initThemeElements();
});

$(document).ready(function () {
    if ($('.sidebar-menu').length) {
        $('.sidebar-menu').tree();
    }

    set_menu_classes();

    initThemeElements();
});
