"use strict";

function initElements(isAjax) {
    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        }
    });

    if ($.fn.iconpicker) {
        $('.icp-auto').iconpicker({
            animation: false
        });
    }

    $('[data-toggle="popover"]').popover();

    if (window.initFunctions) {
        $.each(window.initFunctions, function (index, element) {
            window[element]();
        });
        window.initFunctions = [];
    }

    if (window.Ladda) {
        Ladda.bind('button[type=submit]');
        Ladda.bind('.laddaBtn');
        Ladda.bind('[data-action]');
    }
    initSelect2Tree(isAjax);
    initCopyToClipBoard(isAjax);
    initLimitedText(isAjax);
    initFormIsDirty(isAjax);
    initFormLangSwitcher(isAjax);
    initializeCoralsDatetimePicker();
    initializeAutoCompleteSearch();
}

function initSelect2Tree(isAjax) {
    if (isAjax) {
        return;
    }
    if ($.fn.select2 && $.fn.select2ToTree) {
        $('.select2-tree').each(function () {
            let element = $(this);
            let options = element.data('options');
            element.select2ToTree({treeData: {dataArr: options}, allowClear: true});
        });
    }
}

function initCkeditor() {
    if (typeof CKEDITOR !== "undefined") {
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['i'] = false;
        CKEDITOR.config.filebrowserBrowseUrl = '/file-manager/ckeditor';

        $('.ckeditor-simple').each(function (e) {
            if (CKEDITOR.instances[$(this).prop('id')]) {
                return;
            }

            CKEDITOR.replace($(this).prop('id'), {
                toolbarGroups: [{name: 'document', groups: ['mode', 'document', 'doctools']}, {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo']
                }, {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']}, {
                    name: 'forms',
                    groups: ['forms']
                }, {name: 'basicstyles', groups: ['basicstyles', 'cleanup']}, {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
                }, {name: 'links', groups: ['links']}, {name: 'insert', groups: ['insert']}, {
                    name: 'styles',
                    groups: ['styles']
                }, {name: 'colors', groups: ['colors']}, '/', '/', {name: 'tools', groups: ['tools']}, {
                    name: 'others',
                    groups: ['others']
                }, {name: 'about', groups: ['about']}],
                removeButtons: 'Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,BidiLtr,BidiRtl,Language,Anchor,Flash,Table,HorizontalRule,Smiley,PageBreak,Iframe,Unlink,Maximize,ShowBlocks,About',
            });
        });
    }
}

