(function (factory) {
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.cambridgeLinguaSkillContainer', {
        options: {},
        _create: function () {
            var self = this;
            self.registrationStartDate = $("#registration_start_date");
            self.registrationEndDate = $("#registration_end_date");
            self.examStartDate = $("#exam_start_date");
            self.examEndDate = $("#exam_end_date");

            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var self = this;
            $('[data-tooltip="true"]').tooltip();
            self.registrationStartDate.bdatepicker({format: 'dd-mm-yyyy', todayBtn: true, autoclose: true});
            self.registrationEndDate.bdatepicker({format: 'dd-mm-yyyy', todayBtn: true, autoclose: true});

            self.examStartDate.bdatepicker({format: 'dd-mm-yyyy', todayBtn: true, autoclose: true});
            self.examEndDate.bdatepicker({format: 'dd-mm-yyyy', todayBtn: true, autoclose: true});
            //self.courseSelect = self.courseList.select2();
        },
        _bindEvents: function () {
            var self = this;

        }
    });
}));

$(document).ready(function () {
    $("#language_skill_container").cambridgeLinguaSkillContainer();
});