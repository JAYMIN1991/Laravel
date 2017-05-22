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
            self.module_type = $('#module_type');
            self.existing_module_list = $('#existing_module_list');
            self.new_module_list = $('#new_module_list');
            self.city_type = $('#city_type');
            self.existing_city_list = $('#existing_city_list');
            self.new_city_list = $('#new_city_list');
            self.cambridgeTKTExamForm = $('#cambridgeTKTExamForm');
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var self = this;
            $('[data-tooltip="true"]').tooltip();
            self.date.bdatepicker({format: 'dd M yyyy', todayBtn: true, autoclose: true});
            // For hide module options
            if (self.module_type.val() == 1) {
                self.existing_module_list.show();
                self.new_module_list.hide();
            }
            else if (self.module_type.val() == 2) {
                self.existing_module_list.hide();
                self.new_module_list.show();
            }
            else {
                self.existing_module_list.hide();
                self.new_module_list.hide();
            }
            // For hiding city options
            if (self.city_type.val() == 1) {
                self.existing_city_list.show();
                self.new_city_list.hide();
            }
            else if (self.city_type.val() == 2) {
                self.existing_city_list.hide();
                self.new_city_list.show();
            }
            else {
                self.existing_city_list.hide();
                self.new_city_list.hide();
            }
        },
        _bindEvents: function () {
            var self = this;

            $(self.module_type).on('change', function () {
                if (self.module_type.val() == 1) {
                    self.existing_module_list.show();
                    self.new_module_list.hide();
                }
                else if (self.module_type.val() == 2) {
                    self.existing_module_list.hide();
                    self.new_module_list.show();
                }
                else {
                    self.existing_module_list.hide();
                    self.new_module_list.hide();
                }
            });

            $(self.city_type).on('change', function () {
                if (self.city_type.val() == 1) {
                    self.existing_city_list.show();
                    self.new_city_list.hide();
                }
                else if (self.city_type.val() == 2) {
                    self.existing_city_list.hide();
                    self.new_city_list.show();
                }
                else {
                    self.existing_city_list.hide();
                    self.new_city_list.hide();
                }
            });
            /* Validate method that will be used in edit and create method of institute call visit */
            self.cambridgeTKTExamForm.validate({
                rules: {
                    module_type: "required",
                    module_list_id: {
                        required: {
                            depends: function (element) {
                                return self.module_type.length !== 0;
                            }
                        }
                    },
                    new_module: {
                        required: {
                            depends: function (element) {
                                return self.module_type.length !== 0;
                            }
                        }
                    },
                    city_type: "required",
                    city_name: {
                        required: {
                            depends: function (element) {
                                return self.city_type.length !== 0;
                            }
                        }
                    },
                    new_city: {
                        required: {
                            depends: function (element) {
                                return self.city_type.length !== 0;
                            }
                        }
                    },
                    date: "required",
                    url: {
                        required: true,
                        url: true
                    }
                },
                submitHandler: function (form) {
                    $("#bsave").prepend(self.spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });
        }
    });
}));

$(document).ready(function () {
    $("#cambridge_tkt_exam_container").cambridgeTKTExamContainer();
});