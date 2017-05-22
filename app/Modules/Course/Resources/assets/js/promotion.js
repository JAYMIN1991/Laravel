;if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.promotion", {
        _create:function() {
            var self = this;

            self.instituteList = $('#institute-id');
            self.instituteUrl = self.instituteList.data('action');
            self.courseList = $("#course-id");

            window.i18next.on('initialized', function () {
                window.i18next.loadNamespaces('promotion', function(){
                    $.proxy(self._bindEvents, self)();
                });
            });
        },
        _bindEvents:function() {
            var self = this;

            // Auto suggest the institution list using select2
            self.instituteList.select2({
                minimumInputLength: 2,
                allowClear: true,
                placeholder: self.instituteList.attr('data-placeholder'),
                ajax: {
                    url: self.instituteUrl,
                    dataType: 'json',
                    type: "GET",
                    delay: 250,
                    data: function (params) {
                        return {
                            term : params.term // search term
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
            }).on("change", function(){

                self.courseList.empty();
                self.courseList.append(new Option('-- '+ window.i18next.t('promotion:course_placeholder') +' --', '', true, true));

                if(this.value !== '')
                {
                    $.ajax({
                        url: laroute.route('api.services.suggest.institute-courses'),
                        dataType: 'json',
                        data: {
                            'inst_id' : this.value,
                            'for' : 'promotion'
                        },
                        success: function(data) {
                            if(data.status == 1)
                            {
                                var courses = data.items;

                                for (var course in courses) {
                                    var option = new Option(courses[course].course_name, courses[course].id);
                                    self.courseList.append(option);
                                }
                                self.courseList.trigger('change.select2');
                            }
                        }
                    });
                }
            });
        },

        // Format the course list get through select2 auto suggest
        formatInstituteList: function(data) {
            if(data.loading) return data.text;

            return data.user_school_name;
        },

        // Change the key of name of the select2
        showInstituteSelection: function(data) {
            return _.has(data, "user_school_name") ? data.user_school_name: data.text;
        }
    });
}));

$(document).ready(function(){
    $("#promotion-container").promotion();
});