function initLimitedText() {
    $('.limited-text').each(function () {
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
}

function initCopyToClipBoard() {
    if ($('.copy-button').length && window.Clipboard) {
        var clipboard = new Clipboard('.copy-button');
        clipboard.on('success', function (e) {
            // e.clearSelection();
            var message = {
                level: 'success', message: 'Copied to clipboard!'
            };
            themeNotify(message);
        });
    }
}


function initTabHash() {
    //init tabs hash
    let hash = window.location.hash;

    if (hash.length) {
        let hasURLParameters = hash.indexOf('?');//-1 if not exist
        let indexOfHash = hash.indexOf('#');

        if (hasURLParameters && hasURLParameters > indexOfHash) {
            hash = _.split(hash, '?')[0];
        }

        let $element = $('ul.nav a[href="' + hash + '"]');

        if ($element.length > 0) {
            $element.tab('show');
            let scroll = $element.offset().top - 150;
            $("html, body").animate({scrollTop: scroll});

        }
    }

    $('.nav-tabs a').on("click", function (e) {
        $(this).tab('show');
        changeHashWithoutScrolling(this.hash);

    });
}

function changeHashWithoutScrolling(hash) {
    var id;
    var elem;

    id = hash.replace(/^.*#/, '');

    if (id) {
        elem = document.getElementById(id);

        if (elem) {
            elem.id = id + '-tmp';
            window.location.hash = hash;
            elem.id = id;
        }
    }
}

function refreshDataTable(table) {
    var $table = $(table);
    if (undefined !== table && $table.length) {
        $table.DataTable().ajax.reload();
    } else {
        site_reload();
    }
}

function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function redirectTo(data) {
    setTimeout(function () {
        window.location.replace(data.url);
    }, 1000);
}

function site_reload() {
    setTimeout(function () {
        location.reload();
    }, 1000);
}


function clearContactForm(response, $form) {

    var form_contact = $('#main-contact-form');

    $('html, body').animate({
        scrollTop: form_contact.offset().top - 100
    }, 1000);

    $form[0].reset();
}


function clearForm(response, $form) {
    $form[0].reset();

    $($form).find('input[type=checkbox]').prop('checked', false);

    if ($.fn.iCheck) {
        $($form).find('input[type=checkbox]').iCheck('update');
    }
}

function closeModal(response, $form) {
    if (!$form) {
        return;
    }
    setTimeout(function () {
        if ($form.closest('.modal ').length) {
            clearForm(response, $form);
            $form.closest('.modal ').modal('hide');
        }
    }, 500);
}


/* Simulate Ajax call on Panel with reload effect */
function blockUI(item) {
    if ($.fn.block) {
        $(item).block({
            message: '<svg class="circular"><circle class="path" cx="40" cy="40" r="10" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>',
            css: {
                border: 'none', width: '14px', backgroundColor: 'none'
            },
            overlayCSS: {
                backgroundColor: '#fff', opacity: 0.6, cursor: 'wait'
            }
        });
    }
}

function unblockUI(item) {
    if ($.fn.unblock) {
        $(item).unblock();
    }
}

function readURL(area, input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (area.find('.preview').length) {
                area.find('.preview').attr('src', e.target.result);
                area.find('.preview').removeClass('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on("change", ".upload-file-area", function () {
    var $area = $(this);
    var $input = $("#" + $area.data('input'));

    if ($area.find(".file-name").length) {
        var fileNameSpan = $area.find(".file-name");

        var names = '';

        for (var i = 0; i < $input[0].files.length; ++i) {
            names += $input[0].files[i].name + ' | ';
        }
        names = _.trim(names, ' | ');

        fileNameSpan.text(names);
    }

    readURL($area, $input[0]);
});

$(document).on('click', '.modal-load ,[data-action="modal-load"]', function (e) {
    e.preventDefault();
    let title = $(this).data('title');
    let size = $(this).data('size');
    let view_url = $(this).attr('href');

    $('#global-modal .modal-header .modal-title').html(title);

    if (size) {
        $('#global-modal .modal-dialog').addClass(size);
    }

    $('#global-modal').modal();
    $('#modal-body-global-modal').html('<h3 class="text-center"> <i class="fa fa-spinner fa-spin"></i> loading...</h3>');

    $.get(view_url, function (data) {
        $('#modal-body-global-modal').html(data);
        initElements();
        initThemeElements();
        initSelect2ajax();
    });

    $('#global-modal').on('shown.bs.modal', function () {
    });

    $('#global-modal').on('hidden.bs.modal', function () {
        $('#global-modal .modal-body').data('');
        if (size) {
            $('#global-modal .modal-dialog').removeClass(size);
        }
    });
})

function clearFormValidationBeforeSubmit(form) {
    $('.has-error .help-block', form).html('');

    $('.form-group', form).removeClass('has-error');

    $('.nav.nav-tabs li a').removeClass('c-red');
}

function handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, actionData, table, $form) {
    if ($form) {
        try {
            $form.prop('is_dirty', false);
        } catch (e) {

        }
    }

    if (response.message) {
        themeNotify(response);
    }

    if (response.csrf_token) {
        $('meta[name="csrf-token"]').attr('content', response.csrf_token);
        $('input[name="_token"]').val(response.csrf_token);

    }

    if (response.action) {
        window[response.action](response, $form);
    }

    if (undefined !== table) {
        refreshDataTable(table);
    }

    if (undefined !== page_action) {
        window[page_action](response, $form, actionData);
    }

    closeModal(response, $form);
}

function handleAjaxSubmitError(response, textStatus, jqXHR, $form) {
    if (response.status === 422) {
        var errors = $.parseJSON(response.responseText)['errors'];
        // Iterate through errors object.
        $.each(errors, function (field, message) {
            //console.error(field+': '+message);
            //handle arrays
            if (field.indexOf('.') !== -1) {
                field = field.replace('.', '[');
                //handle multi dimensional array
                for (let i = 1; i <= (field.match(/./g) || []).length; i++) {
                    field = field.replace('.', '][');
                }
                field = field + "]";
            }
            var formGroup = $('[name="' + field + '"]', $form).closest('.form-group');
            //Try array name
            if (formGroup.length === 0) {
                formGroup = $('[name="' + field + '[]"]', $form).closest('.form-group');
            }

            // try data-name
            if (formGroup.length === 0) {
                formGroup = $('[data-name="' + field + '"]', $form).closest('.form-group');
            }

            if (formGroup.length === 0) {
                field = field.replace(/[0-9]/, '');
                formGroup = $('[name="' + field + '"]', $form).closest('.form-group');
            }


            var tabIndex = formGroup.closest('.tab-pane').index();

            var panel = formGroup.closest('.panel').find('.panel-title').addClass('c-red');
            if (tabIndex >= 0) {
                $('.nav.nav-tabs li').eq(tabIndex).find('a').addClass('c-red');
            }
            formGroup.removeClass('hidden');

            formGroup.addClass('has-error has-danger').append('<small class="help-block form-control-feedback">' + message + '</small>');
        });
    }
    var data = {};
    data.message = $.parseJSON(response.responseText)['message'];
    data.level = 'error';
    themeNotify(data);

}

function divSubmit(div) {
    if (!div.length) {
        return
    }
    if (!div.data('url') || !div.data('method')) {
        return;
    }

    $('.has-error .help-block').html('');

    var page_action = div.data('page_action');
    var table = div.data('table');

    var actionData = div.data('action_data');

    var data = div.find('input, textarea, select').serializeArray();

    var formData = new FormData();

    $.each(data, function (index, element) {
        formData.append(element.name, element.value);
    });

    $.ajax({
        url: div.data('url'),
        type: div.data('method'),
        processData: false,
        contentType: false,
        dataType: 'json',
        data: formData,
        success: function (response, textStatus, jqXHR) {
            handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, actionData, table)
        },
        error: function (response, textStatus, jqXHR) {
            handleAjaxSubmitError(response, textStatus, jqXHR, div);
        }
    });
}

function ajax_form($form) {

    clearFormValidationBeforeSubmit($form);

    var page_action = $form.data('page_action');

    var actionData = $form.data('action_data');

    var table = $form.data('table');

    var formData = new FormData($form.get(0));

    var button = $('button[name]:focus', $form);

    if (button.length) {
        formData.append(button.attr('name'), button.attr('value'));
    }

    var url = $form.attr('action');

    var confirmation_message = $form.data('confirmation');

    if (undefined !== confirmation_message) {
        themeConfirmation(corals.confirmation.title, confirmation_message, 'info', corals.confirmation.yes, corals.confirmation.cancel, function () {
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response, textStatus, jqXHR) {
                    handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, actionData, table, $form);
                },
                error: function (response, textStatus, jqXHR) {
                    handleAjaxSubmitError(response, textStatus, jqXHR, $form);
                    if (typeof grecaptcha !== 'undefined') {
                        grecaptcha.reset()

                    }
                }
            });
        });
        return;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response, textStatus, jqXHR) {
            handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, actionData, table, $form);
        },
        error: function (response, textStatus, jqXHR) {
            handleAjaxSubmitError(response, textStatus, jqXHR, $form);
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset()
            }
        }
    });
}

