// load modal to page
let url = '/utilities/content-consent-settings/modal';
if (window.base_url) {
    url = window.base_url + url;
}

$.get(url, function (data) {
    $('body').append(data);

    $("#content-consent-modal").modal('show');
});
