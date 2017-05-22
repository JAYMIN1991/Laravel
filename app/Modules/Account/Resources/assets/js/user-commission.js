(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.userCommissionMst', {
        _create: function () {
            var self = this;
            self.instituteList = $('#institute_id');
            self.courseType = $('#course_type');
            self.actual_commission = $('#actual_commission');
            self.instituteUrl = self.instituteList.data('action');
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var self = this;
            self.tooltip = $('[data-tooltip="true"]').tooltip;
        },
        _bindEvents: function () {
            var self = this;
            self.actual_comm_hdn = $('#actual_cmm_hdn');
            $(self.courseType).on('change', function () {
                if (self.courseType.val() == 2) {
                    self.actual_commission.val('7.00 %');
                }
                else if (self.courseType.val() == 3) {
                    self.actual_commission.val('50.00 %');
                }
                else {
                    self.actual_commission.val('');
                }
            });

            $("#frm_user_commission_mst").validate({
                rules: {
                    course_type: {
                        required: true
                    },
                    institute_id: "required",
                    actual_commission: "required",
                    apply_commission: {
                        required: true,
                        number: true,
                        max: 50,
                        min: 0
                    }
                },
                submitHandler: function (form) {
                    $("#submit").prepend(this.spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });

            // Auto suggest the institution list using select2
            var instituteSelect = self.instituteList.select2({
                minimumInputLength: 2,
                ajax: {
                    url: self.instituteUrl,
                    dataType: 'json',
                    type: "GET",
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: self.formatInstituteList,
                templateSelection: self.showInstituteSelection
            });

            // On post back get the institute id and name and pre-filled in course list
            var selectedInstituteId = self.instituteList.data('old-input');
            var selectedInstituteName = self.instituteList.data('institute-name');
            if (selectedInstituteId) {
                var option = new Option(selectedInstituteName, selectedInstituteId, true, true);
                instituteSelect.append(option).trigger('change.select2');
            }
        },

        // Format the course list get through select2 auto suggest
        formatInstituteList: function (data) {
            if (data.loading) return data.text;

            return data.user_school_name;
        },

        // Change the key of name of the select2
        showInstituteSelection: function (data) {
            return _.has(data, "user_school_name") ? data.user_school_name : data.text;
        },

        // Format the course list get through select2 auto suggest
        formatCourseList: function (data) {
            if (data.loading) return data.text;
            return data.course_name;
        },

        // Change the key of name of the select2
        showCourseSelection: function (data) {
            return _.has(data, "course_name") ? data.course_name : data.text;
        }

    });
}));

$(document).ready(function () {
    $("#user_commission_container").userCommissionMst();
});