function ajaxRequest(url, requestData, table, page_action, method) {
    $.ajax({
        url: url,
        type: method,
        processData: false,
        contentType: false,
        dataType: 'json',
        data: JSON.stringify(requestData),
        success: function (data, textStatus, jqXHR) {
            themeNotify(data);

            if (undefined !== table) {
                refreshDataTable(table);
            }
            if (undefined !== page_action) {
                window[page_action](data);
            }
        },
        error: function (data, textStatus, jqXHR) {
            themeNotify(data);
        }
    });
}

function openInNewWindow(url, title) {
    event.preventDefault();
    var newWindow = window.open(url, title, 'height=480,width=640,top=200,left=300,resizable');

    if (window.focus) {
        newWindow.focus();
    }
}

function getURLSearchParameters() {
    let searchParameters = window.location.search.substr(1);

    let params = {};

    if (searchParameters !== null && searchParameters !== "") {

        let parametersArray = searchParameters.split("&");

        for (let i = 0; i < parametersArray.length; i++) {
            let parameter = parametersArray[i].split("=");
            params[parameter[0]] = parameter[1];
        }
    }

    return params;
}

function translateFormFields(response) {
    let form = $('[data-hashed_id="' + response.hashed_id + '"]').closest('form');

    if (!form.length) {
        return;
    }

    let translateables = response.translateables;

    $.each(translateables, function (fieldId, value) {
        $("#" + fieldId, form).val(value);
        $("#" + fieldId, form).prop('is_dirty', false);
    });

    if (window.CKEDITOR) {

        for (var i in CKEDITOR.instances) {
            let textarea_name = CKEDITOR.instances[i].name;


            let $textarea = $('textarea[name="' + textarea_name + '"]');

            let path = textarea_name.replace('[', '.');

            path = path.replace(']', '');

            if (_.get(translateables, path) !== undefined) {
                CKEDITOR.instances[i].setData(_.get(translateables, path));
                CKEDITOR.instances[i].updateElement();
            }
        }
    }

}

