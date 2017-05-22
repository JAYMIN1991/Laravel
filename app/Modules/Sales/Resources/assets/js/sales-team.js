;(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.salesTeam",{
        _create:function() {
            var self = this;

            window.i18next.on('initialized', function () {
                window.i18next.loadNamespaces('salesteam', function () {
                    $.proxy(self._initComponents, self)();
                    $.proxy(self._bindEvents, self)();
                });
            });
        },
        _initComponents:function() {
                var self = this;
                self.formSalesTeam  = $("#form_sales_team");
                self.btnSubmit =  $("#button_save");
        },
        _bindEvents:function() {
            var self = this;
            var i18next = window.i18next;

           self.formSalesTeam.validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    city_name: "required"
                },
                messages: {
                    first_name: i18next.t('salesteam:validation.first_name'),
                    last_name: i18next.t('salesteam:validation.last_name'),
                    city_name: i18next.t('salesteam:validation.city_name')
                },
                submitHandler:function(form) {
                    self.btnSubmit.find('.fa').remove();
                    self.btnSubmit.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });
        }
    })
}));

$(function() {
    $("#sales_teams_container").salesTeam();
});