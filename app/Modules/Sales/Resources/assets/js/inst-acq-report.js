;if (typeof $.fn.bdatepicker == 'undefined')
{
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
}

(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.instAcqReport", {
        _create:function() {
            var self = this;

            window.i18next.on('initialized', function () {
                window.i18next.loadNamespaces('acqreport', function(){
                    $.proxy(self._initComponents, self)();
                    $.proxy(self._bindEvents, self)();
                });
            });
        },
        _initComponents:function() {
            var self = this;

            self.totalPostValue =  $("#total_post_value");
            self.dateFrom = $("#date_from");
            self.dateTo = $("#date_to");
            self.formAcquisition =  $("#form_acquisition");
            self.btnSearch = $("#button_search");
            self.btnExport = $("#button_export");
            self.tooltip =  $('[data-tooltip="true"]');
            self.instituteList = $('#course-user-id');
            self.instituteUrl = self.instituteList.data('action');
            self.spinMe = $(".spin-me");
            self.refBy = $("#ref_by");
            self.dateFormat = window.i18next.t('config:datetime.input_date_format').toLowerCase();
            self.totalPostValue.inputmask({"mask": "9999999","placeholder": ""});
            self.dateFrom.bdatepicker({
                format: self.dateFormat,
                todayBtn: true,
                autoclose: true,
                endDate: '0'
            }).on('changeDate', function(e){
                var minDate = self.dateFrom.bdatepicker('getDate');
                self.dateTo.bdatepicker('setStartDate', minDate).trigger('changeDate');
                self.dateTo.bdatepicker('toggleActive',true);
            });
            self.dateTo.bdatepicker({
                format: self.dateFormat,
                todayBtn: true,
                autoclose: true,
                endDate: '0'
            }).on('changeDate', function(e){
               var maxDate = self.dateTo.bdatepicker('getDate');
               self.dateFrom.bdatepicker('setEndDate', maxDate);
            });
            self.tooltip.tooltip();
        },
        _bindEvents:function() {
            var self = this;
            var i18next = window.i18next;

            //Validate form on change
            self.refBy.on('change', function (e) {
                self.formAcquisition.valid();
            });

            // Auto suggest the institution list using select2
            self.instituteList.select2({
                minimumInputLength: 2,
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
            });

            self.btnSearch.on("click",function(){
                self.btnSearch.addClass('spin-me');
            });

            self.formAcquisition.validate({
                ignore: [null, ":hidden"],
                rules: {
                    "ref_by[]": "required",
                    "post_value": {
                        min: 0,
                        number: true
                    }
                },
                messages: {
                    post_value: i18next.t('acqreport:validation.post_value_zero', {})
                },
                errorClass: "error-visit",
                submitHandler:function(form) {
                    self.spinMe = $(".spin-me");

                    if( self.spinMe.length > 0 )
                    {
                        self.spinMe.find(".fa").remove();
                        self.spinMe.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                        self.btnExport.addClass("disabled").attr("disabled", "disabled");
                    }
                    form.submit();
                },
                invalidHandler: function(form){
                    self.spinMe.removeClass("spin-me");
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
    $("#inst_acq_report").instAcqReport();
});