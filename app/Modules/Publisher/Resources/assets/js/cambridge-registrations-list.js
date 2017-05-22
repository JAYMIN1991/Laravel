;if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.cambridgeRegistrationWidget', {
        _create: function () {
            var self = this;
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var self = this;
            self.frmCambridgeRegistrations = $('#frm_cambridge_registrations');
            self.registration_date_from = $('#registration_date_from');
            self.registration_date_to = $('#registration_date_to');
            self.registration_email_id = $("#registration_email_id");
            self.registration_mobile_email = $("#mobile_no_email_id");
            self.btnSave = $('#btnsearch');
            $('[data-tooltip="true"]').tooltip();
            self.registration_date_from.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                todayBtn: true,
                autoclose: true
            });
            self.registration_date_to.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                todayBtn: true,
                autoclose: true
            });
        },
        _bindEvents: function () {
            var self = this;

            // Validate the search form before submitting
            self.frmCambridgeRegistrations.validate({
                ignore: ':hidden,null',
                rules: {
                    registration_email_id: {
                        email: true
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
                errorClass: "error-visit",
                showErrors: function () {
                    this.defaultShowErrors();
                },
                submitHandler: function (form) {
                    self.btnSave.find('.fa').remove();
                    self.btnSave.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });
        }
    });
}));

$(document).ready(function () {
    $("#cambridge_registrations_container").cambridgeRegistrationWidget();
});