function switchFormFieldsTranslation(response, form, action_data) {

    let langCodeSelected = action_data.langCodeSelected;
    let activeLangCode = action_data.activeLangCode;
    let model = action_data.model;
    let hashed_id = action_data.hashed_id;

    activeLangCode.val(langCodeSelected);

    let url = '/get-model-translation';

    let requestData = {
        model: model, lang_code: langCodeSelected, hashed_id: hashed_id
    };

    let page_action = 'translateFormFields';

    ajaxRequest(url, requestData, undefined, page_action, 'POST');

    $('.form_language_switcher .btn', form).removeClass('current-lang btn-success');

    $('[data-lang_code="' + langCodeSelected + '"]').addClass('current-lang btn-default btn-secondary');

    initFormLangSwitcher();

    form.data('action_data', form.data('old_action_data'));
    form.data('page_action', form.data('old_page_action'));

    $("input[name=translation_submit]", form).remove();
}

function initFormLangSwitcher() {
    $('.current-lang').addClass('btn-success').removeClass('btn-default btn-secondary');
}

function initFormIsDirty() {
    $('form input, form select, form textarea, form input[type="checkbox"], form input[type="radio"]').not('.ignore-dirty-state').change(function () {
        $(this).prop('is_dirty', true);
        $(this).closest('form').prop('is_dirty', true);
    });

    if (window.CKEDITOR) {
        for (var i in CKEDITOR.instances) {
            addCKeditorUpdate(i);
        }
    }
}

function addCKeditorUpdate(instance) {
    CKEDITOR.instances[instance].on('key', function (event) {
        let textarea_name = CKEDITOR.instances[instance].name;
        CKEDITOR.instances[instance].updateElement();
        let $textarea = $('textarea[name="' + textarea_name + '"]');
        $textarea.prop('is_dirty', true);
        $textarea.closest('form').prop('is_dirty', true);

    });
}

function isFormDirty(form) {
    return !!$(form).prop('is_dirty');
}

function filters(tableId) {
    var filters = $(tableId + "_filters" + ' .filter');

    var serialized = filters.serialize();

    return {'filters': serialized};
}

function jQueryBuilderFilters(tableId) {

    try {
        var filters = $(tableId + "_filters").queryBuilder('getRules');

        let url = window.location.href.split('?')[0];


        if (filters) {
            url = `${url}?${jQuery.param({'q': filters})}`;
        }

        window.history.pushState(null, null, url);


        return filters;
    } catch (e) {
        return {};
    }

}

$(document).on('click', '.clearBtn', function (e) {
    var tableId = $(this).data('table');
    var filtersId = "#" + tableId + "_filters";

    e.preventDefault();

    $(filtersId + ' input:not(:checkbox)').val("");

    $(filtersId + ' select').val("").trigger('change');

    $(':checkbox').prop('checked', false);

    if ($.fn.iCheck) {
        $(filtersId + ' input[type="checkbox"]').iCheck('update');
    }

    window.LaravelDataTables[tableId].draw();
});

$(document).on('click', '.filterBtn', function (e) {
    e.preventDefault();

    var tableId = $(this).data('table');

    window.LaravelDataTables[tableId].draw();
});


