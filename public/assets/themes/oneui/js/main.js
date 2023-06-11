"use strict";

$(document).ajaxStart(function () {
    Pace.restart();
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


let url = window.base_url + '/orders/search';

$(".orders-search-bar-select2").select2({
    ajax: {
        url: url,
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term, // search term
                page: params.page
            };
        },
        processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;

            return {
                results: data.items,
                pagination: {
                    more: (params.page * 30) < data.total_count
                }
            };
        },
        cache: true
    },
    placeholder: 'Search for Orders...',
    minimumInputLength: 4,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection
});

function formatRepo(repo) {
    if (repo.loading) {
        return repo.text;
    }

    return $(`<div class='clearfix'>${repo.code}</div>`);

}

function formatRepoSelection(repo) {
    return repo.full_name || repo.text;
}