$(document).ready(function () {
    initThemeElements();
});

$('body').on('click', '[data-action]', function (e) {
    e.preventDefault();

    var $element = $(this);

    var action = $element.data('action');
    var url = $element.prop('href');

    if (action === 'logout') {
        $.ajax({
            url: url,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
            },
            error: function (data, textStatus, jqXHR) {
            },
            complete: function (data) {
                window.location = window.base_url;
            }
        });
    }
});

jQuery(function ($) {
    // accordian
    $('.accordion-toggle').on('click', function () {
        $(this).closest('.panel-group').children().each(function () {
            $(this).find('>.panel-heading').removeClass('active');
        });

        $(this).closest('.panel-heading').toggleClass('active');
    });

    //Initiat WOW JS
    new WOW().init();

    // portfolio filter
    $(window).load(function () {
        'use strict';
        var $portfolio_selectors = $('.portfolio-filter >li>a');
        var $portfolio = $('.portfolio-items');
        $portfolio.isotope({
            itemSelector: '.portfolio-item',
            layoutMode: 'fitRows'
        });

        $portfolio_selectors.on('click', function () {
            $portfolio_selectors.removeClass('active');
            $(this).addClass('active');
            var selector = $(this).attr('data-filter');
            $portfolio.isotope({filter: selector});
            return false;
        });
    });


    //Pretty Photo
    $("a[rel^='prettyPhoto']").prettyPhoto({
        social_tools: false
    });
});