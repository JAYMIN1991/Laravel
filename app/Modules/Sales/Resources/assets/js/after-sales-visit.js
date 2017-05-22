;if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.visitWidget", {
        _create: function () {
            var self = this;
            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents: function () {
            var self = this;
            self.visitDateContainer = $("#visit_date_container");
            self.visitDate = $("#visit_date");
            self.studentStrength = $("#student_strength");
            self.contactPerson = $("#contact_person");
            self.contactPersonPhone = $("#contact_person_phone");
            self.contactPersonEmail = $("#contact_person_email_id");
            self.contactPersonDesignation = $("#contact_person_desig");
            self.autosearchFields = $(".autosearch_field");
            self.institute = $("#inst_user_id");
            self.instituteAjaxURI = self.institute.attr('data-autosuggesturl');
            self.selectedInstituteId = self.institute.data("selected-inst-id");
            self.postVisitListForm = $("#form_post_inst_call_visit");
            self.btnSave = $("#button_save");
            self.btnCancel = $("#button_cancel");
            self.contactPersonPhone.inputmask({"mask": "9999999999", "placeholder": ""});
            self.studentStrength.inputmask({"mask": "99999", "placeholder": ""});
            self.visitDate.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                defaultDate: '0',
                todayBtn: true,
                autoclose: true,
                endDate: '0'
            });
        },
        _bindEvents: function () {
            var self = this;

            /* Auto-complete event */
            self.autosearchFields.autocomplete({
                source: function (request, response) {
                    var autoSuggestUrl = this.element.attr('data-autosuggesturl');
                    $.ajax({
                        url: autoSuggestUrl,
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function (data) {
                            if (data.status == 1) {
                                response(data.items);
                            }
                        }
                    });
                },
                minLength: 2
            });

            /* getting institute list through ajax */
            self.institute.select2({
                ajax: {
                    url: self.instituteAjaxURI,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term, // search term
                            selected_inst_id: self.selectedInstituteId
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: self.formatUserList,
                templateSelection: self.showUserSelection
            });

            /* Fill last after sales visit contact details on change of institute */
            self.institute.on("change", function(){

                if(this.value != 0 )
                {
                    $.ajax({
                        url: laroute.route('api.sales.last-post-visit-of-institute', {'id' : this.value }),
                        dataType: 'json',
                        success: function(response) {

                            if(response.status ==1  && response.data['after_sales_visit'] != undefined )
                            {
                                var afterSalesVisit = response.data['after_sales_visit'];
                                self.contactPerson.val(afterSalesVisit.contact_person);
                                self.contactPersonPhone.val(afterSalesVisit.contact_person_phone);
                                self.contactPersonDesignation.val(afterSalesVisit.contact_person_desig);
                                self.contactPersonEmail.val(afterSalesVisit.contact_person_email_id);
                            }
                            else {
                                /* No last contact details found, so clear inputs */
                                self.contactPerson.val('');
                                self.contactPersonPhone.val('');
                                self.contactPersonDesignation.val('');
                                self.contactPersonEmail.val('');
                            }
                            /* revalidate form after autofill */
                            self.postVisitListForm.valid();
                        }
                    });
                }
            });

            /* getting list through ajax */
            self.postVisitListForm.validate({
                ignore: ":hidden",
                rules: {
                    visit_date: "required",
                    contact_person: "required",
                    contact_person_desig: "required",
                    contact_person_phone: "required",
                    contact_person_email_id: {
                        required: false,
                        email: true
                    },
                    inst_user_id: {
                        required: {
                            depends: function () {
                                return self.institute.length !== 0;
                            }
                        }
                    }
                }
                , errorPlacement: function (error, element) {
                    error.insertAfter(element);
                    if (element.attr("id") == 'visit_date') {
                        error.insertAfter(element.parent("div"));
                    }
                    if (element.attr("id") == 'inst_user_id') {
                        error.insertAfter(element.parent("div"));
                    }
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
        },
        formatUserList: function (userData) {
            return (userData.loading) ? userData.text : userData.user_school_name;
        },
        showUserSelection: function (userData) {
            return _.has(userData, "user_school_name") ? userData.user_school_name : userData.text;
        }
    });
}));
$(document).ready(function () {
    $("#post_visit_container").visitWidget();
});