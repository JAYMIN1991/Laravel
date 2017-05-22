/**
 * Created by flinnt-php-6 on 23/2/17.
 */

;if (typeof $.fn.bdatepicker == 'undefined') {
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
}

(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';

    $.widget('flinnt.instituteList', {
        options: {},
        _create: function () {
            var self = this;

            self.instituteList = $('#institute_id', this.element);
            self.instituteListUrl = self.instituteList.data('action');

            window.i18next.on('loaded', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
                $.proxy(self._initModalWindows, self)();
            });
        },
        _initComponents: function () {
            var i18next = window.i18next;
            var dateFormat = i18next.t('config:datetime.input_date_format');
            var lowerDateFormat = dateFormat.toLowerCase();

            $("#st_date").bdatepicker({
                format: lowerDateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn:true
            }).on('changeDate', function () {
                var selected = $('#date_from').val();
                var dt = dateWrapper(selected, dateFormat, true);
                $("#et_date").bdatepicker('setStartDate', dt.format(dateFormat));
            });

            $("#et_date").bdatepicker({
                format: lowerDateFormat,
                todayBtn: false,
                autoclose: true,
                endDate: '0',
                clearBtn:true
            }).on('changeDate', function () {
                var selected = $('#date_to').val();
                var dt = dateWrapper(selected, dateFormat, true);
                $("#st_date").bdatepicker('setEndDate', dt.format(dateFormat));
            });
        },
        _bindEvents: function () {
            var self = this;
            var planButton = $(".cplan");
            // Auto suggest the institution list using select2
            var instituteSelect = self.instituteList.select2({
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: self.instituteListUrl,
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
            });

            // On post back get the source institute id and name and pre-fill the course list
            var selectedInstituteId = self.instituteList.data('old-input');
            var selectedInstituteName = self.instituteList.data('institute-name');
            if (selectedInstituteId)
            {
                // @todo :: Create function to append option to select
                var sourceOption = new Option(selectedInstituteName, selectedInstituteId, true, true);
                instituteSelect.append(sourceOption).trigger('change.select2');
            }

            planButton.length && planButton.on("click", function(e){
                e.preventDefault();
                var that = $(this);
                bootbox.confirm({
                    size: "small",
                    message: "Are you sure?",
                    title: that.data("modal-title"),
                    callback: function (r) {
                        if(r) {
                            if(that.attr("data-target") && that.attr("data-target") == "self") {
                                $.ajax({
                                    type: 'POST',
                                    url: that.attr('data-action'),
                                    datatype: 'json'
                                })
                                .done(function(resp, status) {
                                    if(!_.has(resp, "status")) return;
                                    if(parseInt(resp.status) == 1) {
                                        notyfy({
                                            text: resp.message,
                                            layout: "top",
                                            type: "success",
                                            dismisQueue: true,
                                            timeout: 2000
                                        });

                                        setTimeout(function () {
                                            location.reload();
                                        }, 2100)
                                    }
                                })
                                .fail(function(xhr) {
                                    var j = xhr.responseText;
                                    try {
                                        j = JSON.parse(j);

                                        if (j.hasOwnProperty('errors')) {
                                            if (j.errors[0].code == 104) {
                                                location.reload();
                                            } else if (j.errors[0].code == 105){
                                                location.reload();
                                            }
                                        }
                                    }catch(e) {
                                        return;
                                    }

                                    $.proxy(self._showError, self, j)();
                                })
                                .always(function(xhr, status, err) {
                                })
                            }
                        }
                    }
                })
            });

            // Bind the button click event of modal with method
            $("#btnsaveref").on("click", function(){
                $.proxy(self._setInstRefBy, self, this)();
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

        // Initialize the modals
        _initModalWindows:function() {
            var self = this;
            $("#verify_plan").on("show.bs.modal", function(e){
                var caller = $(e.relatedTarget);
                $("#btnverify").attr('data-action', caller.attr("data-action"));
            }).on("shown.bs.modal", function(){
                $("#vrfy_remarks").val("").focus();
            }).on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });

            $("#cancel_plan").on("show.bs.modal", function(e){
                var caller = $(e.relatedTarget);
                $("#btncancel").attr('data-action', caller.attr("data-action"));
            }).on("shown.bs.modal", function(){
                $("#cnl_remarks").val("").focus();
            }).on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });

            self.change_ref_modal = $("#change_ref_by");
            self.change_ref_modal.on("show.bs.modal", function(e){

                var caller = $(e.relatedTarget);
                var btnSaveRef = $("#btnsaveref");
                btnSaveRef.attr('data-edit-url', caller.attr('data-edit-url'));

                $.proxy(self._fillChangeByRefModal, self, caller)();

                console.log(btnSaveRef.attr('data-autosuggest'));
                /* Autocomplete in ref by modal box for city text box */
                $(".autosearch_field").autocomplete({
                    source: function(request, response) {
                        var autoSuggestUrl = btnSaveRef.attr('data-autosuggest');
                        $.ajax({
                            url: autoSuggestUrl,
                            dataType: "json",
                            data: {
                                term: request.term
                            },
                            success: function(data) {

                                if (_.has(data, 'status') && data['status'] == 1) {
                                    response(data.items)
                                }
                            }
                        });
                    },
                    minLength: 2//search after two characters
                });
                $(".ui-autocomplete").css("z-index",'99999999');


            }).on("shown.bs.modal", function(e){
            }).on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });

            $("#btnverify").on("click", function(e) {
                e.preventDefault();
                var caller = $(this);
                $.proxy(self._verifyPlan, self, caller)();
            });

            $("#btncancel").on("click", function(e) {
                e.preventDefault();
                $.proxy(self._cancelPlan(), self)();
            });
        },

        // Get the data for ref by edit
        _fillChangeByRefModal:function(caller) {
            var self = this,
                durl = caller.attr("data-url"),
                saleBy = $("#sale_by"),
                category  = $("#inst_category");
            $.ajax({
                type: 'GET',
                url: durl,
                datatype: 'json'
            })
                .done(function(resp) {
                    var data = resp.data;

                    if (data == null || data == undefined) {
                        return;
                    }
                    /* Setting sales team list with selecting value */
                    if(! _.has(data, "acq_member_id")) {
                        return;
                    }

                    saleBy.val(data.acq_member_id);

                    /* Setting category list with selecting value */
                    if(!_.has(data, "inst_category_id")) {
                        return;
                    }

                    category.val(data.inst_category_id);

                    /* Setting city name with filling existing value */
                    if(!_.has(data, "city")) {
                        return;
                    }

                    $("#city").val(data.city);

                })
                .fail(function(xhr) {
                    var j = xhr.responseText;
                    try {
                        j = JSON.parse(j);
                    }catch(e) {
                        return;
                    }

                    $.proxy(self._showError, self, j)();
                })
                .always(function(xhr, status, err) {
                })
        },

        // Set the institution reference by
        _setInstRefBy: function(caller) {
            var self = this,
                member = $("#sale_by").val(),
                categoryId = $("#inst_category").val(),
                city = $("#city").val();

            if(member.length <= 0) {
                bootbox.alert("You must select ref. by");
                return !1;
            }

            if(categoryId.length <= 0) {
                bootbox.alert("You must select category");
                return !1;
            }

            if(city.length<=0) {
                bootbox.alert("You must enter city");
                return !1;
            }


            var pdata = {
                    "member_id": member,
                    "category_id": categoryId,
                    "city": city,
                    "action": "set_sales_ref"
                },
                durl = $(caller).attr('data-edit-url');
            $.ajax({
                type: 'POST',
                url: durl,
                data: pdata,
                datatype: 'json'
            })
                .done(function(resp, status) {
                    self.change_ref_modal.modal("hide");
                    if(!_.has(resp, "status")) return;
                    if(parseInt(resp.status) == 1) {
                        notyfy({
                            text: resp.message,
                            layout: "top",
                            type: "success",
                            dismisQueue: true,
                            timeout: 2000
                        });
                    } else {
                        notyfy({
                            text: resp.message,
                            layout: "top",
                            type: "error",
                            dismisQueue: true,
                            timeout: 2000
                        });
                    }

                    setTimeout(function () {
                        location.reload();
                    }, 2010);
                })
                .fail(function(xhr) {
                    self.change_ref_modal.modal("hide");
                    var j = xhr.responseText;
                    try {
                        j = JSON.parse(j);
                    }catch(e) {
                        return;
                    }

                    $.proxy(self._showError, self, j)();
                })
                .always(function(xhr, status, err) {
                })
        },

        // Verify plan ajax
        _verifyPlan:function() {
            var self = this,
                caller = $("#btnverify");
            var pdata = {"remarks": $("#vrfy_remarks").val(), "action": "verify"},
                phtml = caller.html(),
                durl = caller.attr("data-action");

            caller.attr("disabled", "disabled");
            caller.prepend(spinner);
            $.ajax({
                type: 'POST',
                url: durl,
                data: pdata,
                datatype: 'json'
            })
                .done(function(resp, status) {
                    if(!_.has(resp, "status")) return;
                    if(parseInt(resp.status) == 1) {
                        notyfy({
                            text: resp.message,
                            layout: "top",
                            type: "success",
                            dismisQueue: true,
                            timeout: 2000
                        });

                        setTimeout(function () {
                            location.reload();
                        }, 2010)
                    }
                })
                .fail(function(xhr) {
                    var j = xhr.responseText;
                    try {
                        j = JSON.parse(j);
                    }catch(e) {
                        return;
                    }

                    $.proxy(self._showError, self, j)();
                })
                .always(function() {
                    $("#hdnvfuser").val("");
                    $("#mdlverifyplan").modal("hide");
                    $("#btnverify").removeAttr("disabled").html(phtml);
                });
        },

        // Cancel plan ajax
        _cancelPlan:function() {
            var self = this,
                caller = $("#btncancel");
            var pdata = {"remarks": $("#cnl_remarks").val(), "action": "cancel"},
                phtml = caller.html(),
                durl = caller.attr("data-action");

            caller.attr("disabled", "disabled");
            caller.prepend(spinner);
            $.ajax({
                type: 'POST',
                url: durl,
                data: pdata,
                datatype: 'json'
            })
                .done(function(resp, status) {
                    if(!_.has(resp, "status")) return;
                    if(parseInt(resp.status) == 1) {
                        notyfy({
                            text: resp.message,
                            layout: "top",
                            type: "success",
                            dismisQueue: true,
                            timeout: 2000
                        });

                        setTimeout(function () {
                            location.reload();
                        }, 2010)
                    }
                })
                .fail(function(xhr) {
                    var j = xhr.responseText;
                    try {
                        j = JSON.parse(j);
                    }catch(e) {
                        return;
                    }

                    $.proxy(self._showError, self, j)();
                })
                .always(function() {
                    $("#hdncnluser").val("");
                    $("#mdlcnlplan").modal("hide");
                    $("#btncancel").removeAttr("disabled").html(phtml);
                })
        },
        // Show the error
        _showError:function(r) {
            $("#extend_subscr").modal("hide");
            $("#mdlverifyplan").modal("hide");
            $("#mdlcnlplan").modal("hide");
            switch(r.code) {
                case 0:
                    notyfy({text: "Error : " + r.message, layout: "top", type: "error", dismisQueue:true, timeout: 7000});
                    break;
                case 10:
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                    notyfy({text: "Error : " + r.message, layout: "top", type: "error", dismisQueue:true, timeout: 7000});
                    return;
                default:
                    notyfy({text: "Error : " + r.message, layout: "top", type: "error", dismisQueue:true, timeout: 7000});
                    return;
            }
        }
    });
}));

$(document).ready(function () {
    $('#institute_list_controller').instituteList();
});

