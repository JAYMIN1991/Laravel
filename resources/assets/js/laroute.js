(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/open","name":"debugbar.openhandler","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@handle"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/clockwork\/{id}","name":"debugbar.clockwork","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@clockwork"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/stylesheets","name":"debugbar.assets.css","action":"Barryvdh\Debugbar\Controllers\AssetController@css"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/javascript","name":"debugbar.assets.js","action":"Barryvdh\Debugbar\Controllers\AssetController@js"},{"host":null,"methods":["GET","HEAD"],"uri":"utility\/change-password","name":"utility.changePassword.index","action":"App\Modules\Utility\Http\Controllers\ChangePasswordController@index"},{"host":null,"methods":["POST"],"uri":"utility\/change-password\/update","name":"utility.changePassword.updatePassword","action":"App\Modules\Utility\Http\Controllers\ChangePasswordController@updatePassword"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/utility","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"user\/public\/verification\/pending","name":"users.verification-pending.unverifiedAccountsList","action":"App\Modules\Users\Http\Controllers\AccountVerificationController@unverifiedAccountsList"},{"host":null,"methods":["GET","POST","HEAD"],"uri":"user\/public\/invite","name":"users.course-invitation.inviteUsers","action":"App\Modules\Users\Http\Controllers\CourseInvitationController@inviteUsers"},{"host":null,"methods":["GET","HEAD"],"uri":"user\/public\/search","name":"users.user-search.index","action":"App\Modules\Users\Http\Controllers\UserSearchController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"user\/public\/institute\/users","name":"users.institute-users.index","action":"App\Modules\Users\Http\Controllers\InstituteUsersListController@index"},{"host":null,"methods":["GET","POST","HEAD"],"uri":"user\/public\/copy","name":"users.copy-learners.index","action":"App\Modules\Users\Http\Controllers\CopyLearnersController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/users\/public\/{id}\/password\/reset","name":"users.password.reset","action":"App\Modules\Users\Http\Controllers\API\UserSearchAPIController@passwordReset"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/users\/public\/{id}\/remarks\/add","name":"users.remarks.add","action":"App\Modules\Users\Http\Controllers\API\UserSearchAPIController@addRemarks"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/users\/public\/{id}\/email\/reset","name":"users.email.reset","action":"App\Modules\Users\Http\Controllers\API\InstituteUsersListAPIController@changeEmail"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/users\/public\/{id}\/mobile\/reset","name":"users.mobile.reset","action":"App\Modules\Users\Http\Controllers\API\InstituteUsersListAPIController@changeMobile"},{"host":null,"methods":["GET","HEAD"],"uri":"backoffice\/dashboard","name":"dashboard","action":"App\Modules\Shared\Http\Controllers\DashboardController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"login","action":"App\Modules\Login\Http\Controllers\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":"loginCheck","action":"App\Modules\Login\Http\Controllers\LoginController@login"},{"host":null,"methods":["GET","HEAD"],"uri":"logout","name":"logout","action":"App\Modules\Login\Http\Controllers\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/login","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/institute\/banks","name":"account.institute.bank","action":"App\Modules\Account\Http\Controllers\InstituteBankController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/institute\/commission\/search","name":"account.user-commission.search","action":"App\Modules\Account\Http\Controllers\UserCommissionListController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/institute\/commission\/create","name":"account.user-commission.create","action":"App\Modules\Account\Http\Controllers\UserCommissionListController@create"},{"host":null,"methods":["POST"],"uri":"account\/institute\/commission","name":"account.user-commission.store","action":"App\Modules\Account\Http\Controllers\UserCommissionListController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/institute\/commission\/{commission}\/edit","name":"account.user-commission.edit","action":"App\Modules\Account\Http\Controllers\UserCommissionListController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"account\/institute\/commission\/{commission}","name":"account.user-commission.update","action":"App\Modules\Account\Http\Controllers\UserCommissionListController@update"},{"host":null,"methods":["DELETE"],"uri":"account\/institute\/commission\/{commission}","name":"account.user-commission.destroy","action":"App\Modules\Account\Http\Controllers\UserCommissionListController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/course\/orders","name":"account.course.orders","action":"App\Modules\Account\Http\Controllers\CourseOrdersController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/account\/mark-as-paid\/{trans_id}","name":"api.account.mark-as-paid","action":"App\Modules\Account\Http\Controllers\API\CourseOrdersAPIController@markAsPaid"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/account\/generate-invoice","name":"api.account.generate-invoice","action":"App\Modules\Account\Http\Controllers\API\CourseOrdersAPIController@generateInvoice"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/visit\/pre-sales\/search","name":"sales.visit.index","action":"App\Modules\Sales\Http\Controllers\VisitController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/visit\/pre-sales\/create","name":"sales.visit.create","action":"App\Modules\Sales\Http\Controllers\VisitController@create"},{"host":null,"methods":["POST"],"uri":"sales\/visit\/pre-sales","name":"sales.visit.store","action":"App\Modules\Sales\Http\Controllers\VisitController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/visit\/pre-sales\/{id}\/edit","name":"sales.visit.edit","action":"App\Modules\Sales\Http\Controllers\VisitController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"sales\/visit\/pre-sales\/{id}","name":"sales.visit.update","action":"App\Modules\Sales\Http\Controllers\VisitController@update"},{"host":null,"methods":["DELETE"],"uri":"sales\/visit\/pre-sales\/{id}","name":"sales.visit.destroy","action":"App\Modules\Sales\Http\Controllers\VisitController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/visit\/pre-sales\/{id}\/acquisition","name":"sales.visit.acquisition","action":"App\Modules\Sales\Http\Controllers\VisitController@acquisition"},{"host":null,"methods":["POST"],"uri":"sales\/visit\/pre-sales\/{id}\/acquisition-do","name":"sales.visit.acquisition-do","action":"App\Modules\Sales\Http\Controllers\VisitController@acquisitionDo"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/visit\/post-sales\/search","name":"sales.post-visit.index","action":"App\Modules\Sales\Http\Controllers\PostVisitController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/visit\/post-sales\/create","name":"sales.post-visit.create","action":"App\Modules\Sales\Http\Controllers\PostVisitController@create"},{"host":null,"methods":["POST"],"uri":"sales\/visit\/post-sales","name":"sales.post-visit.store","action":"App\Modules\Sales\Http\Controllers\PostVisitController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/visit\/post-sales\/{id}\/edit","name":"sales.post-visit.edit","action":"App\Modules\Sales\Http\Controllers\PostVisitController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"sales\/visit\/post-sales\/{id}","name":"sales.post-visit.update","action":"App\Modules\Sales\Http\Controllers\PostVisitController@update"},{"host":null,"methods":["DELETE"],"uri":"sales\/visit\/post-sales\/{id}","name":"sales.post-visit.destroy","action":"App\Modules\Sales\Http\Controllers\PostVisitController@destroy"},{"host":null,"methods":["GET","POST","HEAD"],"uri":"sales\/report\/acquisition","name":"sales.report.acquisition","action":"App\Modules\Sales\Http\Controllers\AcquisitionController@report"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/team\/search","name":"sales.team.index","action":"App\Modules\Sales\Http\Controllers\TeamController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/team\/create","name":"sales.team.create","action":"App\Modules\Sales\Http\Controllers\TeamController@create"},{"host":null,"methods":["POST"],"uri":"sales\/team","name":"sales.team.store","action":"App\Modules\Sales\Http\Controllers\TeamController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/team\/{id}\/edit","name":"sales.team.edit","action":"App\Modules\Sales\Http\Controllers\TeamController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"sales\/team\/{id}","name":"sales.team.update","action":"App\Modules\Sales\Http\Controllers\TeamController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/sales\/suggest\/city","name":"api.sales.city","action":"App\Modules\Sales\Http\Controllers\SalesApiController@getAvailableCities"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/sales\/suggest\/designation","name":"api.sales.designation","action":"App\Modules\Sales\Http\Controllers\SalesApiController@getAvailableDesignations"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/sales\/suggest\/post-visit\/designation","name":"api.sales.post-visit.designation","action":"App\Modules\Sales\Http\Controllers\SalesApiController@getAvailableDesignationsForAfterSalesVisit"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/sales\/not-acquired-institute\/{id}","name":"api.sales.not-acquired-institute","action":"App\Modules\Sales\Http\Controllers\SalesApiController@getNotAcquiredInstitute"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/sales\/get-last-post-visit-of-institute\/{id}","name":"api.sales.last-post-visit-of-institute","action":"App\Modules\Sales\Http\Controllers\SalesApiController@getLastAfterSaleVisitOfInstitute"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/promotion\/search","name":"course.promotion.index","action":"App\Modules\Course\Http\Controllers\PromotionController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/promotion\/create-search","name":"course.promotion.create-search","action":"App\Modules\Course\Http\Controllers\PromotionController@createSearch"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/promotion\/{id}","name":"course.promotion.show","action":"App\Modules\Course\Http\Controllers\PromotionController@show"},{"host":null,"methods":["DELETE"],"uri":"course\/promotion\/{id}","name":"course.promotion.destroy","action":"App\Modules\Course\Http\Controllers\PromotionController@destroy"},{"host":null,"methods":["PUT","PATCH"],"uri":"course\/promotion\/{id}","name":"course.promotion.store-or-update","action":"App\Modules\Course\Http\Controllers\PromotionController@storeOrUpdate"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/offline_payment_list","name":"course.offline.index","action":"App\Modules\Course\Http\Controllers\OfflinePaymentController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/offline_payment_list\/create","name":"course.offline.create","action":"App\Modules\Course\Http\Controllers\OfflinePaymentController@create"},{"host":null,"methods":["POST"],"uri":"course\/offline_payment_list","name":"course.offline.store","action":"App\Modules\Course\Http\Controllers\OfflinePaymentController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/offline_payment_list\/{offline_payment_list}\/edit","name":"course.offline.edit","action":"App\Modules\Course\Http\Controllers\OfflinePaymentController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"course\/offline_payment_list\/{offline_payment_list}","name":"course.offline.update","action":"App\Modules\Course\Http\Controllers\OfflinePaymentController@update"},{"host":null,"methods":["DELETE"],"uri":"course\/offline_payment_list\/{offline_payment_list}","name":"course.offline.destroy","action":"App\Modules\Course\Http\Controllers\OfflinePaymentController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/offline_payment_list\/{id}","name":"course.offline.export","action":"App\Modules\Course\Http\Controllers\OfflinePaymentController@export"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/verify_offline_payment_list","name":"course.verify_offline.index","action":"App\Modules\Course\Http\Controllers\verifyOfflinePaymentController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/verify_offline_payment_list\/generate_coupon\/{offline_payment_id}\/{institute_id}\/{course_id}\/{total_buyer}","name":"course.verify_offline.generate_coupon","action":"App\Modules\Course\Http\Controllers\verifyOfflinePaymentController@generateCoupon"},{"host":null,"methods":["GET","HEAD"],"uri":"course\/verify_offline_payment_list\/mark_as_clear\/{id}\/{instrumentProcessStatus}","name":"course.verify_offline.mark_as_clear","action":"App\Modules\Course\Http\Controllers\verifyOfflinePaymentController@markAsClear"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/course\/generate_offline_invoice\/{id}\/{user_id}\/{to_do}","name":"api.course.generate_offline_invoice","action":"App\Modules\Course\Http\Controllers\API\verifyOfflinePaymentAPIController@generateOfflinePaymentInvoice"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/course\/mark_return_cancel","name":"api.course.mark_return_cancel","action":"App\Modules\Course\Http\Controllers\API\verifyOfflinePaymentAPIController@markReturnOrCancel"},{"host":null,"methods":["GET","HEAD"],"uri":"services\/auto-suggest","name":"services.autosugget","action":"App\Modules\Services\Http\Controllers\AutoSuggestController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/services\/suggest\/courses\/search","name":"api.services.suggest.courses","action":"App\Modules\Services\Http\Controllers\AutoSuggestController@suggestCourses"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/services\/suggest\/institute\/courses\/search","name":"api.services.suggest.institute-courses","action":"App\Modules\Services\Http\Controllers\AutoSuggestController@getInstituteCourses"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/services\/suggest\/institute\/search","name":"api.services.suggest.institute","action":"App\Modules\Services\Http\Controllers\AutoSuggestController@suggestInstitute"},{"host":null,"methods":["GET","HEAD"],"uri":"report\/content\/users","name":"report.content.users","action":"App\Modules\Report\Http\Controllers\ContentUserReportController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"report\/institutions","name":"report.institutions","action":"App\Modules\Report\Http\Controllers\InstituteListController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"report\/users\/statistics\/registration","name":"report.users.statistics.registration","action":"App\Modules\Report\Http\Controllers\UsersCountController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"report\/users\/new","name":"report.users.new","action":"App\Modules\Report\Http\Controllers\UsersCountController@newUsers"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/report","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/report\/institute-inquiry\/{id}","name":"api.report.institute-inquiry","action":"App\Modules\Report\Http\Controllers\API\InstituteListAPIController@getInstituteInquiryDetails"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/report\/institute-inquiry\/{id}\/edit","name":"api.report.institute-inquiry.edit","action":"App\Modules\Report\Http\Controllers\API\InstituteListAPIController@editInstituteInquiryDetails"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/report\/plan\/activate\/{id}","name":"api.report.plan.activate","action":"App\Modules\Report\Http\Controllers\API\InstituteListAPIController@activatePlan"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/report\/plan\/deactivate\/{id}","name":"api.report.plan.deactivate","action":"App\Modules\Report\Http\Controllers\API\InstituteListAPIController@deactivatePlan"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/report\/plan\/verify\/{id}","name":"api.report.plan.verify","action":"App\Modules\Report\Http\Controllers\API\InstituteListAPIController@deactivatePlan"},{"host":null,"methods":["POST"],"uri":"api\/v1.0\/report\/plan\/cancel\/{id}","name":"api.report.plan.cancel","action":"App\Modules\Report\Http\Controllers\API\InstituteListAPIController@cancelPlan"},{"host":null,"methods":["GET","HEAD"],"uri":"content\/courses\/review","name":"content.courses.review","action":"App\Modules\Content\Http\Controllers\CoursesController@review"},{"host":null,"methods":["GET","HEAD"],"uri":"content\/courses\/{id}","name":"content.courses.show","action":"App\Modules\Content\Http\Controllers\CoursesController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/content\/courses\/{id}\/status\/search","name":"api.content.course.status.search","action":"App\Modules\Content\Http\Controllers\CoursesAPIController@getCourseStatusList"},{"host":null,"methods":["PUT"],"uri":"api\/v1.0\/content\/courses\/{id}\/status","name":"api.content.course.status.update","action":"App\Modules\Content\Http\Controllers\CoursesAPIController@updateStatus"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/content\/courses\/{id}\/review\/search","name":"api.content.course.review.search","action":"App\Modules\Content\Http\Controllers\CoursesAPIController@getCourseReviewHistory"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1.0\/content\/courses\/{id}\/section\/{section_id}\/content\/{content_id}\/attachment\/{attachment_id}","name":"api.content.course.section.content.attachment.show","action":"App\Modules\Content\Http\Controllers\CoursesAPIController@getCourseAttachmentDetails"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/tkt\/search","name":"publisher.cambridge.tkt.search","action":"App\Modules\Publisher\Http\Controllers\CambridgeTKTSearchController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/tkt\/create","name":"publisher.cambridge.tkt.create","action":"App\Modules\Publisher\Http\Controllers\CambridgeTKTSearchController@create"},{"host":null,"methods":["POST"],"uri":"publisher\/cambridge\/tkt","name":"publisher.cambridge.tkt.store","action":"App\Modules\Publisher\Http\Controllers\CambridgeTKTSearchController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/tkt\/{tkt}\/edit","name":"publisher.cambridge.tkt.edit","action":"App\Modules\Publisher\Http\Controllers\CambridgeTKTSearchController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"publisher\/cambridge\/tkt\/{tkt}","name":"publisher.cambridge.tkt.update","action":"App\Modules\Publisher\Http\Controllers\CambridgeTKTSearchController@update"},{"host":null,"methods":["DELETE"],"uri":"publisher\/cambridge\/tkt\/{tkt}","name":"publisher.cambridge.tkt.destroy","action":"App\Modules\Publisher\Http\Controllers\CambridgeTKTSearchController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/linguaskill\/search","name":"publisher.cambridge.linguaskill.search","action":"App\Modules\Publisher\Http\Controllers\CambridgeLinguaSkillSearchController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/registrations","name":"publisher.cambridge.registrations","action":"App\Modules\Publisher\Http\Controllers\CambridgeRegistrationsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/submissions","name":"publisher.cambridge.submissions","action":"App\Modules\Publisher\Http\Controllers\CambridgeSubmissionsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/submissions\/view_submission\/{sub_id}","name":"publisher.cambridge.submissions.view_submission","action":"App\Modules\Publisher\Http\Controllers\CambridgeSubmissionsController@viewSubmission"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/submissions\/download_submission\/registration\/{id}","name":"publisher.cambridge.submissions.download_submission.registration","action":"App\Modules\Publisher\Http\Controllers\CambridgeSubmissionsController@downloadRegistrationZip"},{"host":null,"methods":["GET","HEAD"],"uri":"publisher\/cambridge\/submissions\/download_submission\/submission\/{id}","name":"publisher.cambridge.submissions.download_submission.submission","action":"App\Modules\Publisher\Http\Controllers\CambridgeSubmissionsController@downloadSubmissionZip"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/publisher","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"flinnt\/load-widget","name":null,"action":"Flinnt\Core\View\Widgets\Controllers\WidgetController@showWidget"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                return this.getCorrectUrl(uri + qs);
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if(!this.absolute)
                    return url;

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

