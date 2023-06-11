/**
 * init elements on page loading and ajax complete
 */
function initThemeElements() {
    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        }
    });
    if(window.grecaptcha){
        grecaptcha.reset();
    }
}

function themeConfirmation(title, text, type, confirm_btn, cancel_btn, callback, dismiss_callback) {
    var txt;
    var r = confirm(text);

    if (r === true) {
        if (typeof callback === "function") {
            // Call it, since we have confirmed it is callable​
            callback();
        }
    } else {
        if (typeof dismiss_callback === "function") {
            // Call it, since we have confirmed it is callable​
            dismiss_callback()
        }
    }
}

function themeNotify(data) {

    if (undefined == data.level && undefined == data.message) {

        if (undefined != data.responseJSON) {
            data = data.responseJSON;
        }

        var level = 'error';
        var message = data.message;
        var errors = data.errors;
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

    toastr[level](message);
}