/**
 * Created by flinnt-php-6 on 21/1/17.
 */

;(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.instituteUsersList', {
        options: {},
        _create: function () {
            var self = this;

            self.searchInstUserForm = $("#search_inst_user_form", this.element);
            self.searchButton = $("#bsearch", this.element);
            self.exportButton = $("#bexport", this.element);
            self.userExportButton = $("#bexport_users", this.element);
            self.addRemark = $("#btnaddrmrk", this.element);
            self.courseList = $('#course-list');
            self.courseUrl = self.courseList.data('action');
            self.instituteList = $('#institute-list');
            self.instituteUrl = self.instituteList.data('action');
            self.courseSelect = null;
            self.userPlanStatus = $('#user-plan-status');
            self.userRow = null;
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });

        },
        _initComponents: function () {
            var self = this;
            $('[data-tooltip="true"]').tooltip();
            self.courseSelect = self.courseList.select2();
        },
        _bindEvents: function () {
            var self = this;
            var i18next = window.i18next;
            var hiddenButton = $('#inst_users_button');
            var newPwd = $("#new_pwd");
            var resetPwd = $("#btnresetpwd");
            var changeEmailButton = $('#btn_change_email');
            var newEmail = $("#new_email");
            var changeMobileButton = $('#btn_change_mobile');
            var newMobile = $('#new_mob');
            // If search button is click set the value of hidden button to 1
            self.searchButton.on("click", function(){
                hiddenButton.val(1);
            });
            // If export button is click set the value of hidden button to 2
            self.exportButton.on("click", function(){
                hiddenButton.val(2);
            });
            // If user export button is click set the value of hidden button to 3
            self.userExportButton.on("click", function(){
                hiddenButton.val(3);
            });
            // If institute is changed set the value of hidden button to 0
            $("#isnt_id").on("change", function(){
                hiddenButton.val(0);
            });

            // Validate the search form before submitting
            self.searchInstUserForm.validate({
                ignore: ":hidden,null",
                rules: {
                    inst_id: "required" // Institute is mandatory field
                }

            });

            // Submit the form
            self.searchInstUserForm.on("submit", function() {
                // If form validation returns true then we disable search and export buttons
                if ($(this).valid()){
                    if(hiddenButton.val() == 1) {
                        self.searchButton.find(".fa").remove();
                        self.searchButton.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                        self.exportButton.addClass("disabled").attr("disabled", "disabled");
                        self.userExportButton.addClass("disabled").attr("disabled", "disabled");
                    }
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
                escapeMarkup: function(markup) { return markup; },
                templateResult: self.formatInstituteList,
                templateSelection: self.showInstituteSelection
            }).on('change.select2', function () {
                var instId = $(this).val();
                self.fillCourseDropdown(instId);
            });

            // On post back get the institute id and name and pre-filled in course list
            var selectedInstituteId = self.instituteList.data('old-input');
            var selectedInstituteName = self.instituteList.data('institute-name');
            if (selectedInstituteId)
            {
                var option = new Option(selectedInstituteName, selectedInstituteId, true, true);
                instituteSelect.append(option).trigger('change.select2');
            }

            // BEGIN Change password button click
            $("#change_pwd").on("show.bs.modal", function (e) {

                var caller = $(e.relatedTarget);
                newPwd.val("");
                self.userRow = caller.closest("tr");
                resetPwd.attr('data-url', caller.attr("data-url"));

                newPwd.off("keydown").on("keydown", function (e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                })
            }).on("shown.bs.modal", function () {
                try {
                    newPwd.focus();
                } catch (e) {
                }
            }).on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });
            // END Change password button click

            // BEGIN Reset password button click
            resetPwd.on("click", function (e) {
                e.preventDefault();

                // Password length must be greater than 6 length
                if (newPwd.val().length < 6) {
                    bootbox.alert(i18next.t('message:validation.min.string', {attribute:'Password', min:6}));
                    return false;
                }

                /**
                 * pdata : Request Data
                 * phtml : Html of modal, used to set after request
                 * url : Request url
                 */
                var pdata = {"pwd": newPwd.val(), "target": "pwd"},
                    phtml = $(this).html(),
                    url = $(this).attr("data-url");

                $(this).attr("disabled", "disabled");
                $(this).prepend(spinner);

                // Ajax request to change the password
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: pdata,
                    datatype: 'json',
                    success: function (resp, status) {
                        if (!_.has(resp, "status")) return;
                        var status = resp.status;
                        if (parseInt(status) == 1) {
                            if (self.userRow != null) {
                                $(self.userRow).fadeOut(500).fadeIn(1000);
                            }
                            notyfy({
                                text: i18next.t('message:success.process'),
                                layout: "top",
                                type: "success",
                                dismisQueue: true,
                                timeout: 3000
                            })
                        }
                    },
                    error: function (xhr) {
                        var j = xhr.responseText;
                        try {
                            j = JSON.parse(j);
                        } catch (e) {
                            return;
                        }

                        self.pe(j);
                    },
                    complete: function () {
                        $("#hdnuserpwd").val("");
                        $("#change_pwd").modal("hide");
                        resetPwd.removeAttr("disabled");
                        resetPwd.html(phtml);
                    }
                })
            });
            // END Reset password button click


            // Show modal of change email when change email button click
            $("#change_email").on("show.bs.modal", function(e){
                var caller = $(e.relatedTarget);
                self.userRow = caller.closest("tr");
                changeEmailButton.attr('data-url', caller.attr("data-url"));
                newEmail.val(caller.attr('data-email'));
                newEmail.off("keydown").on("keydown", function(e){
                    if(e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                })
            }).on("shown.bs.modal", function(){
                try {
                    newEmail.focus();
                }catch(e){}
            }).on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });

            // Call the API to change the email
            changeEmailButton.on("click", function(e){
                e.preventDefault();
                if(!newEmail.length || $.trim(newEmail.val()) == "") {
                    bootbox.alert(i18next.t('message:validation.invalid', {attribute:'email'}));
                    return false;
                }

                /**
                 * pdata : Request Data
                 * phtml : Html of modal, used to set after request
                 * url : Request url
                 */
                var pdata = {"email": newEmail.val(), "target": "email"},
                    phtml = $(this).html(),
                    url = $(this).attr("data-url");

                $(this).attr("disabled", "disabled");

                // Ajax request to change the email
                $(this).prepend(spinner);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: pdata,
                    datatype: 'json',
                    success: function(resp) {
                        if(!_.has(resp, "status")) return;
                        var status = resp.status;
                        if(parseInt(status) == 1) {
                            if(self.userRow != null) {
                                $(self.userRow).find(".usermail").fadeOut(500).html(newEmail.val()).fadeIn(1000);
                                $(self.userRow).find(".change-email").remove();
                            }
                            notyfy({
                                text: i18next.t('message:success.process'),
                                layout: "top",
                                type: "success",
                                dismisQueue: true,
                                timeout: 3000
                            })
                        }
                    },
                    error: function(xhr) {
                        var j = xhr.responseText;
                        try {
                            j = JSON.parse(j);
                        }catch(e) {
                            return;
                        }

                        self.pe(j);
                    },
                    complete: function() {
                        $("#hdnemluser").val("");
                        $("#change_email").modal("hide");
                        changeEmailButton.removeAttr("disabled");
                        changeEmailButton.html(phtml);
                    }
                })
            });

            $("#change_mob").on("show.bs.modal", function(e){
                var caller = $(e.relatedTarget);
                self.userRow = caller.closest("tr");
                changeMobileButton.attr('data-url', caller.attr("data-url"));
                newMobile.val(caller.attr("data-mobile"));

                newMobile.off("keydown").on("keydown", function(e){
                    if(e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                })
            }).on("shown.bs.modal", function(){
                try {
                    newMobile.focus();
                }catch(e){}
            }).on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });

            changeMobileButton.on("click", function(e){
                e.preventDefault();
                if(!newMobile.length || isNaN(parseInt(newMobile.val())) || !isFinite(newMobile.val())) {
                    bootbox.alert(i18next.t('message:validation.invalid', {attribute:'mobile number'}));
                    return false;
                }

                var pdata = {"mobile": newMobile.val(), "user": $("#hdnuser").val(), "target": "mobile"},
                    phtml = $(this).html(),
                    url = $(this).attr("data-url");
                $(this).attr("disabled", "disabled");
                $(this).prepend(spinner);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: pdata,
                    datatype: 'json',
                    success: function(resp, status) {
                        if(!_.has(resp, "status")) return;
                        var status = resp.status;
                        if(parseInt(status) == 1) {
                            if(self.userRow != null) {
                                $(self.userRow).find(".usermob").fadeOut(500).html(newMobile.val()).fadeIn(1000);
                                $(self.userRow).find(".changemob").attr("data-mobile", newMobile.val());
                            }
                            notyfy({
                                text: i18next.t('message:success.process'),
                                layout: "top",
                                type: "success",
                                dismisQueue: true,
                                timeout: 3000
                            })
                        }
                    },
                    error: function(xhr) {
                        var j = xhr.responseText;
                        try {
                            j = JSON.parse(j);
                        }catch(e) {
                            return;
                        }

                        self.pe(j);
                    },
                    complete: function() {
                        $("#hdnuser").val("");
                        $("#change_mob").modal("hide");
                        changeMobileButton.removeAttr("disabled");
                        changeMobileButton.html(phtml);
                    }
                })
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
        },

        // Format the course list get through select2 auto suggest
        formatCourseList: function(data) {
            if(data.loading) return data.text;

            return data.course_name;
        },

        // Change the key of name of the select2
        showCourseSelection: function(data) {
            return _.has(data, "course_name") ? data.course_name : data.text;
        },

        // Get the courses based on selected institute and fill the courses dropdown
        fillCourseDropdown: function (instId) {
            var self = this;
            self.courseList.removeAttr('disabled');
            self.courseSelect.empty();
            // self.courseSelect.trigger('change.select2');
            var selectedCourse = self.courseList.data('old-input');
            $.ajax({
                    url : self.courseUrl,
                    dataType: 'json',
                    type: "GET",
                    delay: 250,
                    data: { inst_id : instId }

            }).done(function (response) {
                var courses = response.items;

                if (courses){
                    self.courseSelect.append(new Option('All', 0, true, true));
                    for (var i in courses ){
                        var option = new Option(courses[i].course_name, courses[i].id);
                        self.courseSelect.append(option);
                    }
                }

                if (selectedCourse){
                    self.courseSelect.val(selectedCourse);
                }

                self.courseSelect.trigger('change.select2');

            });
        },

        pe: function (r) {
            $("#change_mob").modal("hide");
            switch (r.status) {
                case 0:
                    notyfy({
                        text: "Error : " + r.message,
                        layout: "top",
                        type: "error",
                        dismisQueue: true,
                        timeout: 7000
                    });
                    break;
                case 10:
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                case 17:
                case 18:
                    notyfy({
                        text: "Error : " + r.message,
                        layout: "top",
                        type: "error",
                        dismisQueue: true,
                        timeout: 7000
                    });
                    return;
                default:
                    notyfy({text: "Error : " + r.message, layout: "top", type: "error", dismisQueue: true, timeout: 7000});
                    return;
            }
        }
    });
}));

$(document).ready(function () {
    $('#institute_users_list_controller').instituteUsersList();
});