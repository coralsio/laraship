/**
 * init elements on page loading and ajax complete
 */
function initThemeElements() {


    $('[data-toggle="tooltip"]').tooltip();

    $('td .item-actions').addClass('pull-left');

    $('.select2-normal').select2({
        allowClear: true,
    });

    $('.select2-normal.tags').select2({
        tags: []
    });


    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "preventDuplicates": false,
        "positionClass": "toast-bottom-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}



function set_menu_classes() {
    var items = $(".sidebar-menu").find('.active');

    items.each(function (i) {
        var item = $(this);
        item.closest('li').addClass('active');
        item.closest('.treeview').addClass('menu-open');
        item.closest('.treeview-menu').css('display', 'block');
    });
}

function themeConfirmation(title, text, type, confirm_btn, cancel_btn, callback, dismiss_callback) {
    swal({
        title: title,
        text: text,
        type: type,
        showCancelButton: true,
        animation: true,
        // customClass: 'animated tada',
        confirmButtonColor: "#ff7014",
        confirmButtonText: confirm_btn,
        cancelButtonText: cancel_btn
    }).then(
        function () {
            if (typeof callback === "function") {
                // Call it, since we have confirmed it is callable​
                callback();
            }
        }, function (dismiss) {
            if (window.Ladda) {
                Ladda.stopAll();
            }
            if (typeof dismiss_callback === "function") {
                // Call it, since we have confirmed it is callable​

                dismiss_callback()
            }
        });
}

function themeNotify(data) {

    if (undefined == data.level && undefined == data.message) {

        if (undefined != data.responseJSON) {
            data = data.responseJSON;
        }

        var level = 'error';
        var message = data.message;
        var errors = data.errors;

        if (undefined == errors && undefined == message) {
            return;
        }
    } else {
        var level = data.level;
        var message = data.message;
    }

    if (undefined != errors) {
        message += "<br>";
        $.each(errors, function (key, val) {
            message += val + "<br>";
        });
    }
    if (undefined == level && undefined == message) {
        level = 'error';
        message = 'Something went wrong!!';
    }
    if (level === 'error') {
        level = 'Error';
    }

    if (level === 'success') {
        level = 'Success';
    }

    $('body').xmalert({
        x: 'right',
        y: 'top',
        xOffset: 30,
        yOffset: 30,
        alertSpacing: 50,
        lifetime: 6000,
        fadeDelay: 0.3,
        template: 'message' + level,
        title: message,
    });
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
