if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();
(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.visitList", {
        _create:function() {
            var self = this;

            window.i18next.on('initialized', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });
        },
        _initComponents:function() {
            var self = this;
            self.dateFrom = $("#date_from");
            self.dateTo = $("#date_to");
            self.tooltip =  $('[data-tooltip="true"]');
            self.dateFrom.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                defaultDate: '0',
                endDate: '0',
                todayBtn: true,
                autoclose: true
            }).on('changeDate', function(e){
                var minDate = self.dateFrom.bdatepicker('getDate');
                self.dateTo.bdatepicker('setStartDate', minDate).trigger('changeDate');
                self.dateTo.bdatepicker('toggleActive',true);
            });
            self.dateTo.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                defaultDate: '0',
                endDate: '0',
                todayBtn: true,
                autoclose: true
            }).on('changeDate', function(e){
                var maxDate = self.dateTo.bdatepicker('getDate');
                self.dateFrom.bdatepicker('setEndDate', maxDate);
            });
            self.tooltip.tooltip();
            self.instCallVisitForm = $("#form_institute_call_visit");
            self.btnSearch = $("#button_search");
            self.btnExport = $("#button_export");
        },
        _bindEvents:function() {
            var self = this;

            self.btnSearch.on("click", function(){
                self.btnSearch.addClass('spin-me');
            });

            self.instCallVisitForm.on("submit", function() {
                self.spinMe = $(".spin-me");

                if( self.spinMe.length > 0 )
                {
                    self.spinMe.find(".fa").remove();
                    self.spinMe.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    self.btnExport.addClass("disabled").attr("disabled", "disabled");
                }
            });
        }
    });
}));

$(document).ready(function(){
    $("#visit_list_container").visitList();
});