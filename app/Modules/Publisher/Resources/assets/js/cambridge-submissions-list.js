;if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.cambridgeSubmissionWidget', {
        _create: function () {
            var self = this;
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var self = this;
            self.frmCambridgeSubmissions = $('#frm_cambridge_submissions');
            self.submissionsDateFrom = $('#submissions_date_from');
            self.submissionsDateTo = $('#submissions_date_to');
            self.btnSave = $('#btnsearch');
            self.submissionList = $('#submission_list');
            self.submissionBodyP = $(".submission_body p");
            $('[data-tooltip="true"]').tooltip();
            self.submissionsDateFrom.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                todayBtn: true,
                autoclose: true
            });
            self.submissionsDateTo.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                todayBtn: true,
                autoclose: true
            });
        },
        _bindEvents: function () {
            var self = this;
            self.submissionList.length &&
            $("#submission_list tbody tr td:not('.attachment')").on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                var href = $(this).parent().attr("data-target");
                var w = window.open(href, "_blank");
                w && w.focus();
            });

            self.submissionBodyP.length && self.submissionBodyP.readmore({
                collapsedHeight: 67,
                moreLink: '<a href="#" class="text-small" style="margin-bottom: 20px;"><i class="fa fa-plus"></i> Read More</a>',
                lessLink: '<a href="#" class="text-small" style="margin-bottom: 20px;"><i class="fa fa-minus"></i> Hide</a>'
            });
        }
    });
}));

$(document).ready(function () {
    $("#cambridge_submissions_container").cambridgeSubmissionWidget();
});