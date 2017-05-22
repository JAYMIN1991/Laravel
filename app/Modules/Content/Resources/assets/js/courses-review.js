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
    $.widget("flinnt.reviewCourse", {
        _create:function() {
            var self = this;

            /*  filter variables */
            self.instituteList = $('#institute');
            self.instituteUrl = self.instituteList.data('action');
            self.dateFrom = $("#date_from");
            self.dateTo = $("#date_to");

            /* institute detail toggle */
            self.instituteToggle = $(".toggle"); 
            
            self.modalReviewHistory = $("#review_history_model");
            self.modelComment = $("#comment_modal");

            /* change status modal */
            self.modalChangeStatus = $("#change_status_modal");
            self.statusList = $("#status_list");
            self.remarks = $("#remarks");
            self.courseId = $("#course_id");
            self.btnChangeStatus = $("#button_change_status");

            /* Register i18Next */
            window.i18next.on('initialized', function () {
                window.i18next.loadNamespaces('content-review', function(){
                    $.proxy(self._bindInputControls, self)();
                    $.proxy(self._bindEvents, self)();
                    $.proxy(self._prepareChangeStatModal, self)();
                    $.proxy(self._prepareHistoryModal, self)();
                });
            });
        },
        _bindEvents:function() {
            var self = this;
            /* Change status submit */
            self.btnChangeStatus.on("click", function(e){
                $.proxy(self._changeStatus, self, e)();
            });

            /* Auto suggest the institution list using select2 */
            self.instituteList.select2({
                minimumInputLength: 2,
                ajax: {
                    url: self.instituteUrl, // Institute search url
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

            /* Show comment in popup */
            self.modelComment.on('show.bs.modal', function (event) {
                /* get comment id from clicked comment link */
                var commentId = $(event.relatedTarget).data('comment-id');
                $(this).find('.modal-body').html( $("#comment-" + commentId ).html() );
            });
        },
        _bindInputControls:function() {
            var self = this;
            /* Register date-picker */
            self.dateFrom.bdatepicker({format: window.i18next.t('config:datetime.input_date_format').toLowerCase(), todayBtn: true, autoclose: true});
            self.dateTo.bdatepicker({format: window.i18next.t('config:datetime.input_date_format').toLowerCase(), todayBtn: true, autoclose: true});

            /* Register institute toggle */
            self.instituteToggle .each(function(index, element){
                $(this).parent().find("div").addClass('hide');
                $(this).on("click", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).parent().find("div").toggleClass('hide');
                });
            });
        },
        _prepareChangeStatModal:function() {
            var self = this;
            self.modalChangeStatus.on("show.bs.modal", function(e) {
                /* Get course_id from clicked link */
                var course_id = $(e.relatedTarget).attr("data-id");
                var url = $(e.relatedTarget).attr("data-action");
                /* Set the course_id in hidden field */
                self.courseId.val(course_id);
                self.remarks.val("");

                /* Bind the CKEditor for remarks */
                $.proxy(self._bindEditor, self)();
                $.proxy(self._configEditor, self)();

                /* Fetch list of status for particular course_id */
                $.ajax({
                    type: 'GET',
                    url: url,
                    datatype: 'json',
                    success: function(resp, status, xhr) {
                        if(!_.has(resp, 'status')) {
                            return;
                        }
                        var responseStatus = resp.status;
                        if(parseInt(responseStatus) == 1) {
                            /* Add options into drop-down */
                            $.each(resp.items, function(k, v) {
                                self.statusList.append($("<option></option>").attr("value", v.id).text(v.text));
                            });
                        }
                    },
                    error: function(xhr, status, err) {
                        var response = null;
                        try {
                            response = JSON.parse(xhr.responseText);
                        }catch(e) {
                            return;
                        }
                        /* Unblock the model and hide */
                        $(".modal-body", self.modalChangeStatus).unblock();
                        self.modalChangeStatus.modal("hide");

                        /* Notify the error */
                        notyfy({text: "Error : " + response.message, layout: "top", type: "error", dismissQueue:true, timeout: 5000});
                    },
                    beforeSend: function(xhr, settings) {
                        /* Clear old drop-down */
                        if(self.statusList.data("select2")) {
                            self.statusList.select2("destroy");
                        }
                        self.statusList.find("option").remove();

                        /* Disable and block the popup  until ajax complete */
                        $(".modal-body", self.modalChangeStatus).block({
                            message: window.i18next.t('content-review:wait') + ' ...'
                        });
                        self.btnChangeStatus.attr("disabled", "disabled");
                    },
                    complete: function(xhr, status, err) {
                        /* Enable and unblock the popup */
                        self.btnChangeStatus.removeAttr("disabled");
                        $(".modal-body", self.modalChangeStatus).unblock();
                    }
                })
            });
        },
        _changeStatus:function(e) {
            var self = this;
            var status = parseInt(self.statusList.val()),
                remarks = $.trim(CKEDITOR.instances.remarks.document.getBody().getText()),
                course = self.courseId.val();

            if(isNaN(status) || status <= 0) {
                bootbox.alert( window.i18next.t('content-review:error.status_required') );
                e.preventDefault();
                return false;
            } else {

                if(status != 2) {

                    if(remarks == "") {
                        bootbox.alert( window.i18next.t('content-review:error.remark_required') );
                        e.preventDefault();
                        return false;
                    }
                }
            }

            $.ajax({
                type: 'PUT',
                url: laroute.route('api.content.course.status.update', {'id' : self.courseId.val() }),
                data: {
                    "status": status,
                    "remarks": CKEDITOR.instances.remarks.document.getBody().getHtml()
                },
                datatype: 'json',
                success: function(resp, status, xhr) {
                    self.modalChangeStatus.modal("hide");
                    if(!_.has(resp, "status")){
                        return;
                    }

                    if(resp.status == 1) {
                        notyfy({text: resp.message, layout: "top", type: "success", dismissQueue:true, timeout: 5000});
                    } else if(resp.status == 0) {
                        notyfy({text: resp.message, layout: "top", type: "error", dismissQueue:true, timeout: 5000});
                    }
                },
                error: function(xhr, status, err) {
                    self.modalChangeStatus.unblock();
                    self.modalChangeStatus.modal("hide");

                    var response = null;
                    try {
                        response = JSON.parse(xhr.responseText);
                    }catch(e) {
                        return;
                    }

                    notyfy({text:  response.message, layout: "top", type: "error", dismissQueue:true, timeout: 5000});
                    },
                beforeSend: function(xhr, settings) {
                    self.btnChangeStatus.attr("disabled", "disabled");
                    self.modalChangeStatus.block({
                        message: window.i18next.t('content-review:wait') + ' ...'
                    });
                },
                complete: function(xhr, status, err) {
                    self.btnChangeStatus.removeAttr("disabled");
                    self.modalChangeStatus.unblock();
                }
            })
        },
        _prepareHistoryModal:function() {
            var self = this;
            self.modalReviewHistory.on("show.bs.modal", function(e) {

                /* Get course_id from clicked link */
                var url = $(e.relatedTarget).attr("data-action");
                /* Remove all rows after first row of history table */
                $("tr:gt(0)", self.modalReviewHistory).remove();

                /* Fetch all review history of particular course */
                $.ajax({
                    type: 'GET',
                    url: url,
                    datatype: 'json',
                    success: function(resp, status, xhr) {
                        if(!_.has(resp, "status")) return;
                        var cols;
                        if(resp.data.history.length >0)
                        {
                            $.each(resp.data.history, function(i, history){
                                cols = '<td>' + (i+1) + '</td><td>' + history.review_status_text + '</td><td>' + history.review_ts_text + '</td><td>' + history.review_notes + '</td>';
                                $("table tbody", self.modalReviewHistory).append($("<tr>" + cols + "</tr>"));
                            });
                        }
                        else{
                            cols = '<td class="text-center" colspan="4">' +window.i18next.t('content-review:info.no_history') + '</td>';
                            $("table tbody", self.modalReviewHistory).append($("<tr>" + cols + "</tr>"));
                        }
                    },
                    error: function(xhr, status, err) {
                        var response = null;
                        try {
                            response = JSON.parse(xhr.responseText);
                        }catch(e) {
                            return;
                        }
                        $(".modal-body", self.modalReviewHistory).unblock();
                        self.modalReviewHistory.modal("hide");
                        notyfy({text: response.message, layout: "top", type: "error", dismissQueue:true, timeout: 5000});
                    },
                    beforeSend: function(xhr, settings) {
                        $(".modal-body", self.modalReviewHistory).block({
                            message: window.i18next.t('content-review:wait') + ' ...'
                        });
                    },
                    complete: function(xhr, status, err) {
                        $(".modal-body", self.modalReviewHistory).unblock();
                    }
                })
            });
        },
        _bindEditor: function(e) {
            var self = this;
            if(typeof CKEDITOR !== "undefined") {
                if(this.remarks.data("htmlEditor")) {
                    this.remarks.htmlEditor("destroyEditor");
                }

                this.remarks.htmlEditor(
                    {
                        autoGrowStartUp: true,
                        toolbar: 'course_description',
                        link: {
                            showAdvanced: false,
                            showTarget: false
                        },
                        patchBootstrapModal: true
                    }
                );
            }
        },
        _configEditor: function() {
            CKEDITOR.on("dialogDefinition", function(e){
                if(e.data.name == "link") {
                    var def = e.data.definition,
                        infoTab = def.getContents("info");
                    def.height = 50;
                    infoTab.get( 'linkType' ).style = 'display: none';
                    var tgt = def.getContents("target");
                    var targetField = tgt.get('linkTargetType');
                    targetField['default'] = '_blank';
                    var urlOptions = infoTab.get('urlOptions');
                    urlOptions.children[0].children[0].items = [
                        // Force 'ltr' for protocol names in BIDI. (#5433)
                        [ 'http://\u200E', 'http://' ],
                        [ 'https://\u200E', 'https://' ]
                    ];
                }
            });
        },
        /* Format the course list get through select2 auto suggest */
        formatInstituteList: function(data) {
            if(data.loading) {
                return data.text;
            }

            return data.user_school_name;
        },

        /* Change the key of name of the select2 */
        showInstituteSelection: function(data) {
            return _.has(data, "user_school_name") ? data.user_school_name: data.text;
        }
    });
}));
$(document).ready(function(){
    $("#courses_review_container").reviewCourse();
});