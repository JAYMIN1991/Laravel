/**
 * Created by flinnt-php-6 on 6/3/17.
 */
if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();

(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.usersCount', {
        _create: function () {
            var self = this;

            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var dateFormat = i18next.t('config:datetime.input_date_format');
            var lowerDateFormat = dateFormat.toLowerCase();
            var startDate = $("#st_date");
            var endDate = $("#et_date");

            startDate.bdatepicker({
                format: lowerDateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn:true
            }).on('changeDate', function () {
                var selected = $('#date_from').val();
                var dt = dateWrapper(selected, dateFormat, true);
                $("#et_date").bdatepicker('setStartDate', dt.format(dateFormat));
            });

            endDate.bdatepicker({
                format: lowerDateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn:true
            }).on('changeDate', function () {
                var selected = $('#date_to').val();
                var dt = dateWrapper(selected, dateFormat, true);
                $("#st_date").bdatepicker('setEndDate', dt.format(dateFormat));
            });
        },
        _bindEvents: function () {
            $("#fsentsms").on("submit", function() {
                $(this).find(".fa").remove();
                $(this).prepend(spinner).addClass("disabled").attr("disabled", "disabled");
            })
        }
    });
}));

$(document).ready(function () {
    $('#users_count_controller').usersCount();
});

