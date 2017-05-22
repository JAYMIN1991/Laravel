if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.courseOrderSummary', {
        _create: function () {
            var self = this;
            self.courseOrderFrm = $('#frm_course_order', this.element);
            self.courseList = $('#course-list');
            self.courseUrl = self.courseList.data('action');
            self.instituteList = $('#institute_id');
            self.dateFrom = $('#date_from');
            self.dateTo = $('#date_to');
            self.instituteUrl = self.instituteList.data('action');
            self.courseSelect = null;

            window.i18next.on('loaded', function () {
                window.i18next.loadNamespaces('account', function () {
                    $.proxy(self._initComponents, self)();
                    $.proxy(self._bindEvents, self)();
                });
            });

        },
        _initComponents: function () {
            var self = this;
            self.dateFrom.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                defaultDate: '0',
                endDate: '0',
                todayBtn: true,
                autoclose: true
            });
            self.dateTo.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                defaultDate: '0',
                endDate: '0',
                todayBtn: true,
                autoclose: true
            });
            $('[data-tooltip="true"]').tooltip();
            self.courseSelect = self.courseList.select2();
        },
        _bindEvents: function () {
            var self = this;
            var i18next = window.i18next;

            // Validate the search form before submitting
            self.courseOrderFrm.validate({
                ignore: ':hidden,null',
                rules: {
                    inst_id: 'required' // Institute is mandatory field
                }

            });

            // Auto suggest the institution list using select2
            var instituteSelect = self.instituteList.select2({
                minimumInputLength: 2,
                ajax: {
                    url: self.instituteUrl,
                    dataType: 'json',
                    type: 'GET',
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
            }).on('change.select2', function () {
                var instId = $(this).val();
                self.fillCourseDropdown(instId);
            });

            // On post back get the institute id and name and pre-filled in course list
            var selectedInstituteId = self.instituteList.data('old-input');
            var selectedInstituteName = self.instituteList.data('institute-name');
            if (selectedInstituteId) {
                var option = new Option(selectedInstituteName, selectedInstituteId, true, true);
                instituteSelect.append(option).trigger('change.select2');
            }
        },

        // Format the course list get through select2 auto suggest
        formatInstituteList: function (data) {
            if (data.loading) return data.text;

            return data.user_school_name;
        },

        // Change the key of name of the select2
        showInstituteSelection: function (data) {
            return _.has(data, 'user_school_name') ? data.user_school_name : data.text;
        },

        // Format the course list get through select2 auto suggest
        formatCourseList: function (data) {
            if (data.loading) return data.text;

            return data.course_name;
        },

        // Change the key of name of the select2
        showCourseSelection: function (data) {
            return _.has(data, 'course_name') ? data.course_name : data.text;
        },

        // Get the courses based on selected institute and fill the courses dropdown
        fillCourseDropdown: function (instId) {
            var self = this;
            self.courseList.removeAttr('disabled');
            self.courseSelect.empty();
            // self.courseSelect.trigger('change.select2');
            var selectedCourse = self.courseList.data('old-input');
            $.ajax({
                url: self.courseUrl,
                dataType: 'json',
                type: 'GET',
                delay: 250,
                data: {inst_id: instId}

            }).done(function (response) {
                var courses = response.items;

                if (courses) {
                    self.courseSelect.append(new Option('All', '', true, true));
                    for (var i in courses) {
                        var option = new Option(courses[i].course_name, courses[i].id);
                        self.courseSelect.append(option);
                    }
                }

                if (selectedCourse) {
                    self.courseSelect.val(selectedCourse);
                }

                self.courseSelect.trigger('change.select2');

            });
        },

    });
    $('#mark_as_paid').length && $('#mark_as_paid').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        bootbox.confirm({
            size: 'small',
            message: window.i18next.t('account:custom.mark_as_paid'),
            title: self.data('modal-title'),
            callback: function (r) {
                if (r) {
                    if (self.attr('data-target') && self.attr('data-target') == 'self') {
                        self.url = self.attr('data-action');
                        var explodeArray = self.url.split('/');
                        var trans_id = explodeArray[7];

                        $.ajax({
                            url: self.url,
                            dataType: 'json',
                            type: 'GET',
                            delay: 250,
                            data: {trans_id: trans_id}

                        }).done(function (response) {
                            var orderData = response;
                            console.log(orderData);

                            self.courseSelect.trigger('change.select2');

                        });
                    } else {
                        window.open(self.attr('data-action'), '_blank');
                    }
                }
            }
        })
    });

    $('#generate_buyer_invoice').length && $('#generate_buyer_invoice').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        bootbox.confirm({
            size: 'small',
            message: window.i18next.t('account:custom.generate_buyer_invoice'),
            title: self.data('modal-title'),
            callback: function (r) {
                if (r) {
                    if (self.attr('data-target') && self.attr('data-target') == 'self') {
                        self.url = self.attr('data-action');
                        console.log(self.url);
                        var explodeArray = self.url.split('/');
                        var do_action = explodeArray[7];
                        var trans_id = explodeArray[8];
                        var user_id = explodeArray[9];
                        var is_send = explodeArray[10];

                        $.ajax({
                            url: self.url,
                            dataType: 'json',
                            type: 'GET',
                            delay: 250,
                            // data: { do_action : do_action, trans_id:trans_id, user_id:user_id, is_send:is_send }

                        }).done(function (response) {
                            var msg = window.i18next.t('account:custom.success');
                            console.log(response);
                            notyfy({
                                text: msg, layout: 'top', type: 'success', dismissQueue: true, timeout: 5000,
                                events: {

                                    shown: function (e) {
                                        $.notyfy.closeAll();
                                        $.notyfy.clearQueue();
                                    },

                                },
                                showEffect: function (bar) {
                                    bar.animate({height: 'toggle'}, 0, 'swing');
                                    location.reload();
                                },
                                hideEffect: function (bar) {
                                    bar.animate({height: 'toggle'}, 0, 'swing');
                                },
                            });
                        });
                    } else {
                        window.open(self.attr('data-action'), '_blank');
                    }
                }
            }
        })
    });

    $('#generate_seller_invoice').length && $('#generate_seller_invoice').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        bootbox.confirm({
            size: 'small',
            message: window.i18next.t('account:custom.generate_seller_invoice'),
            title: self.data('modal-title'),
            callback: function (r) {
                if (r) {
                    if (self.attr('data-target') && self.attr('data-target') == 'self') {
                        self.url = self.attr('data-action');
                        var explodeArray = self.url.split('/');
                        var do_action = explodeArray[7];
                        var trans_id = explodeArray[8];
                        var user_id = explodeArray[9];
                        var is_send = explodeArray[10];

                        $.ajax({
                            url: self.url,
                            dataType: 'json',
                            type: 'GET',
                            delay: 250,
                            // data: { do_action : do_action, trans_id:trans_id, user_id:user_id, is_send:is_send }

                        }).done(function (response) {
                            var msg = window.i18next.t('account:custom.success');
                            notyfy({
                                text: msg, layout: 'top', type: 'success', dismissQueue: true, timeout: 5000,
                                events: {

                                    shown: function (e) {
                                        $.notyfy.closeAll();
                                        $.notyfy.clearQueue();
                                    },

                                },
                                showEffect: function (bar) {
                                    bar.animate({height: 'toggle'}, 0, 'swing');
                                    location.reload();
                                },
                                hideEffect: function (bar) {
                                    bar.animate({height: 'toggle'}, 0, 'swing');
                                },
                            });
                        });
                    } else {
                        window.open(self.attr('data-action'), '_blank');
                    }
                }
            }
        })
    });

}));
$(document).ready(function () {
    $('#course_orders_container').courseOrderSummary();
});