$(function() {

    var rowTemplate = '<div class="input-group time-row" data-index="" style="margin-top: 5px;">'
        + '<div class="input-group clockpicker" data-default="00:00">'
        + '<input type="text" class="form-control open-time" />'
        + '<span class="input-group-addon">'
        + '<i class="glyphicon glyphicon-time"></i>'
        + '</span>'
        + '</div>'
        + '<span class="input-group-addon" style="background-color: transparent; border: none"> 至 </span>'
        + '<div class="input-group clockpicker" data-default="00:00">'
        + '<input type="text" class="form-control close-time" />'
        + '<span class="input-group-addon">'
        + '<i class="glyphicon glyphicon-time"></i>'
        + '</span>'
        + '</div>'
        + '<span class="input-group-btn operate-btn" style="background-color: transparent; border: none;">'
        + '</span>'
        + '</div>';
    var addBtnTemplate = '<button class="btn btn-default add-time-row" type="button">'
        + '<i class="glyphicon glyphicon-plus"></i>'
        + '</button>';
    var deleteBtnTemplate = '<button class="btn btn-default delete-time-row" type="button">'
        + '<i class="glyphicon glyphicon-remove"></i>'
        + '</button>';

    var timeRows = $('#time-rows');

    function addTimeRow(openTime, closeTime) {
        var row = $(rowTemplate);
        var operateBtn = $('.operate-btn', row);

        if (timeRows.children().length == 0) {
            operateBtn.append(addBtnTemplate);
        } else {
            operateBtn.append(deleteBtnTemplate);
        }
        timeRows.append(row);

        row.find('.clockpicker').clockpicker({
            afterDone: updateTimes
        });

        if (openTime != null && closeTime != null) {
            row.find('input.open-time').val(openTime);
            row.find('input.close-time').val(closeTime);
        }
    }

    function deleteTimeRow() {
        var row = $(this).closest('div.time-row');
        row.remove();
        updateTimes();
    }

    function updateTimes() {
        var times = [];
        $('div.time-row', timeRows).each(function() {
            var openTime = $(this).find('input.open-time').val();
            var closeTime = $(this).find('input.close-time').val();
            times.push({
                'open_time': openTime,
                'close_time': closeTime
            });
        });
        timeRows.prev('input').val(JSON.stringify(times));
    }

    function setupDefaultTimes() {
        var json = timeRows.prev('input').val();
        var times = null;
        try {
            times = $.parseJSON(json);
        } catch (e) {
            //console.error(e);
            // noting to do...
        }
        if (times == null || times.length == 0) {
            addTimeRow(null, null);
        } else {
            for (var i = 0; i < times.length; i++) {
                var time = times[i];
                addTimeRow(time['open_time'], time['close_time']);
            }
        }
    }

    $(document).on('click', '.add-time-row', addTimeRow);
    $(document).on('click', '.delete-time-row', deleteTimeRow);

    setupDefaultTimes();





    $('#booking-times').multiselect({
        enableCollapsibleOptGroups: true,
        onDropdownHidden: function() {
            var times = [];
            $('#booking-times option:selected').each(function() {
                times.push($(this).val());
            });
            $('#booking-times').prev('input').val(times.join(','));
        }
    });
});