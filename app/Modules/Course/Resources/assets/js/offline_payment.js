;if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.offlinePayment", {
        _create: function () {
            var self = this;

            self.instituteList = $('#institute-id');
            self.instituteUrl = self.instituteList.data('action');
            self.courseList = $("#course-id");
            self.offlineForm = $("#form_create_offline_payment");
            self.dateFrom = $('#date_from');
            self.dateTo = $('#date_to');
            self.btnSubmit = $('#submit');

            window.i18next.on('initialized', function () {
                window.i18next.loadNamespaces('course', function () {
                    $.proxy(self._initComponents, self)();
                    $.proxy(self._bindEvents, self)();
                });
            });
        },
        _initComponents: function () {
            var self = this;
            var i18next = window.i18next;
            var dateFormat = i18next.t('config:datetime.input_date_format').toLowerCase();
            $("#date_from").bdatepicker({
                format: dateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn: true
            }).on('changeDate', function () {
                var selected = $('#date_from').val();
                var dt = dateWrapper(selected, dateFormat, true);
                dt.add(1, 'day');
                $("#date_to").bdatepicker('setStartDate', dt.format(dateFormat));
            });

            $("#date_to").bdatepicker({
                format: dateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn: true
            }).on('changeDate', function () {
                var selected = $('#date_to').val();
                var dt = dateWrapper(selected, dateFormat, true);
                dt.subtract(1, 'day');
                $("#date_from").bdatepicker('setEndDate', dt.format(dateFormat));
            });
            $("#cheque_date").bdatepicker({
                format: dateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn: true
            });
            $('[data-tooltip="true"]').tooltip();
        },
        _bindEvents: function () {
            var self = this;

            // toggle for course code list show/ hide
            $(".toggle").each(function(index, element){
                $(this).parent().find("div").hide();
                $(this).on("click", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).parent().find("div").toggle();
                });
            });

            // Validate offline payment form
            self.offlineForm.validate({
                ignore: ":hidden",
                rules: {
                    institute_id: "required",
                    course_id: "required",
                    total_quantity: {
                        required: true,
                        number :  true
                    },
                    member_list:{
                        required: true,
                        minlength:1,
                        maxlength:100
                    },
                    cheque_amount: {
                        required: true,
                        number :  true
                    },
                    cheque_no: {
                        required: true
                    },
                    cheque_date: {
                        required: true,
                        date :  true
                    },
                    bank_name: "required",
                    branch_name: "required",
                    billing_name: "required",
                    billing_city: "required",
                    billing_pincode: {
                        required: true,
                        number :  true,
                        minlength:6,
                        maxlength:6
                    },
                    billing_phone: {
                        required: true,
                        number :  true,
                        minlength:10,
                        maxlength:10
                    },
                    billing_address: "required",
                    billing_state: "required",
                    billing_email: {
                        required: true,
                        email :  true
                    }
                },
                submitHandler:function(form) {
                    self.btnSubmit.find('.fa').remove();
                    self.btnSubmit.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });

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
            }).on("change", function () {

                self.courseList.empty();
                self.courseList.append(new Option('-- ' + window.i18next.t('promotion:course_placeholder') + ' --', '', true, true));
                // Fill course list depend of selected institute
                if (this.value !== '') {
                    $.ajax({
                        url: laroute.route('api.services.suggest.institute-courses'),
                        dataType: 'json',
                        data: {
                            'inst_id': this.value,
                            'for': 'offlinePayment'
                        },
                        success: function (data) {
                            if (data.status == 1) {
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
            // Delete offline payment record
            $(".btn-delete").length && $(".btn-delete").on("click", function(e){
                e.preventDefault();
                var self = $(this);
                var url = self.data('data-action');
                console.log(url);
                bootbox.confirm({
                    size: "small",
                    message: "Are you sure?",
                    title: self.data("modal-title"),
                    callback: function (r) {
                        if(r) {
                            if(self.attr("data-target") && self.attr("data-target") == "self") {
                                window.location = self.attr("data-action");
                            } else {
                                window.open(self.attr("data-action"), '_blank');
                            }
                        }
                    }
                })
            });
            // Export offline payment records
            $(".export_offline_coupon").length && $(".export_offline_coupon").on("click", function(e){
                e.preventDefault();
                var self = $(this);
                var url = self.data('data-action');
                console.log(url);
                bootbox.confirm({
                    size: "small",
                    message: "Are you sure?",
                    title: self.data("modal-title"),
                    callback: function (r) {
                        if(r) {
                            window.location = self.attr("data-action");
                        }
                    }
                })
            });
        },

        // Format the course list get through select2 auto suggest
        formatInstituteList: function (data) {
            if (data.loading) return data.text;

            return data.user_school_name;
        },

        // Change the key of name of the select2
        showInstituteSelection: function (data) {
            return _.has(data, "user_school_name") ? data.user_school_name : data.text;
        }
    });
}));

$(document).ready(function () {
    $("#offline-payment-container").offlinePayment();
});