$(document).on('keypress', '.filtersCollapse input', function (e) {

    if (e.keyCode === 13) {
        $('.filtersCollapse input').change();
        $('.filterBtn').trigger('click')
    }
});

$(document).on('shown.bs.collapse', ".filtersCollapse", function (e) {
    var filtersCollapseHref = "#" + $(this).attr('id');
    var $filterCollapseBtn = $("a[href='" + filtersCollapseHref + "']");
    $filterCollapseBtn.html(corals.filter_close);
    $filterCollapseBtn.removeClass('btn-info');
    $filterCollapseBtn.addClass('btn-warning');
});

$(document).on('hidden.bs.collapse', ".filtersCollapse", function (e) {
    var filtersCollapseHref = "#" + $(this).attr('id');
    var $filterCollapseBtn = $("a[href='" + filtersCollapseHref + "']");
    $filterCollapseBtn.html(corals.filter_open);
    $filterCollapseBtn.removeClass('btn-warning');
    $filterCollapseBtn.addClass('btn-info');
});

function updateParentObjectField(parents, parent_type, parentObject) {
    parent_type.change(function () {
        let selectedType = $(this).val();

        if (_.isEmpty(selectedType)) {
            return;
        }

        let parent = parents[selectedType];
        parentObject.select2('val', null);

        parentObject.data('model', parent['model_class']);
        parentObject.data('columns', parent['columns']);
        parentObject.data('where', parent['where']);
        parentObject.data('scopes', parent.hasOwnProperty('scopes') ? parent['scopes'] : []);
    });
}

