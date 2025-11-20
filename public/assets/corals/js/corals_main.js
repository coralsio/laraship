"use strict";
$(document).ajaxComplete(function (event, xhr, settings) {
    if (IsJsonString(xhr.responseText)) {
        var response = JSON.parse(xhr.responseText);
        if (response.notification) {
            themeNotify(response.notification);
        }
    }

    initElements(true);
});

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

$(document).ajaxStart(function (event) {
    var panelEl = $(".box-body");
    if (panelEl.closest('.box').hasClass('no-block-ui')) {
        return false;
    }

    if (panelEl.length === 0 && !$('body').hasClass('no-block-ui')) {
        panelEl = $('body');
    }
    if (panelEl.length) {
        blockUI(panelEl);
    }
});

$(document).ajaxStop(function () {
    var panelEl = $(".box-body");

    if (panelEl.length === 0) {
        panelEl = $('body');
    }

    unblockUI(panelEl);

    if (window.Ladda) {
        Ladda.stopAll();
    }
});

$(document).ready(function () {
    initSelect2ajax();
    initElements();
});

$('[data-action]').click(function (event) {
    event.preventDefault();
});

$('body').on('click', '[data-action]', function (e) {
    e.preventDefault();

    var $element = $(this);

    var action = $element.data('action');
    var requestData = $element.data('request_data');
    var confirmation_message = $element.data('confirmation');

    if (undefined === requestData) {
        requestData = {};
    }
    var url = $element.prop('href');

    var page_action = $element.data('page_action');
    var action_data = $element.data('action_data');

    var table = $element.data('table');

    if (action === 'delete') {

        themeConfirmation(
            corals.confirmation.title,
            corals.confirmation.delete.text,
            'warning',
            corals.confirmation.delete.yes,
            corals.confirmation.cancel,
            function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        _method: 'delete'
                    },
                    success: function (response, textStatus, jqXHR) {
                        handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, action_data, table);
                    },
                    error: function (data, textStatus, jqXHR) {
                        themeNotify(data);
                    }
                });
            });

        return;
    }

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

    if (action === 'load') {
        var load_to = $element.data('load_to');
        $(load_to).load(url);
    }

    if (action === 'post' || action === 'get') {
        if (undefined !== confirmation_message) {
            themeConfirmation(
                corals.confirmation.title,
                confirmation_message,
                'info',
                corals.confirmation.yes,
                corals.confirmation.cancel, function () {
                    ajaxRequest(url, requestData, table, page_action, action);
                });
        } else {
            ajaxRequest(url, requestData, table, page_action, action);
        }
    }
});

$('body').on('submit', '.ajax-form', function (event) {
    event.preventDefault();

    let $form = $(this);

    ajax_form($form);
});

/*
* Select2 dependency handler
* The following attributes must be added to the main select:
*
* 'class'=>'dependent-select'
* 'data-dependency-field'=>'field_id',// the target element Id
* 'data-dependency-args'=>'arg1_id,arg2_id'//any additional fields that their values are required to get the data
* 'data-dependency-ajax-url'=>url('') //ajax url that handles the dependency
* */
$(document).on('change', '.dependent-select', function () {
    var thisVal = $(this).val();
    var name = $(this).prop('name');
    var dependencyArgs = [];

    if ($(this).data('dependency-args')) {
        dependencyArgs = $(this).data('dependency-args').split(',');
    }
    var dependencyFieldId = $(this).data('dependency-field');

    if ($("#" + dependencyFieldId).length === 0) {
        return;
    }

    if (!thisVal) {
        return;
    }

    var ajaxParams = name + "=" + thisVal + "&";

    $.each(dependencyArgs, function (index, arg) {
        let argValue = $('#' + arg).val();
        ajaxParams += arg + "=" + argValue + "&";
    });

    var targetUrl = $(this).data('dependency-ajax-url');
    var ajaxUrl = targetUrl + "?" + ajaxParams;

    $.ajax(ajaxUrl,   // request url
        {
            success: function (data, status, xhr) {// success callback function
                var targetElementData = [];

                targetElementData.push({'id': '', 'text': ''});

                $.each(data, function (index) {
                    targetElementData.push({'id': index, 'text': data[index]});
                });

                $("#" + dependencyFieldId).select2().empty().select2({
                    data: targetElementData
                });

                let selectedValue = $("#" + dependencyFieldId).data('selected_value');

                if (selectedValue) {
                    $("#" + dependencyFieldId).val(selectedValue).trigger('change');
                }
            }
        });
});

$(document).on('change blur keyup keypress mouseup', '.limited-text', function (event) {
    var value = $(this).val();

    var limit = $(this).prop('maxlength');

    if (value.length == limit) {
        event.preventDefault();
    } else if (value.length > limit) {
        // Maximum exceeded
        value = value.substring(0, limit);
        $(this).val(value);
    }

    $(this).parent().find(".limit-counter").text(value.length);
});

