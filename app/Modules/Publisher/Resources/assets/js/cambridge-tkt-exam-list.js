(function (factory) {
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.cambridgeTKTExamContainer', {
        options: {},
        _create: function () {
            var self = this;
            self.moduleList = $('#module_list_id');
            self.date = $('#date');
            self.city_name = $('#city_name');
            self.url = $('#url');
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var self = this;
            $('[data-tooltip="true"]').tooltip();
            self.date.bdatepicker({format: 'dd-mm-yyyy', todayBtn: true, autoclose: true});
            //self.courseSelect = self.courseList.select2();
        },
        _bindEvents: function () {
            var self = this;
        }
    });
}));

$(document).ready(function () {
    $("#cambridge_tkt_exam_container").cambridgeTKTExamContainer();
});