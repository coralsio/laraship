/**
 * init elements on page loading and ajax complete
 */
function initThemeElements() {
    $('.select2-normal').select2({
        allowClear: true,
    });

    $('.select2-normal.tags').select2({
        tags: []
    });

    $("#sidebarnav .collapse").perfectScrollbar();

    setFloatingLabels();

    $('.nav-item').removeClass('active');

}

function setFloatingLabels() {

    $('form').addClass('floating-labels');
    $(".floating-labels input[type=text]:not('#authy-countries'),.floating-labels input[type=email],.floating-labels input[type=password],.floating-labels input[type=passowrd],.floating-labels input[type=email],.floating-labels select:not('.select2-normal,.select2-ajax,.select2-tags,.select2-tree'),.floating-labels textarea:not('.ckeditor,.ckeditor-simple'),.floating-labels input[type=number]")
        .each(function () {
            var item = $(this);
            item.attr('placeholder', '');
            item.find("option[value='']").text('')
            item.insertBefore(item.prev('label'));
        });
    $(".floating-labels .form-control").on("focus blur", function (e) {
        $(this).parents(".form-group").toggleClass("focused", "focus" === e.type || this.value.length > 0)
    }).trigger("blur")
}

function themeConfirmation(title, text, type, confirm_btn, cancel_btn, callback, dismiss_callback) {
    swal({
        title: title,
        text: text,
        type: type,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
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

function themeBroadCast(eventName, payload) {
    $('.nav-item .notify').closest('.nav-link')
        .html(`<i class="fa fa-fw fa-bell"></i>${payload.unread_notifications_count}
                <div class="notify"><span class="heartbit"></span>
                <span class="point"></span></div>`);
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

    $.toast({
        text: message,
        position: 'bottom-right',
        loaderBg: '#ff6849',
        icon: level,
        hideAfter: 5000,
        stack: 6
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
