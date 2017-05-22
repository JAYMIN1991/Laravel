/**
 * Created by flinnt-php-6 on 31/1/17.
 */

;(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.copyLearners', {
        options: {},
        _create: function () {
            var self = this;

            self.copyLearnersForm = $('#copy_learners_form', this.element);
            self.copyLearnersButton  = $('#button_copy', this.element);
            self.fromInstitute = $('#from_institute', this.element);
            self.fromInstituteUrl = self.fromInstitute.data('action');
            self.toInstitute = $('#to_institute', this.element);
            self.toInstituteUrl = self.toInstitute.data('action');
            self.fromCourses = $('#from_courses', this.element);
            self.fromCoursesUrl = self.fromCourses.data('action');
            self.fromCoursesSelect = null;
            self.toCourse = $('#to_course', this.element);
            self.toCourseUrl = self.toCourse.data('action');
            self.toCourseSelect = null;

            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents : function () {
            var self = this;
            $('[data-tooltip="true"]').tooltip();
            self.fromCoursesSelect = self.fromCourses.select2();
            self.toCourseSelect = self.toCourse.select2();
        },
        _bindEvents : function () {
            var self = this;

            // Validate the user search form
            self.copyLearnersForm.validate({
                ignore: ":hidden, null",
                rules: {
                    'from_institute' : 'required',
                    'from_courses[]' : 'required',
                    'to_institute' : 'required',
                    'to_course' : 'required'
                }
            });

            var fromInsitituteSelect = self.fromInstitute.select2({
                minimumInputLength: 2,
                ajax: {
                    url: self.fromInstituteUrl,
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
                self.fillFromCourseDropdown(instId);
            });


            // On post back get the from institute id and name and pre-fill the course list
            var selectedFromInstituteId = self.fromInstitute.data('old-input');
            var selectedFromInstituteName = self.fromInstitute.data('institute-name');
            if (selectedFromInstituteId)
            {
                // @todo :: Create function to append option to select
                var fromOption = new Option(selectedFromInstituteName, selectedFromInstituteId, true, true);
                fromInsitituteSelect.append(fromOption).trigger('change.select2');
            }

            var toInsitituteSelect = self.toInstitute.select2({
                minimumInputLength: 2,
                ajax: {
                    url: self.toInstituteUrl,
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
                self.fillToCourseDropdown(instId);
            });

            // On post back get the institute id and name and pre-filled in course list
            var selectedToInstituteId = self.toInstitute.data('old-input');
            var selectedToInstituteName = self.toInstitute.data('institute-name');
            if (selectedToInstituteId)
            {
                // @todo :: Create function to append option to select
                var toOption = new Option(selectedToInstituteName, selectedToInstituteId, true, true);
                toInsitituteSelect.append(toOption).trigger('change.select2');
            }
        },

        // Fill the from course dropdown
        fillFromCourseDropdown: function (instId) {
            var self = this;
            self.fromCourses.removeAttr('disabled');
            self.fromCoursesSelect.empty();

            // self.courseSelect.trigger('change.select2');
            var selectedFromCourse =  String(self.fromCourses.data('old-input'));

            $.ajax({
                url : self.fromCoursesUrl,
                dataType: 'json',
                type: "GET",
                delay: 250,
                data: { inst_id : instId }

            }).done(function (response) {
                var courses = response.items;

                if (courses){
                    // @todo :: Create function to append option to select
                    for (var i in courses ){
                        var option = new Option(courses[i].course_name, courses[i].id);
                        self.fromCoursesSelect.append(option);
                    }
                }

                if (selectedFromCourse){
                    console.log(selectedFromCourse);
                    self.fromCoursesSelect.val(selectedFromCourse.split(','));
                }

                self.fromCoursesSelect.trigger('change.select2');

            });
        },

        // Fill the to course dropdown
        fillToCourseDropdown: function (instId) {
            var self = this;
            self.toCourse.removeAttr('disabled');
            self.toCourseSelect.empty();

            // self.courseSelect.trigger('change.select2');
            var selectedToCourse = self.toCourse.data('old-input');

            $.ajax({
                url : self.toCourseUrl,
                dataType: 'json',
                type: "GET",
                delay: 250,
                data: { inst_id : instId }

            }).done(function (response) {
                var courses = response.items;

                if (courses){
                    // @todo :: Create function to append option to select
                    for (var i in courses ){
                        var option = new Option(courses[i].course_name, courses[i].id);
                        self.toCourseSelect.append(option);
                    }
                }

                if (selectedToCourse){
                    self.toCourseSelect.val(selectedToCourse);
                }

                self.toCourseSelect.trigger('change.select2');

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
        }
    });
}));

;$(document).ready(function () {
    $('#copy_learners_controller').copyLearners();
});
