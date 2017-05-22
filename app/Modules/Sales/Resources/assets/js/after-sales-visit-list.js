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
            self.tooltip = $('[data-tooltip="true"]');
            self.instituteList = $("#inst_user_id");
            self.instituteAjaxURI =  self.instituteList.attr('data-autosuggesturl');
            self.selectedInstituteId = self.instituteList.data("selected-inst-id");
            self.btnSearch = $("#button_search");
            self.btnExport = $("#button_export");
            self.postVisitListForm = $("#form_post_inst_call_visit");
            self.deleteLink = $(".delvisit");
            self.dateFrom.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                defaultDate: '0',
                todayBtn: true,
                autoclose: true,
                endDate: '0'
            }).on('changeDate', function(e){
                var minDate = self.dateFrom.bdatepicker('getDate');
                self.dateTo.bdatepicker('setStartDate', minDate).trigger('changeDate');
                self.dateTo.bdatepicker('toggleActive',true);
            });
            self.dateTo.bdatepicker({
                format: window.i18next.t('config:datetime.input_date_format').toLowerCase(),
                defaultDate: '0',
                todayBtn: true,
                autoclose: true,
                endDate: '0'
            }).on('changeDate', function(e){
                var maxDate = self.dateTo.bdatepicker('getDate');
                self.dateFrom.bdatepicker('setEndDate', maxDate);
            });
            self.tooltip.tooltip();
            self.spinMe = $(".spin-me");
        },
        _bindEvents:function() {
            var self = this;

            /* getting institute list through ajax */
            self.instituteList.select2({
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
                escapeMarkup: function(markup) { return markup; },
                templateResult: self.formatUserList,
                templateSelection: self.showUserSelection
            });

            self.btnSearch.on("click",function(){
                self.btnSearch.addClass('spin-me');
            });

            self.postVisitListForm.on("submit", function() {
                self.spinMe = $(".spin-me");

                if( self.spinMe.length > 0 )
                {
                    self.spinMe.find(".fa").remove();
                    self.spinMe.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    self.btnExport.addClass("disabled").attr("disabled", "disabled");
                }
            });
        },
        formatUserList: function(userData) {
            return  (userData.loading) ? userData.text : userData.user_school_name;
        },
        showUserSelection: function(data) {
            return _.has(data, "user_school_name") ? data.user_school_name : data.text;
        }
    });
}));

$(document).ready(function(){
    $("#post_visit_list_container").visitList();
});