(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.instituteBankList', {
        options: {},
        _create: function () {
            var self = this;
            self.instituteList = $('#institute_id');
            self.instituteUrl = self.instituteList.data('action');
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });

        },
        _initComponents: function () {
            $('[data-tooltip="true"]').tooltip();
        },
        _bindEvents: function () {
            var self = this;
            //console.log(self.instituteUrl);
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
                escapeMarkup: function(markup) { return markup; },
                templateResult: self.formatInstituteList,
                templateSelection: self.showInstituteSelection
            });

            // On post back get the institute id and name and pre-filled in course list
            var selectedInstituteId = self.instituteList.data('old-input');
            var selectedInstituteName = self.instituteList.data('institute-name');
            if (selectedInstituteId)
            {
                var option = new Option(selectedInstituteName, selectedInstituteId, true, true);
                instituteSelect.append(option).trigger('change.select2');
            }
        },

        // Format the course list get through select2 auto suggest
        formatInstituteList: function(data) {
            if(data.loading) return data.text;

            return data.user_school_name;
        },

        // Change the key of name of the select2
        showInstituteSelection: function(data) {
            return _.has(data, "user_school_name") ? data.user_school_name: data.text;
        },

        // Format the course list get through select2 auto suggest
        formatCourseList: function(data) {
            if(data.loading) return data.text;

            return data.course_name;
        },

        // Change the key of name of the select2
        showCourseSelection: function(data) {
            return _.has(data, "course_name") ? data.course_name : data.text;
        }

    });
}));

$(document).ready(function () {
    $("#institute_bank_list").instituteBankList();
});