$(document).on('click', '.form_language_switcher .btn:not(.current-lang)', function () {
    let langSwitcher = $(this);

    let form = langSwitcher.closest('form');

    let langCodeSelected = langSwitcher.data('lang_code');

    let model = langSwitcher.data('model');
    let hashed_id = langSwitcher.data('hashed_id');

    if (!hashed_id.length) {
        return;
    }

    let activeLangCode = langSwitcher.closest('form').find('.translation_language_code');

    let action_data = {
        langCodeSelected: langCodeSelected,
        activeLangCode: activeLangCode,
        model: model,
        hashed_id: hashed_id
    };

    if (activeLangCode.val() !== langCodeSelected && isFormDirty(form)) {
        themeConfirmation(
            corals.confirmation.title,
            corals.confirmation.confirm_dirty_form,
            'warning',
            corals.confirmation.yes,
            corals.confirmation.skip_continue, function () {
                let old_action_data = form.data('action_data');
                let old_page_action = form.data('page_action');

                form.data('action_data', action_data);
                form.data('page_action', 'switchFormFieldsTranslation');
                form.data('old_action_data', old_action_data);
                form.data('old_page_action', old_page_action);

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'translation_submit',
                    value: true
                }));

                let submitStatus = ajax_form(form);
            }, function () {
                switchFormFieldsTranslation({}, form, action_data);
            });
    } else {
        switchFormFieldsTranslation({}, form, action_data);
    }
});


$('.nav-tabs a').on('shown.bs.tab', function (event) {
    let tab = $(event.target);

    let tabContentSelector = tab.attr('href');

    let url = tab.data('content_url');

    let loaded = tab.data('content_loaded');

    if (loaded || !url) {
        return false;
    }

    $.get(url, {tab_html: true}, function (data, textStatus, jqXHR) {
        $(tabContentSelector).html(data);
        tab.data('content_loaded', true);
    });
});

$(document).on('click', 'a.laddaBtn', function (e) {
    $(this).addClass('disabled');
});

$(document).on('click', 'a.disabled', function (e) {
    e.preventDefault();
    e.stopPropagation();
    return false;
});

function getCookie(name, parseJson = false) {
    let cookies = "; " + document.cookie;
    let parts = cookies.split("; " + name + "=");

    if (parts.length === 2) {
        let value = decodeURIComponent(parts.pop().split(";").shift());
        return parseJson ? JSON.parse(value) : value;
    }
}

function setCookie(name, value, minutes) {
    let date = new Date();
    date.setTime(date.getTime() + (minutes * 60 * 1000));
    let expiresAt = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expiresAt + ";path=/";
}



$(document).ready(function () {

    let ignorePredefinedChangeEvent = false;

    $('.preDefinedDateOption').on('change', function (e) {
        let predefinedDates = corals.predefinedDates;

        if (ignorePredefinedChangeEvent) {
            ignorePredefinedChangeEvent = false;
            return;
        }

        let predefinedDateConfig = predefinedDates[$(this).val()];

        let startDate = predefinedDateConfig['start_date'];
        let endDate = predefinedDateConfig['end_date'];

        if (typeof $(this).attr('monthly') !== 'undefined') {
            startDate = formatToYearMonth(startDate);
            endDate = formatToYearMonth(endDate);
        }

        $('[name$="[from]"]').val(startDate);
        $('[name$="[to]"]').val(endDate);
    });

    $('[name$="[from]"]').on('change', function () {
        ignorePredefinedChangeEvent = true;
        $('.preDefinedDateOption').val('custom').trigger('change');
    });

    $('[name$="[to]"]').on('change', function () {
        ignorePredefinedChangeEvent = true;
        $('.preDefinedDateOption').val('custom');
    });

    function formatToYearMonth(dateString) {
        let date = new Date(dateString);
        let year = date.getFullYear();
        let month = (date.getMonth() + 1).toString().padStart(2, '0');
        return `${year}-${month}`;
    }

    function addOneDay(dateString) {
        let date = new Date(dateString);
        date.setDate(date.getDate() + 1);
        return date.toISOString().split('T')[0];
    }
});

$(document).ready(function (){

    let isDirty = false;

    $('.confirmation_dirty_leave_form').on('change input', ':input', function () {
        isDirty = true;
    });

    $('.confirmation_dirty_leave_form').on('submit', function () {
        isDirty = false;
    });

    $('a').on('click', function (e) {
        if (isDirty) {
            e.preventDefault();
            themeConfirmation(
                corals.confirmation.title,
                "You have unsaved changes. Do you want to discard them and leave?",
                'warning',
                corals.confirmation.yes,
                corals.confirmation.cancel,
                function () {
                    isDirty = false;
                    window.location.href = $(e.target).attr('href');
                },
                function () {
                }
            );
        }
    });
});