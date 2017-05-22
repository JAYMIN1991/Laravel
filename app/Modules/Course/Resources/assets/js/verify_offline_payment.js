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

            self.dateFrom = $('#st_date');
            self.dateTo = $('#et_date');
            self.markReturnfrm = $('#mark_return_cancel');
            self.saveStatus = $('#btnsavepaystatus');
            self.btnSubmit = $('#submit');
            self.mark_return_cancel = $("#mdl_mark_return_cancel");

            window.i18next.on('initialized', function () {
                window.i18next.loadNamespaces('course', function () {
                    $.proxy(self._initComponents, self)();
                    $.proxy(self._bindEvents, self)();
                    $.proxy(self._initModalWindows, self)();
                });
            });
        },
        _initComponents: function () {
            var self = this;
            var i18next = window.i18next;
            var dateFormat = i18next.t('config:datetime.input_date_format').toLowerCase();
            self.dateFrom.bdatepicker({
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

            self.dateTo.bdatepicker({
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
            $(".toggle").each(function (index, element) {
                $(this).parent().find("div").hide();
                $(this).on("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).parent().find("div").toggle();
                });
            });

            self.markReturnfrm.validate({
                ignore: ":hidden",
                rules: {
                    return_cancel: "required",
                    reason: "required"
                },
                submitHandler: function (form) {
                    self.saveStatus.find('.fa').remove();
                    self.saveStatus.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });

            // Generate Coupon
            $("#generateInvoices").length && $("#generateInvoices").on("click", function (e) {
                e.preventDefault();
                var self = $(this);
                bootbox.confirm({
                    size: "small",
                    message: "Are you sure?",
                    title: self.data("modal-title"),
                    callback: function (r) {
                        if (r) {
                            if (self.attr('data-target') && self.attr('data-target') == 'self') {
                                self.url = self.attr('data-action');
                                var explodeArray = self.url.split('/');
                                console.log(explodeArray);
                                //var toDo = explodeArray[6];
                                var offlinePaymentId = explodeArray[7];
                                var user_id = explodeArray[8];
                                var to_do = explodeArray[9];

                                $.ajax({
                                    url: self.url,
                                    dataType: 'json',
                                    type: 'GET',
                                    delay: 250,
                                    data: {
                                        id: offlinePaymentId,
                                        user_id: user_id,
                                        to_do: to_do
                                    }

                                }).done(function (response) {
                                    var orderData = response;
                                    console.log(orderData);
                                    if (orderData.status == 1) {
                                        var msg = window.i18next.t('course:success.generate_offline_invoice');
                                        notyfy({
                                            text: msg,
                                            layout: 'top',
                                            type: 'success',
                                            dismissQueue: true,
                                            timeout: 5000,
                                            events: {
                                                shown: function (e) {
                                                    $.notyfy.closeAll();
                                                    $.notyfy.clearQueue();
                                                }
                                            },
                                            showEffect: function (bar) {
                                                bar.animate({height: 'toggle'}, 0, 'swing');
                                                location.reload();
                                            },
                                            hideEffect: function (bar) {
                                                bar.animate({height: 'toggle'}, 0, 'swing');
                                            }
                                        });
                                    }
                                });
                            } else {
                                window.open(self.attr('data-action'), '_blank');
                            }
                        }
                    }
                })
            });
            $("#btnsavepaystatus").on("click", function (e) {
                $.proxy(self._saveReturnCancelStatus, self)();
            });
        },
        _initModalWindows: function () {

            var self = this;
            self.mark_return_cancel = $("#mdl_mark_return_cancel");

            self.mark_return_cancel.on("show.bs.modal", function (e) {
                var caller = $(e.relatedTarget);
                $("#hdpaymentid").val(caller.attr("data-id"));
            }).on("shown.bs.modal", function (e) {
            }).on('hidden.bs.modal', function (e) {
                $(this).data('bs.modal', null);
            });

        },
        _saveReturnCancelStatus: function () {
            var self = $(this);
            self.mark_return_cancel = $("#mdl_mark_return_cancel");

            var offline_payment_id_value = $("#hdpaymentid").val();
            var return_cancel = $('input[name=return_cancel]:checked').val();
            var url = self.mark_return_cancel.attr('data-action');

            if (isNaN(offline_payment_id_value) || offline_payment_id_value <= 0) {
                bootbox.alert(window.i18next.t('course:error.invalid_offline'));
                return !1;
            }

            if (!return_cancel) {
                bootbox.alert(window.i18next.t('course:error.select_return_cancel'));
                return !1;
            }

            if ($("#reason").val() == "") {
                bootbox.alert(window.i18next.t('course:error.enter_reason'));
                return !1;
            }

            var pdata = {
                "offline_payment_id": offline_payment_id_value,
                "return_cancel": return_cancel,
                "reason": $("#reason").val(),
                "action": "save_return_cancel_status"
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: pdata,
                datatype: 'json'
            })
                .done(function (resp, status, xhr) {
                    if (resp.state == 1) {
                        var msg = window.i18next.t('course:success.submit_success');
                        notyfy({
                            text: msg, layout: 'top', type: 'success', dismissQueue: true, timeout: 5000,
                            events: {
                                shown: function (e) {
                                    $.notyfy.closeAll();
                                    $.notyfy.clearQueue();
                                }
                            },
                            showEffect: function (bar) {
                                bar.animate({height: 'toggle'}, 0, 'swing');
                                //location.reload(); // Keep this code
                            },
                            hideEffect: function (bar) {
                                bar.animate({height: 'toggle'}, 0, 'swing');
                            }
                        });
                    }
                    else {
                        var msg = window.i18next.t('course:error.error_while_deleting_coupons');
                        notyfy({
                            text: msg, layout: 'top', type: 'error', dismissQueue: true, timeout: 5000,
                            events: {

                                shown: function (e) {
                                    $.notyfy.closeAll();
                                    $.notyfy.clearQueue();
                                }
                            },
                            showEffect: function (bar) {
                                bar.animate({height: 'toggle'}, 0, 'swing');
                                //location.reload(); // keep this code
                            },
                            hideEffect: function (bar) {
                                bar.animate({height: 'toggle'}, 0, 'swing');
                            }
                        });
                    }
                    self.mark_return_cancel.modal("hide");
                })
                .fail(function (xhr, status, err) {
                    self.mark_return_cancel.modal("hide");
                    var msg = window.i18next.t('course:error.error_while_deleting_coupons');
                    notyfy({
                        text: msg, layout: 'top', type: 'error', dismissQueue: true, timeout: 5000,
                        events: {

                            shown: function (e) {
                                $.notyfy.closeAll();
                                $.notyfy.clearQueue();
                            }
                        },
                        showEffect: function (bar) {
                            bar.animate({height: 'toggle'}, 0, 'swing');
                            //location.reload();
                        },
                        hideEffect: function (bar) {
                            bar.animate({height: 'toggle'}, 0, 'swing');
                        }
                    });
                })
                .always(function (xhr, status, err) {
                })
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
    $("#verify-offline-payment-container").offlinePayment();
});