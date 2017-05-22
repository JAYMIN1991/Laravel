;(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.courseInvitation", {
        options: {
        },
        _create:function() {
            var self = this;
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents : function () {
            $('[data-tooltip="true"]').tooltip();
            if($("#invite_manual").is(":checked")) {
                $("#invite_manual_block").show();
                $("#invite_upload_block").hide();
            } else {
                $("#invite_manual_block").hide();
                $("#invite_upload_block").show();
            }
        },
        _bindEvents : function () {
            var self = this;
            var inviteUserButton = $("#btn_invite_usr");
            $("#invite_manual").on("change", function(){
                if($(this).is(":checked")) {
                    $("#invite_manual_block").show();
                    $("#invite_upload_block").hide();
                } else {
                    $("#invite_manual_block").hide();
                    $("#invite_upload_block").show();
                }
            });

            $("#invite_upload").on("change", function(){
                if($(this).is(":checked")) {
                    $("#invite_upload_block").show();
                    $("#invite_manual_block").hide();
                } else {
                    $("#invite_upload_block").hide();
                    $("#invite_manual_block").show();
                }
            });

            // Auto suggested course list using select2
            var courseList = $('#course_id');
            var coursesUrl = courseList.data('action');

            var courseSelect = courseList.select2({
                minimumInputLength: 2,
                ajax: {
                    url: coursesUrl,
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
                templateResult: self.formatCourseList,
                templateSelection: self.showCourseSelection
            });

            // On post back get the institute id and name and pre-filled in course list
            var selectedCourseId = courseList.data('course-selected-id');
            var selectedCourseName = courseList.data('course-selected-name');
            var option = new Option(selectedCourseName, selectedCourseId, true, true);
            courseSelect.append(option).trigger('change.select2');

            // validate the form before submit
            $("#course_invitation_controller").validate({
                ignore: ":hidden",
                rules: {
                    "course_id" : "required",
                    "invite_by": "required"
                },
                submitHandler:function(form) {
                    $("#hdinvite").val(1);
                    inviteUserButton.find(".fa").remove();
                    inviteUserButton.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });
        },

        // Format the course list get through select2 auto suggest
        formatCourseList: function(data) {
            if(data.loading){
                return data.text;
            }
            return data.course_name;
        },

        // Change the key of name of the select2
        showCourseSelection: function(data) {
            return _.has(data, "course_name") ? data.course_name : data.text;
        }
    });
}));


$(document).ready(function(){
    $("#course_invitation_controller").courseInvitation();
});