function parse_str(str, array) {

    var strArr = String(str).replace(/^&/, '').replace(/&$/, '').split('&')
    var sal = strArr.length
    var i
    var j
    var ct
    var p
    var lastObj
    var obj
    var chr
    var tmp
    var key
    var value
    var postLeftBracketPos
    var keys
    var keysLen

    var _fixStr = function (str) {
        return decodeURIComponent(str.replace(/\+/g, '%20'))
    }

    var $global = (typeof window !== 'undefined' ? window : global)
    $global.$locutus = $global.$locutus || {}
    var $locutus = $global.$locutus
    $locutus.php = $locutus.php || {}

    if (!array) {
        array = $global
    }

    for (i = 0; i < sal; i++) {
        tmp = strArr[i].split('=')
        key = _fixStr(tmp[0])
        value = (tmp.length < 2) ? '' : _fixStr(tmp[1])

        while (key.charAt(0) === ' ') {
            key = key.slice(1)
        }

        if (key.indexOf('\x00') > -1) {
            key = key.slice(0, key.indexOf('\x00'))
        }

        if (key && key.charAt(0) !== '[') {
            keys = []
            postLeftBracketPos = 0

            for (j = 0; j < key.length; j++) {
                if (key.charAt(j) === '[' && !postLeftBracketPos) {
                    postLeftBracketPos = j + 1
                } else if (key.charAt(j) === ']') {
                    if (postLeftBracketPos) {
                        if (!keys.length) {
                            keys.push(key.slice(0, postLeftBracketPos - 1))
                        }

                        keys.push(key.substr(postLeftBracketPos, j - postLeftBracketPos))
                        postLeftBracketPos = 0

                        if (key.charAt(j + 1) !== '[') {
                            break
                        }
                    }
                }
            }

            if (!keys.length) {
                keys = [key]
            }

            for (j = 0; j < keys[0].length; j++) {
                chr = keys[0].charAt(j)

                if (chr === ' ' || chr === '.' || chr === '[') {
                    keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1)
                }

                if (chr === '[') {
                    break
                }
            }

            obj = array

            for (j = 0, keysLen = keys.length; j < keysLen; j++) {
                key = keys[j].replace(/^['"]/, '').replace(/['"]$/, '')
                lastObj = obj

                if ((key === '' || key === ' ') && j !== 0) {
                    // Insert new dimension
                    ct = -1

                    for (p in obj) {
                        if (obj.hasOwnProperty(p)) {
                            if (+p > ct && p.match(/^\d+$/g)) {
                                ct = +p
                            }
                        }
                    }

                    key = ct + 1
                }

                // if primitive value, replace with object
                if (Object(obj[key]) !== obj[key]) {
                    obj[key] = []
                }

                obj = obj[key]
            }

            lastObj[key] = value
        }
    }
}

function initializeCoralsDatetimePicker() {
    $('.coralsDatetimePicker .time-picker, .coralsDatetimePicker .corals-datepicker').on('change', function () {

        let hoursMinutesValue, dateVale;

        if ($(this).hasClass('time-picker')) {
            hoursMinutesValue = $(this).val();
            dateVale = $(this).closest('.coralsDatetimePicker').find('.corals-datepicker').val();
        } else {
            dateVale = $(this).val();
            hoursMinutesValue = $(this).closest('.coralsDatetimePicker').find('.time-picker').val();
        }

        let fullDateTime = dateVale + (hoursMinutesValue == null ? '' : (' ' + hoursMinutesValue));

        if (dateVale && hoursMinutesValue) {
            $(this).closest('.coralsDatetimePicker').find(".datetime-hidden")
                .val(fullDateTime)
                .trigger('change');
        }
    });

    $('.coralsDatetimePicker .time-picker').each(function () {

        if ($(this).data('timepicker-init')) {
            return;
        }

        let startHour = $(this).data('start_hour') ? $(this).data('start_hour') : 0,
            minutesStep = $(this).data('minutes_step') ? $(this).data('minutes_step') : 30,
            lastHour = $(this).data('last_hour') ? $(this).data('last_hour') : 24, displayTime, timeValue,
            timeElement = $(this), timeValuesArray = [];

        for (let hour = startHour; hour < lastHour; hour++) {
            for (let minutes = 0; minutes <= 59; minutes += minutesStep) {

                timeValue = moment().hour(hour).minute(minutes).seconds(0).format('HH:mm:ss');
                displayTime = moment().hour(hour).minute(minutes).format('hh:mm A');

                timeValuesArray.push(timeValue);

                timeElement.append(`<option value="${timeValue}">${displayTime}</option>`);
            }
        }

        //set the values
        let datetimeValue = $(this).closest('.coralsDatetimePicker').find(".datetime-hidden").val(),
            fullDatetime = datetimeValue.split(" "),
            bDatepicker = $(this).closest('.coralsDatetimePicker').find('.corals-datepicker'),
            momentDate = moment(fullDatetime[0], corals.dateInputFormat).format(corals.dateInputFormat);

        if (momentDate === 'Invalid date') {
            momentDate = moment(fullDatetime[0]).format(corals.dateInputFormat);
        }

        if (momentDate === 'Invalid date') {
            return;
        }

        bDatepicker.val(momentDate);


        if (fullDatetime[1] === undefined || !timeValuesArray.includes(fullDatetime[1])) {
            fullDatetime[1] = '';//corals.defaultSelectedHour;
        }

        timeElement.val(fullDatetime[1]);

        timeElement.trigger('change');

        $(this).data('timepicker-init', true);

        let dependWith = bDatepicker.data('depend_with');

        if (dependWith) {
            let targetDatetimePicker = $(dependWith).closest('.coralsDatetimePicker').find('.corals-datepicker');

            $(targetDatetimePicker).datepicker('setStartDate', momentDate);

            if (fullDatetime[0] > targetDatetimePicker.val()) {
                targetDatetimePicker.val(null);
            }
        }
    });
}

function initializeAutoCompleteSearch() {
    if ($.fn.autoComplete) {
        $('.auto-complete').each(function () {
            let $el = $(this);
            $el.autoComplete({
                resolver: 'custom', bootstrapVersion: $el.data('bs') ?? 'auto', formatResult: function (item) {
                    return {
                        value: item.id,
                        text: item.name,
                        html: [$('<img>').attr('src', item.image).css("height", 18), ' ', item.name]
                    };
                }, events: {
                    search: function (qry, callback) {
                        // let's do a custom ajax call
                        $.ajax($el.data('url'), {
                            data: {'search': qry}
                        }).done(function (res) {
                            callback(res.results)
                        });
                    }
                }
            });

            $el.on('autocomplete.select', function (evt, item) {
                $(location).attr('href', item.url)
            });

        });
    }
}

function getCookie(name, parseJson = false) {
    let cookies = "; " + document.cookie;
    let parts = cookies.split("; " + name + "=");

    if (parts.length == 2) {
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
