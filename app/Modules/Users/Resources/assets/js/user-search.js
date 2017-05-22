/**
 * Created by flinnt-php-6 on 6/1/17.
 */

(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.userSearch", {
        options: {},
        _create: function () {
            var self = this;

            self.searchUserForm = $("#searchUserForm", this.element);
            self.searchButton = $("#bsearch", this.element);

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
            var i18next = window.i18next;
            var newPassword = $("#new_pwd");
            var resetPassword = $("#btn_reset_password");
            var userRemark = $('#user_remark');
            var addRemark = $("#btn_add_remark");

            // Validate the user search form
            self.searchUserForm.validate({
                ignore: ":hidden, null",
                rules: {}
            });

            // BEGIN User search form submit click
            self.searchUserForm.on("submit", function () {
                self.searchButton.find(".fa").remove();
                self.searchButton.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
            });
            // END User search form submit click

            // BEGIN Change password button click
            $("#change_pwd").on("show.bs.modal", function (e) {

                var caller = $(e.relatedTarget);
                newPassword.val("");
                self.inst_user_row = caller.closest("tr");
                resetPassword.attr('data-url', caller.attr("data-url"));

                newPassword.off("keydown").on("keydown", function (e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                })
            }).on("shown.bs.modal", function (e) {
                try {
                    newPassword.focus();
                } catch (e) {
                }
            }).on('hidden.bs.modal', function (e) {
                $(this).data('bs.modal', null);
            });
            // END Change password button click

            // BEGIN Reset password button click
            resetPassword.on("click", function (e) {
                e.preventDefault();

                // Password length must be greater than 6 length
                if (newPassword.val().length < 6) {
                    bootbox.alert(i18next.t('message:validation.min.string', {attribute:'Password', min:6}));
                    return false;
                }

                /**
                 * pdata : Request Data
                 * phtml : black html of modal, used to set after request
                 * url : get the request url
                 */
                var pdata = {"pwd": newPassword.val(), "target": "pwd"},
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
                    success: function (resp, status, xhr) {
                        if (!_.has(resp, "status")) return;
                        var status = resp.status;
                        if (parseInt(status) == 1) {
                            if (self.inst_user_row != null) {
                                $(self.inst_user_row).fadeOut(500).fadeIn(1000);
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
                        resetPassword.removeAttr("disabled");
                        resetPassword.html(phtml);
                    }
                })
            });
            // END Reset password button click

            // BEGIN User remark button modal
            userRemark.on("show.bs.modal", function (e) {
                var caller = $(e.relatedTarget);
                $("#rmrk_text").val("");
                addRemark.attr('data-url',caller.attr("data-url"));
            }).on("shown.bs.modal", function (e) {
                try {
                    $("#rmrk_text").focus();
                } catch (e) {
                }
            }).on('hidden.bs.modal', function (e) {
                $(this).data('bs.modal', null);
            });
            // END User remark button click

            // BEGIN Add remark button click
            addRemark.on("click", function (e) {
                e.preventDefault();
                var remarkText = $("#rmrk_text");

                // Remarks text must be 2 characters long
                if (remarkText.val().length < 2) {
                    bootbox.alert(i18next.t('message:validation.min.string', {attribute:'Remark', min:2}));
                    return false;
                }

                /**
                 * pdata : Request Data
                 * phtml : black html of modal, used to set after request
                 * url : get the request url
                 */
                var pdata = {"remark": remarkText.val(), "user": $("#hdnuserrmrk").val(), "target": "remark"},
                    phtml = $(this).html(),
                    url = $(this).attr("data-url");

                $(this).attr("disabled", "disabled");
                $(this).prepend(spinner);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: pdata,
                    datatype: 'json',
                    success: function (resp, status) {
                        if (!_.has(resp, "status")) return;
                        var status = resp.status;
                        if (parseInt(status) == 1) {
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
                        $("#hdnuserrmrk").val("");
                        userRemark.modal("hide");
                        addRemark.removeAttr("disabled");
                        addRemark.html(phtml);
                    }
                })
            });
            // BEGIN Add remark button click

        },
        pe: function (r) {
            $("#change_mob").modal("hide");
            switch (r.code) {
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


$(document).ready(function (e) {
    $("#user-search-controller").userSearch();
});