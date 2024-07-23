$(document).ready(function () {
    let predefinedDates = predefinedDatesData;
    let ignorePredefinedChangeEvent = false;

    $('.preDefinedDateOption').on('change', function (e) {
        console.log('fff');
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
