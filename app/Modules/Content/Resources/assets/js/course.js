(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.course", $.flinnt.back_office_form, {
        options: {
            course_type: 0,
            "url": "",
            "urls": {
                "section": "",
                "content": "",
                "preview_link": "",
                "download_doc": ""
            },
            "section": "",
            "content": "",
            "docpath": ""
        },
        _create: function() {
            this._super();
            var self = this;

            for (var t in this.options){
                typeof this.element.data(t.toLowerCase()) != "undefined" && (this.options[t] = this.element.data(t.toLowerCase()));
            }

            self.file_urls = $("#file_urls");
            this.options.content = self.file_urls.attr("data-content");

            if(typeof self.options.content !== "undefined" && self.options.content != null) {
                var urls = self.options.content.split("|");
                self.options.urls.preview_link = urls[0];
                self.options.urls.download_doc = urls[1];
                self.options.urls.course_popup_preview = urls[2];
            }

            self.tmpl = _.template($("#course_content_preview_template").html());
            self.dialogBox = $("#course_content_preview");
            self.content_preview_description = $("#content_preview_description_popup", self.dialogBox);
            self.preview_action_popup = $(".content_preview_class");
            self.ajaxdata_url ='';
            self.audio = {
                'player': null
            };
            self.video = {
                'player': null
            };

            window.i18next.on('initialized', function () {
                window.i18next.loadNamespaces('course', function () {
                    $.proxy(self._bindContentPopupEvent, self)();
                    $.proxy(self._prepareDescriptionSection, self)();
                    $.proxy(self._prepareReviewSection, self)();
                    $.proxy(self._prepareDynaSection, self)();
                    $.proxy(self._prepareAlsoJoinRating, self)();
                    $.proxy(self._prepareSlimScroll, self)();
                    $.proxy(self._prepareHeaderSection, self)();
                });
            });

            self.dialogBox.modal('hide').on("show.bs.modal", function(){
                self.content_preview_description.html(window.i18next.t("course:please_wait"));
            }).on("shown.bs.modal", function(e){
                $.proxy(self._loadContentPreview, self, e)();
            }).on("hidden.bs.modal", function(e){
                if(self.audio.player != null) {
                    if (!self.audio.player.paused) {
                        self.audio.player.pause();
                    }

                    self.audio.player.remove();
                }
                if(self.video.player != null) {
                    if (!self.video.player.paused) {
                        self.video.player.pause();
                    }

                    self.video.player.remove();
                }
            });
        },
        _bindContentPopupEvent : function () {
            var self = this;
            self.preview_action_popup.on("click", function(e){
                e.preventDefault();
                var course_id = $(this).attr('data-course_id');
                var section_id =  $(this).attr('data-section_id');
                var content_id =  $(this).attr('data-content_id');
                var attachment_id =  $(this).attr('data-attachment_id');
                var user_id = $(this).attr('data-user_id');

                self.ajaxdata_url = laroute.route('api.content.course.section.content.attachment.show', {
                    'id': course_id,
                    'section_id': section_id,
                    'content_id': content_id,
                    'attachment_id': attachment_id
                });
                self.userId = user_id;

                self.dialogBox.modal();
            });

        },
        _loadContentPreview: function(e) {
            var self = this;
            self.content_preview_description.html("");
            self.video.player = null;
            self.audio.player = null;

            var fail_result = function () {
                self.content_preview_description.append(window.i18n.t('course:error.no_desc'));

                if(typeof response !== "undefined") {
                    return $.proxy(self._processFailResponse, self, window.i18next.t("course:error.unknown_error"), response)();
                } else {
                    return $.proxy(self._showErrorMessage, self, window.i18next.t("course:error.unknown_error"))();
                }
            };

            $.ajax({
                type: 'GET',
                url: self.ajaxdata_url,
                data: {
                    'user_id' :  self.userId
                },
                datatype: 'json',
                beforeSend: function(xhr, settings) {
                    var msg = $('<h1>' + window.i18next.t("course:loading") + ' ...</h1>');
                    self.content_preview_description.append($(msg));

                }
            }).
            done(function(resp, status, xhr) {
                    if(!_.has(resp, "status") || resp.status == 0) {
                        fail_result();
                    }
                    self.content_preview_description.html("");
                    if(!_.has(resp, "status")) {
                        self.dialogBox.modal("hide");
                        return;
                    }
                    if(resp.data.contentlist.length == 0) {
                        var msg = $('<h2>' + window.i18next("course:error.no_desc") + '</h2>');
                        self.content_preview_description.append($(msg));
                    } else {
                        var file_type = resp.data.contentlist['0'].file_type;
                        var content_self;
                        if(file_type == 'gallery') {

                            var c = { contentlist: resp.data.contentlist };
                            self.content_preview_description.html($(self.tmpl(c)));
                            content_self = $("#gallery"); //dynamic content
                            content_self.removeClass('hide');
                            $.proxy(self._bindgalleryEvent, self, content_self)();

                        } else if(file_type == 'video') {

                            var c = {contentlist: resp.data.contentlist};
                            self.content_preview_description.html($(self.tmpl(c)));
                            content_self = $("#video"); //dynamic content
                            content_self.removeClass('hide');
                            $.proxy(self._bindvideoEvent, self, content_self)();


                        } else if(file_type == 'audio') {

                            var c = {contentlist: resp.data.contentlist};
                            self.content_preview_description.html($(self.tmpl(c)));
                            content_self = $("#audio"); //dynamic content
                            content_self.removeClass('hide');
                            $.proxy(self._bindaudioEvent, self, content_self)();

                        } else if(file_type == 'link') {

                            var c = {contentlist: resp.data.contentlist};
                            self.content_preview_description.html($(self.tmpl(c)));
                            content_self = $("#link"); //dynamic content
                            content_self.removeClass('hide');

                        } else if(file_type == 'doc') {

                            var attach_file = resp.data.contentlist['0'].attach_file;
                            var url = sprintf(self.options.urls.download_doc, sprintf(self._e(self.options.docpath + attach_file)));
                            resp.data.contentlist['0'].download_link = url;
                            resp.data.contentlist['0'].content_doc_file_icon = $.proxy(self._preparePostIconURL, self, resp.data.contentlist['0'].content_doc_file_icon)();
                            var c = {contentlist: resp.data.contentlist};
                            self.content_preview_description.html($(self.tmpl(c)));
                            content_self = $("#doc"); //dynamic content
                            content_self.removeClass('hide');

                        }
                        $.proxy(self._bindShowMore, self)();

                    }
            })
            .fail(function(xhr, status, err) {
                if(typeof xhr.responseJSON !== "undefined" && xhr.responseJSON != null) {
                    fail_result(xhr.responseJSON);
                } else {
                    fail_result();
                }
            })
            .always(function(dataxhr, status, err) {
            });
        },
        _bindShowMore: function () {
        },
        _bindvideoEvent : function (content_self) {
            var self = this;
            $('#content_video_player', content_self).mediaelementplayer({
                defaultVideoWidth: '100%',
                videoWidth: '100%',
                success: function(media, node) {
                    if(media.rendererName == "youtube_iframe") {
                        $("#content_video_player_youtube_iframe").css("width", "100%").css("height", "100%").css("max-width", "100%").css("max-height", "100%");
                    }
                }
            });
            var playerId = $('#content_video_player', content_self).closest('.mejs__container').attr('id');
            var mplayer = mejs.players[playerId];
            self.video.player = mplayer;
        },
        _bindaudioEvent: function (content_self) {
            var self = this;
            $('#content_audio_player', content_self).mediaelementplayer();
            var playerId = $('#content_audio_player', content_self).closest('.mejs__container').attr('id');
            var mplayer = mejs.players[playerId];
            self.audio.player = mplayer;
        },
        _bindgalleryEvent: function(content_self) {
            var self = this;
            content_self.find(".image_fade").hover(function() {
                $(this).filter(':not(:animated)').animate({opacity: 0.6}, 400);
            }, function () {
                $(this).animate({opacity: 1}, 400);
            });
            content_self.find('[data-lightbox="image"]').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                closeBtnInside: false,
                fixedContentPos: true,
                mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
                image: {
                    verticalFit: true
                },
                zoom: {
                    enabled: true, // By default it's false, so don't forget to enable it

                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out', // CSS transition easing function
                    opener: function(openerElement) {
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
            });
        },
        _processFailResponse: function(def_error, response) {
            var self = this;

            if(_.has(response, "errors") && response.errors.length > 0) {
                var error_code = response.errors[0].code;
                switch(true) {
                    case (error_code <= 5 || [600].indexOf(error_code, 0) > 0):
                        if(error_code == 2 && response.message.toLowerCase().indexOf("invalid request") < 0) {
                            self._showErrorMessage.call(self, response.message);
                        } else{
                            self._showErrorMessage.call(self, def_error);
                        }
                        break;
                    case (error_code == 8):
                        self._showErrorMessage.call(self, response.errors[0].message, function(){location.href = "/"});
                        break;
                    case ( [509, 701].indexOf(error_code) > 0):
                        self._showErrorMessage.call(self, response.errors[0].message, function(){window.location.reload(true)});
                        break;
                    case (error_code == 312):
                        self._showErrorMessage.call(self, response.errors[0].message, function(){location.href = "/logout/"});
                        break;
                    default:
                        self._showErrorMessage.call(self, def_error);
                        break;
                }

                return !1;
            }
            self._showErrorMessage.call(self, def_error);
            return !1;
        },
        _showErrorMessage: function(msg, callback) {
            bootbox.dialog({
                title: '<span class="red2"><i class="fa fa-exclamation-triangle"></i> '+ window.i18next.t("course:error.errors")+'</span>',
                message: '<h4>' + msg + '</h4>',
                buttons: {
                    close: {
                        label: 'Close',
                        className: 'btn-danger',
                        callback: (typeof callback !== "undefined" ? callback : function(){})
                    }
                },
                onEscape: (typeof callback !== "undefined" ? callback : function(){})
            });
            return !1;
        },

        _prepareSlimScroll:function() {
            var content_scroll = $(".content-scroll");
            if(typeof content_scroll != 'undefined') {
                var cc_height = content_scroll.height();
                /* check if scrolled is required */
                if (cc_height >= 235) {
                    content_scroll.slimScroll({
                        allowPageScroll: true, // allow page scroll when the element scroll is ended
                        size: '6px',
                        color: '#666',
                        wrapperClass: ($(this).attr("data-wrapper-class")  ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
                        railColor: '#D9D9D9',
                        position: 'right',
                        height: cc_height,
                        alwaysVisible: true,
                        railVisible: true,
                        disableFadeOut: true,
                        wheelStep:5,
                        touchScrollStep:200
                    });
                }
            }
        },
        _prepareAlsoJoinRating:function() {
            var self = this;
            $(".course-also-join").find(".course_ratings").each(function() {
                var ratingval = $(this).val();
                if(ratingval == null) {
                    $(this).rating({displayOnly: true, min: 0, size: 'course-browse', theme: 'flinnt', emptyStar: '<i class="glyphicon glyphicon-star"></i>'});
                } else {
                    if(typeof $(this).data("rating") !== "undefined") {
                        $(this).rating('update', ratingval);
                    } else {
                        $(this).val(ratingval).rating({displayOnly: true, min: 0, size: 'course-browse', theme: 'flinnt', emptyStar: '<i class="glyphicon glyphicon-star"></i>'});
                    }
                }
            })
        },
        _prepareHeaderSection:function() {
            var self = this,
                elem;
            self.wishlist = $("#course_wishlist");
            if(self.wishlist.length > 0) {
                self.add_wishlist_tpl = _.template($("#addwishlist_tpl").html());
                self.wishlisted_rate_tpl = _.template($("#wishlisted_tpl").html());

                $("a", self.wishlist).each(function (index, element) {
                    elem = $(element);
                    if (elem.attr("data-href").indexOf("/login/") > 0) {
                        $.proxy(self._bindDoLogin, self, elem)();
                    } else {
                        if (elem.hasClass("wishlist")) {
                            $.proxy(self._bindWishlistEvent, self, "add", elem)();
                        } else {
                            $.proxy(self._bindWishlistEvent, self, "remove", elem)();
                        }
                    }
                });
            }

            $("#course-landing-area a.btn").on("click", function(e){
                e.preventDefault();
                location.href = $(this).attr("data-href");
            });
        },
        _prepareDynaSection: function() {
            self.social = $("#social_share");
            if(self.social.length) {
                self.social.social_share();
            }
        },
        _prepareDescriptionSection:function() {
            var self = this;
            self.ratings = $("#course_ratings");
            self.ratings_count_label = $(".course_avg_ratings");
        },
        _prepareReviewSection:function(){
            var self = this;
            self.my_review = $("#my_review");

            if(self.my_review.length <= 0) {
                return 1;
            }

            self.my_review_url = self.my_review.attr("data-url");
            self.add_edit_review_url = self.my_review.attr("data-url-addedit");
            self.delete_review_url = self.my_review.attr("data-url-del");
            self.my_review_data = null;

            self.my_review_template = _.template($("#my_review_tpl").html());
            self.add_review_template = _.template($("#add_review_tpl").html());

            self.user_reviews = {
                "container": $("#user_reviews"),
                "pagination": {
                    "current_page": 1,
                    "count": 0,
                },
                "template": _.template($("#user_review_tpl").html())
            };
            self.user_reviews.url = self.user_reviews.container.attr("data-url");
            self.user_reviews.list = $("ol", self.user_reviews.container);
            self.user_reviews.pagination.container = $(".user_review_pagination", self.user_reviews.container);
            self.user_reviews.pagination.prev = $("#user_review_prev", self.user_reviews.pagination.container);
            self.user_reviews.pagination.next = $("#user_review_next", self.user_reviews.pagination.container);
            self.user_reviews.container.hide(), self.user_reviews.pagination.container.hide();
            $("#user_review_prev, #user_review_next", self.user_reviews.pagination.container).each(function(index, element){
                $(element).on("click", function(e){
                    e.preventDefault();
                    $.proxy(self._showUserReviews, self, $(element).attr("data-url"))();
                });
            });
            self.user_reviews.container.show();
        },
        _DOM_AddReview: function(resp) {
            var self = this;
            var frm = $($.proxy(self._renderAddReview, self, true, 0)());
            $("#comment_rating", frm).rating({min: 0, step: 1, size: 'course-entry', theme: 'flinnt', emptyStar: '<i class="glyphicon glyphicon-star"></i>', showCaption: false, showClear: false, animate: false});
            var parameters = {addnew: true, review_id: 0};
            $("#submit-button", frm).on("click", $.proxy(self._submitReview, self, parameters));
            self.my_review.empty().append(frm);
        },
        _DOM_MyReview: function(resp) {
            var self = this;
            self.my_review_data = resp.review;
            var rvw = $(self.my_review_template({"review": resp.review}));
            $("#my_comment_rating", rvw).rating({min: 0, step: 1, size: 'course-esmall', theme: 'flinnt', emptyStar: '<i class="glyphicon glyphicon-star"></i>', displayOnly: true});
            $(".comment-edit", rvw).on("click", function(e) {
                e.preventDefault();
                $.proxy(self._DOM_EditReview, self, resp)();
            });
            $(".comment-remove a", rvw).on("click", function(e){
                var me = $(this);
                var parameters = {review_id: resp.review.id};
                e.preventDefault();
                bootbox.confirm(window.i18next.t("course:confirm.want_to_remove"), function(result){
                    if(result) {
                        $.proxy(self._deleteReview, self, parameters)();
                    }
                });
            });
            self.my_review.empty().append(rvw);
        },
        _DOM_EditReview: function(resp) {
            var self = this;
            var frm = $($.proxy(self._renderEditReview, self, true, 1)());
            $("#comment_rating", frm).val(resp.review.rating).rating({min: 0, step: 1, size: 'course-entry', theme: 'flinnt', emptyStar: '<i class="glyphicon glyphicon-star"></i>', showCaption: false, showClear: false, animate: false});
            $("#review_text", frm).val(resp.review.text);
            var parameters = {addnew: true, review_id: resp.review.id};
            $("#submit-button", frm).on("click", $.proxy(self._submitReview, self, parameters));
            $("#cancel-submit", frm).on("click", $.proxy(self._DOM_MyReview, self, resp));
            self.my_review.empty().append(frm);
        },
        _deleteReview:function(parameters) {
            var self = this,
                review_id = parameters.review_id,
                pdata = {"review_id": review_id};
            $.ajax({
                type: 'POST',
                url: self.delete_review_url,
                data: pdata,
                datatype: "json",
                beforeSend:function(xhr, settings) {
                    $(".comment-remove a").hide();
                    $(".comment-remove").append($('<img src="images/flinnt_loader.gif" style="vertical-align: middle;" />'));
                }
            }).
            done(function(resp, status, xhr){
                var reload_review = function() {
                    $.proxy(self._showMyReview, self)();
                };
                if(_.has(resp, "stat")) {
                    if(resp.stat == 0) {
                        bootbox.dialog({
                            message: '<h2 class="red2">'+window.i18next.t("course:error.fail_delete")+'</h2>',
                            buttons: {
                                close: {
                                    label: 'Close',
                                    className: 'btn-danger'
                                }
                            }
                        });
                        reload_review();
                        return !1;
                    } else {
                        reload_review();
                        bootbox.alert(window.i18next.t("course:success.review_removed"));
                        if(_.has(resp, "ratings")) {
                            var avgrate = parseFloat(resp.ratings);
                            if(!isNaN(avgrate)) {
                            }
                        }
                    }
                } else {
                    reload_review();
                }
                return 1;
            }).
            fail(function(xhr, status, err){
                $(".comment-remove").remove("img");
                bootbox.dialog({
                    message: '<h2 class="red2">' + window.i18next.t("course:error.error_delete") + '</h2>',
                    buttons: {
                        close: {
                            label: 'Close',
                            className: 'btn-danger'
                        }
                    }
                })
            }).
            always(function(dataxhr, status, err){

            });
        },
        _submitReview: function(parameters) {
            var self = this,
                addnew = parameters.addnew,
                review_id = parameters.review_id,
                rating = $("#comment_rating").val(),
                norate = false;

            var show_no_rate_error = function() {
                bootbox.dialog({
                    message: '<h4 class="red2 nopadding">'+ window.i18next.t('course:error.must_choose_ratings') +'</h4>',
                    buttons: {
                        close: {
                            label: 'Close',
                            className: 'btn-danger'
                        }
                    }
                });
            };

            if(rating == null) {
                show_no_rate_error();
                return !1;
            }
            rating = !norate ? parseInt(rating) : 0;
            if(isNaN(rating) || rating == 0) {
                show_no_rate_error();
                return !1;
            }
            var pdata = {"rating": rating, "review_text": $("#review_text").val()};
            if(review_id != null && review_id != "") {
                pdata.review_id = review_id;
            }
            $.ajax({
                type: 'POST',
                url: self.add_edit_review_url,
                data: pdata,
                datatype: 'json',
                beforeSend: function(xhr, settings) {
                    $("#submit-button").attr("disabled", "disabled").prepend(spinner);
                }
            }).
            done(function(resp, statu, xhr){
                var refresh_review = function() {
                    $.proxy(self._showMyReview, self)();
                };
                if(_.has(resp, "submitted")) {
                    if(resp.submitted == 1) {
                        bootbox.alert(window.i18next.t("course:success.review_submitted"));
                        if(_.has(resp, "ratings")) {
                            var ratings = parseFloat(resp.ratings);
                            if(!isNaN(ratings)) {
                                $.proxy(self._showAvgRatings, self, ratings.toFixed(2))();
                            }
                        }
                        refresh_review();
                    } else {
                        bootbox.dialog({
                            message: '<h2 class="red2">'+window.i18next.t("course:error.fail_submit_review")+'</h2>',
                            buttons: {
                                close: {
                                    label: 'Close',
                                    className: 'btn-danger'
                                }
                            }
                        })
                        refresh_review();
                        return !1;
                    }
                } else {
                    refresh_review();
                }
            }).
            fail(function(xhr, status, err){
                $("#submit-button").removeAttr("disabled").remove("i.fa-spin");
                var $msg = function() {
                    bootbox.dialog({
                        message: '<h2 class="red2">' + window.i18next.t("course:error.fail_submit_review") + '</h2>',
                        buttons: {
                            close: {
                                label: 'Close',
                                className: 'btn-danger'
                            }
                        }
                    })
                };
                if(typeof xhr.responseJSON !== "undefined") {
                    if(xhr.responseJSON.code == 3) {
                        bootbox.alert( window.i18next.t("course:session_expired"), function(){
                            location.reload();
                        })
                    } else {
                        $msg();
                    }
                } else {
                    $msg();
                }
            });
        },
        _showUserReviews: function(rurl) {

            var self = this,
                pdata = {"max": self.user_reviews.pagination.count};
            $.ajax({
                type: "POST",
                url: rurl,
                data: pdata,
                datatype: "json"
            }).
            done(function(resp, statu, xhr){
                if(!_.has(resp, "reviews")) {
                    self.user_reviews.hide();
                    return !1;
                }
                //Show Review heading title when user reviews exist
                if(resp.count > 0) {
                    $("#course_review_heading").show();
                }
                var reviews = $(self.user_reviews.template({"reviews": resp.reviews}));
                $("input", reviews).rating({displayOnly: true, min: 0, size: 'course-esmall', theme: 'flinnt', emptyStar: '<i class="glyphicon glyphicon-star"></i>'});
                self.user_reviews.list.empty(0);
                self.user_reviews.list.append(reviews);
                if(resp.has_more == 0 && resp.page > 1) {
                    !self.user_reviews.pagination.container.is(":visible") && self.user_reviews.pagination.container.show();
                    self.user_reviews.pagination.next.attr("disabled", "disabled").data("url", "");
                } else if (resp.prev == "" && resp.next == "") {
                    self.user_reviews.pagination.container.hide();
                } else {
                    !self.user_reviews.pagination.container.is(":visible") && self.user_reviews.pagination.container.show();
                    if (resp.next == "") {
                        self.user_reviews.pagination.next.attr("disabled", "disabled").data("url", "");
                    } else {
                        self.user_reviews.pagination.next.attr("data-url", resp.next).removeAttr("disabled");
                    }
                }
                if(resp.prev == "") {
                    self.user_reviews.pagination.prev.attr("disabled", "disabled").data("url", "");
                } else {
                    self.user_reviews.pagination.prev.attr("data-url", resp.prev).removeAttr("disabled");
                }
                self.user_reviews.pagination.count = resp.max;
            }).
            fail(function(xhr, status, err){

            });
        },
        _addToWishlist:function(wurl) {
            var self = this;
            $.ajax({
                type: "POST",
                url: wurl,
                data: {},
                datatype: "json"
            }).
            done(function(resp, statu, xhr){
                if(!_.has(resp, "is_wishlist")) {
                    bootbox.alert(window.i18next.t("course:error.add_course_wishlist_fail"));
                    return !1;
                }
                self.wishlist.html("");
                var t, elem, target;
                if(resp.is_wishlist == 1) {
                    bootbox.alert(window.i18next("course:success.course_add_to_wishlist"));
                    t = $(self.wishlisted_rate_tpl({"url": resp.url}));
                    target = "remove";
                } else {
                    t = $(self.add_wishlist_tpl({"url": resp.url}));
                    target = "add";
                }
                self.wishlist.append(t);
                elem = $("a", self.wishlist);
                $.proxy(self._bindWishlistEvent, self, target, elem)();
            }).
            fail(function(xhr, status, err){
                /*silently discard errors*/
            });
        },
        _removeWishlist:function(wurl) {
            var self = this;
            $.ajax({
                type: "POST",
                url: wurl,
                data: {},
                datatype: "json"
            }).
            done(function(resp, statu, xhr){
                if(!_.has(resp, "is_wishlist")) {
                    bootbox.alert(window.i18next.t("course:error.add_course_wishlist_fail"));
                    return !1;
                }
                self.wishlist.html("");
                var t, elem, target;
                if(resp.is_wishlist == 1) {
                    t = $(self.wishlisted_rate_tpl({"url": resp.url}));
                    target = "remove";
                } else {
                    t = $(self.add_wishlist_tpl({"url": resp.url}));
                    target = "add";
                }
                self.wishlist.append(t);
                elem = $("a", self.wishlist);
                $.proxy(self._bindWishlistEvent, self, target, elem)();
            }).
            fail(function(xhr, status, err){
                //silently discard errors
            });
        },
        _bindDoLogin: function(element) {
            element.on("click", function(){
                bootbox.alert(window.i18next.t("course:error.must_login"), function(){
                    location.href = element.attr("data-href");
                })
            });
        },
        _bindWishlistEvent: function(target, element) {
            var self = this;
            switch(target) {
                case "add":
                    element.on("click", function(e){
                        e.preventDefault();
                        $.proxy(self._addToWishlist, self, $(this).attr("data-href"))();
                    });
                    break;
                case "remove":
                    element.on("click", function(e){
                        e.preventDefault();
                        $.proxy(self._removeWishlist, self, $(this).attr("data-href"))();
                    });
                    break;
            }
        },
        _renderAddReview: function() {
            var self = this;
            return self.add_review_template({cancel: 0});
        },
        _renderEditReview: function() {
            var self = this;
            return self.add_review_template({cancel: 1});
        },
        _preparePostIconURL: function(filename){
            filename = filename.substring(filename.lastIndexOf('/')+1);
            return '/images/post/' + filename;
        }
    });
}));
$(document).on("ready", function(e){
    $("#course-contents").course();
});