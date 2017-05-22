(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.instCallAcq", {
        _create:function() {

            var self = this;

            window.i18next.on('initialized', function () {
                $.proxy(self._initComponents, self)();
                $.proxy(self._bindEvents, self)();
            });

        },
        _initComponents:function() {

        },
        _bindEvents:function() {

            var self = this;
            self.instituteList = $("#instlist");
            self.formAcquisition = $("#form_acquisition");
            self.btnSubmit = $("#button_save");

            /* getting list through ajax */
            self.instituteList.select2({
                ajax: {
                    url: self.instituteList.data("autosuggesturl"),
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term, // search term
                            selected_inst_id: self.instituteList.data("selected-inst-id")
                        };
                    },
                    processResults: function (data) {
                        var selectedInstitute = {'id': self.instituteList.data("selected-institute-id"),
                            'user_school_name' : self.instituteList.data("selected-institute-name")
                        };
                        var x = data.items;
                        x.unshift(selectedInstitute);
                        return {
                            results: x
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                escapeMarkup: function(markup) { return markup; },
                templateResult: self.formatUserList,
                templateSelection: self.showUserSelection
            });

            /* getting list through ajax */
            self.formAcquisition.validate({
                ignore: [null, ":hidden"],
                rules: {
                    user_id: "required"
                },
                messages:{
                }
                ,errorPlacement:function(error, element) {
                    error.insertAfter(element);
                },
                errorClass: "error-visit",
                showErrors: function() {
                    this.defaultShowErrors();
                },
                submitHandler:function(form) {
                    self.btnSubmit.find('.fa').remove();
                    self.btnSubmit.prepend(spinner).addClass("disabled").attr("disabled", "disabled");
                    form.submit();
                }
            });
        },
        formatUserList: function(data) {
            if(data.loading){
                return data.text;
            }
            return data.user_school_name;
        },
        showUserSelection: function(data) {
            return _.has(data, "user_school_name") ? data.user_school_name : data.text;
        }
    });
}));

$(document).ready(function(){
    $("#acq_container").instCallAcq();
});