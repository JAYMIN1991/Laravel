;if (typeof $.fn.bdatepicker == 'undefined') {
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
}
(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.instCallVisit", {
        _create:function() {
            var self = this;

            window.i18next.on('initialized', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents:function() {
            var self = this;
            self.instituteName = $("#institute_name");
            self.instituteNameContainer = $("#institute_name_container");
            self.instituteDropdownContainer = $("#institute_dropdown_container");
            self.studentStrength = $("#student_strength");
            self.visitDateContainer = $("#visit_date_container");
            self.instCallVisitFrom =  $("#form_institute_call_visit");
            self.saveButton = $("#button_save");
            self.instituteType = $("#institute_type");
            self.instituteInquiryId = $("#inst_inquiry_id");
            self.autosearchFields = $(".autosearch_field");
            self.instituteCategoryId = $("#inst_category_id");
            self.address = $("#address");
            self.city =  $("#city");
            self.stateId = $("#state_id");
            self.contactPerson = $("#contact_person");
            self.contactPersonPhone =  $("#contact_person_phone");
            self.contactPersonDesig = $("#contact_person_desig");
            self.contactPersonEmail = $("#contact_person_email_id");

            /* Initialize inputmask and datepicker */
            self.studentStrength.inputmask({"mask": "99999","placeholder": ""});
            self.contactPersonPhone.inputmask({"mask": "9999999999","placeholder": ""});
            self.visitDateContainer.bdatepicker({format: window.i18next.t('config:datetime.input_date_format').toLowerCase(), defaultDate: '0', todayBtn: true, autoclose: true, endDate: '0'});
        },
        _bindEvents:function() {
            var self = this;

            /* Autocomplete event binding for all fields having class autosearch_field */
            self.autosearchFields.autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: this.element.attr('data-autosuggesturl'),
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            if(data.status == 1) {
                                response(data.items);
                            }
                        }
                    });
                },
                minLength: 2 // Search after two characters
            });

            /* Validate method that will be used in edit and create method of institute call visit */
            self.instCallVisitFrom.validate({
                ignore: ":hidden",
                rules: {
                    visit_date: "required",
                    inst_category_id: "required",
                    student_strength: "required",
                    address: "required",
                    city: "required",
                    state_id: "required",
                    contact_person : "required",
                    contact_person_desig : "required",
                    contact_person_phone : "required",
                    institute_name: "required",
                    inst_inquiry_id: "required",
                    contact_person_email_id: {
                        required: false,
                        email :  true
                    }
                },
                errorPlacement:function(error, element) {
                        error.insertAfter(element);

                        if( element.attr("id") == 'visit_date' ){
                            error.insertAfter(element.parent("div"));
                        }
                },
                errorClass: "error-visit",
                showErrors: function() {
                    this.defaultShowErrors();
                },
                submitHandler:function(form) {
                    self.saveButton.find('.fa').remove();
                    self.saveButton.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });

            self.instituteType.on("change", function(){
                self.instCallVisitFrom.validate({ errorPlacement: function(error, element) {} });
                if(this.value == 1)
                {
                    self.instituteNameContainer.show();
                    self.instituteDropdownContainer.hide();
                }
                else
                {
                    self.instituteNameContainer.hide();
                    self.instituteDropdownContainer.show();
                }
            });

            /* Fill institute related details on change of institute */
            self.instituteInquiryId.on("change", function(){
                if(this.value !== '')
                {
                    $.ajax({
                        url: laroute.route('api.sales.not-acquired-institute', {'id' : this.value }),
                        dataType: 'json',
                        success: function(data) {
                            if(data.status ==1)
                            {
                                data = data.items;
                                self.instituteCategoryId.val(data.inst_category_id).trigger('change');
                                self.address.val(data.address);
                                self.studentStrength.val(data.student_strength);
                                self.city.val(data.city);
                                self.stateId.val(data.state_id).trigger('change');

                                self.contactPerson.val(data.contact_person);
                                self.contactPersonDesig.val(data.contact_person_desig);
                                self.contactPersonPhone.val(data.contact_person_phone);
                                self.contactPersonEmail.val(data.contact_person_email_id);

                                self.instCallVisitFrom.valid();
                            }
                        }
                    });
                }
            });

            self.instituteCategoryId.on("change", function () {
                self.instCallVisitFrom.valid();
            });
            self.instituteInquiryId.on("change", function () {
                self.instCallVisitFrom.valid();
            });
            self.stateId.on("change", function () {
                self.instCallVisitFrom.valid();
            });
        }
    });
}));

$(function(){
    $("#inst_visit_container").instCallVisit();
});