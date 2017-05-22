/**
 * Created by flinnt-php-6 on 16/2/17.
 */
;if (typeof $.fn.bdatepicker == 'undefined') {
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
}

;(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.contentUserReport', {
        options: {},
        _create: function () {
            var self = this;

            self.searchContentUserReportForm = $('#search_content_user_report_form', this.element);
            self.sourceInstitute = $('#source_institute_id', this.element);
            self.sourceInstituteUrl = self.sourceInstitute.data('action');
            self.targetInstitute = $('#target_institute_id', this.element);
            self.targetInstituteUrl = self.targetInstitute.data('action');
            self.sourceCourse = $('#source_course_id', this.element);
            self.sourceCourseSelect = null;
            self.sourceCourseUrl = self.sourceCourse.data('action');
            self.targetCourse = $('#target_course_id', this.element);
            self.targetCourseSelect = null;
            self.targetCourseUrl = self.targetCourse.data('action');
            self.showDeleted = $('#show_deleted_course', this.element);
            self.greaterThanZero = $('#greater_than_zero', this.element);
            
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents : function () {
            var self = this;
            var i18next = window.i18next;
            var dateFormat = i18next.t('config:datetime.input_date_format');
            $("#st_date").bdatepicker({
                format: dateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn:true,
            }).on('changeDate', function () {
                var selected = $('#date_from').val();
                var dt = dateWrapper(selected, dateFormat, true);
                dt.add(1, 'day');
                $("#et_date").bdatepicker('setStartDate', dt.format(dateFormat));
            });

            $("#et_date").bdatepicker({
                format: dateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn:true,
            }).on('changeDate', function () {
                var selected = $('#date_to').val();
                var dt = dateWrapper(selected, dateFormat, true);
                dt.subtract(1, 'day');
                $("#st_date").bdatepicker('setEndDate', dt.format(dateFormat));
            });

            $('[data-tooltip="true"]').tooltip();
            self.sourceCourseSelect = self.sourceCourse.select2();
            self.targetCourseSelect = self.targetCourse.select2();
        },
        _bindEvents : function () {
            var self = this;

            self.searchContentUserReportForm.validate({
                ignore: ":hidden,null",
                rules : {
                    source_institute_id: 'required',
                }
            });

            self.searchContentUserReportForm.submit(function () {
                if ($(this).valid()){
                    if (!self.greaterThanZero.is(':checked')){
                        $(this).append('<input type="hidden" name="greater_than_zero" value="0">')
                    }
                }
                return true;
            });

            self.showDeleted.on('change', function () {
                var sourceInstitute = self.sourceInstitute.val();
                var targetInstitute = self.targetInstitute.val();
                var value = 0;
                if ($(this).is(':checked')){
                    value = 1;
                }
                if( sourceInstitute != null) {
                    self.fillSourceCourseDropdown(sourceInstitute, value);
                }
                if(targetInstitute != null) {
                    self.fillTargetCourseDropdown(targetInstitute, value);
                }
            });

            // Auto suggest the institution list using select2
            var sourceInstituteSelect = self.sourceInstitute.select2({
                minimumInputLength: 2,
                ajax: {
                    url: self.sourceInstituteUrl,
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
            }).on('change.select2', function () {
                var instId = $(this).val();
                var deleted = self.showDeleted.val();
                self.fillSourceCourseDropdown(instId, deleted);
            });

            // On post back get the source institute id and name and pre-fill the course list
            var selectedSourceInstituteId = self.sourceInstitute.data('old-input');
            var selectedSourceInstituteName = self.sourceInstitute.data('institute-name');
            if (selectedSourceInstituteId)
            {
                // @todo :: Create function to append option to select
                var sourceOption = new Option(selectedSourceInstituteName, selectedSourceInstituteId, true, true);
                sourceInstituteSelect.append(sourceOption).trigger('change.select2');
            }

            // Auto suggest the institution list using select2
            var targetInstituteSelect = self.targetInstitute.select2({
                minimumInputLength: 2,
                ajax: {
                    url: self.targetInstituteUrl,
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
            }).on('change.select2', function () {
                var instId = $(this).val();
                var deleted = self.showDeleted.val();
                self.fillTargetCourseDropdown(instId, deleted);
            });

            // On post back get the source institute id and name and pre-fill the course list
            var selectedTargetInstituteId = self.targetInstitute.data('old-input');
            var selectedTargetInstituteName = self.targetInstitute.data('institute-name');
            if (selectedTargetInstituteId)
            {
                // @todo :: Create function to append option to select
                var targetOption = new Option(selectedTargetInstituteName, selectedTargetInstituteId, true, true);
                targetInstituteSelect.append(targetOption).trigger('change.select2');
            }
        },

        // Fill the source course dropdown
        fillSourceCourseDropdown: function (instId, deleted) {
            var self = this;
            self.sourceCourse.removeAttr('disabled');
            self.sourceCourseSelect.empty();

            var selectedToCourse = self.sourceCourse.data('old-input');

            $.ajax({
                url : self.sourceCourseUrl,
                dataType: 'json',
                type: "GET",
                delay: 250,
                data: { inst_id : instId, deleted:deleted }

            }).done(function (response) {
                var courses = response.items;

                if (courses){
                    self.sourceCourseSelect.append(new Option('All', 0, true, true));
                    // @todo :: Create function to append option to select
                    for (var i in courses ){
                        var option = new Option(courses[i].course_name, courses[i].id);
                        self.sourceCourseSelect.append(option);
                    }
                }

                if (selectedToCourse){
                    self.sourceCourseSelect.val(selectedToCourse);
                }

                self.sourceCourseSelect.trigger('change.select2');

            });
        },

        // Fill the source course dropdown
        fillTargetCourseDropdown: function (instId, deleted) {
            var self = this;
            self.targetCourse.removeAttr('disabled');
            self.targetCourseSelect.empty();

            var selectedToCourse = self.targetCourse.data('old-input');

            $.ajax({
                url : self.targetCourseUrl,
                dataType: 'json',
                type: "GET",
                delay: 250,
                data: { inst_id : instId, deleted: deleted }

            }).done(function (response) {
                var courses = response.items;

                if (courses){
                    self.targetCourseSelect.append(new Option('All', 0, true, true));
                    // @todo :: Create function to append option to select
                    for (var i in courses ){
                        var option = new Option(courses[i].course_name, courses[i].id);
                        self.targetCourseSelect.append(option);
                    }
                }

                if (selectedToCourse){
                    self.targetCourseSelect.val(selectedToCourse);
                }

                self.targetCourseSelect.trigger('change.select2');

            });
        },

        // Format the institute list get through select2 auto suggest
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
            return _.has(data, "course_name") ? data.course_name: data.text;
        },
    });
}));

$(document).ready(function () {
    $('#content_user_report_controller').contentUserReport();
});