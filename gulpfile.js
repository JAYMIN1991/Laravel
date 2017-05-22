/**
 * Created by flinnt-php-6 on 16/1/17.
 */

const elixir = require('laravel-elixir');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

/* Disable Desktop toast notification if local option is provided */
if (process.argv[2] == '--local') {
    process.env.DISABLE_NOTIFIER = true;
}

elixir.config.js.uglify.options.mangle = true;
elixir.config.sourcemaps = true;

const modulePath = '../../../app/Modules/';

// Sales module resource path
const salesJsAssetPath = modulePath + 'Sales/Resources/assets/js/';

// Users module resource path
const usersJsAssetPath = modulePath + 'Users/Resources/assets/js/';

// Report module resource path
const reportJsAssetPath = modulePath + 'Report/Resources/assets/js/';

// Account module resource path
const accountJsAssetPath = modulePath + 'Account/Resources/assets/js/';

//publisher resource path
const publisherJsAsserpath = modulePath + 'Publisher/Resources/assets/js/';

// Content module resource path
const contentJsAssetPath = modulePath + 'Content/Resources/assets/js/';

//Course module resource path
const courseJsAssetPath = modulePath + 'Course/Resources/assets/js/';

// Gulp related task
elixir(mix => {

    mix.styles([
        'library/bootstrap/css/bootstrap.min.css',
        'library/icons/fontawesome/assets/css/font-awesome.min.css',
        'library/icons/glyphicons/assets/css/glyphicons_filetypes.css',
        'library/icons/glyphicons/assets/css/glyphicons_regular.css',
        'library/icons/glyphicons/assets/css/glyphicons_social.css',
        'library/icons/pictoicons/css/picto.css',
        'library/animate/animate.min.css',
        'plugins/calendar_fullcalendar/css/fullcalendar.css',
        'plugins/charts_easy_pie/css/jquery.easy-pie-chart.css',
        'plugins/core_prettyprint/css/prettify.css',
        'plugins/forms_editors_wysihtml5/css/bootstrap-wysihtml5-0.0.2.css',
        'plugins/forms_elements_bootstrap-datepicker/css/bootstrap-datepicker.css',
        'plugins/forms_elements_bootstrap-select/css/bootstrap-select.css',
        'plugins/forms_elements_bootstrap-switch/css/bootstrap-switch.css',
        'plugins/forms_elements_bootstrap-timepicker/css/bootstrap-timepicker.css',
        'plugins/forms_elements_colorpicker-farbtastic/css/farbtastic.css',
        'plugins/forms_elements_jasny-fileupload/css/fileupload.css',
        'plugins/forms_elements_multiselect/css/multi-select.css',
        'plugins/forms_elements_select2_latest/css/select2.css',
        'plugins/forms_file_dropzone/css/dropzone.css',
        'plugins/forms_file_plupload/jquery.plupload.queue/css/jquery.plupload.queue.css',
        'plugins/maps_vector/css/elements.css',
        'plugins/maps_vector/css/jquery-jvectormap-1.1.1.css',
        'plugins/maps_vector/css/jquery-jvectormap-1.2.2.css',
        'plugins/media_blueimp/css/blueimp-gallery.min.css',
        'plugins/media_image-crop/css/jquery.Jcrop.css',
        'plugins/media_owl-carousel/owl.carousel.css',
        'plugins/media_owl-carousel/owl.theme.css',
        'plugins/media_prettyphoto/css/prettyPhoto.css',
        'plugins/notifications_gritter/css/jquery.gritter.css',
        'plugins/notifications_notyfy/css/jquery.notyfy.css',
        'plugins/notifications_notyfy/css/notyfy.theme.default.css',
        'plugins/other_page-tour/css/pageguide.css',
        'plugins/tables_datatables/extras/ColReorder/media/css/ColReorder.css',
        'plugins/tables_datatables/extras/ColVis/media/css/ColVis.css',
        'plugins/tables_datatables/extras/TableTools/media/css/TableTools.css',
        'plugins/tables_responsive/css/footable.core.min.css',
        'plugins/ui_sliders_range_jqrangeslider/css/iThing.css',
        'library/jquery-ui/css/jquery-ui.min.css',
        'admin/module.admin.stylesheet-complete.skin.flinnt-theme.css',
        'custom.css'
    ],'public/css/all.css');

    mix.scripts([
        'library/jquery/jquery.min.js',
        'library/jquery/jquery-migrate.min.js',
        'library/modernizr/modernizr.js',
        'plugins/core_less-js/less.min.js',
        'plugins/core_browser/ie/ie.prototype.polyfill.js',
        'library/bootstrap/js/bootstrap.min.js',
        'plugins/core_nicescroll/jquery.nicescroll.min.js',
        'plugins/core_breakpoints/breakpoints.js',
        'plugins/core_preload/pace.min.js',
        'components/core_preload/preload.pace.init.js',
        'library/jquery-ui/js/jquery-ui.min.js',
        'plugins/forms_validator/jquery-validation/dist/jquery.validate.min.js',
        'plugins/forms_elements_select2_latest/js/select2.full.min.js',
        'plugins/underscore/underscore.js',
        'plugins/js-cookie-latest/js/js.cookie.js',
        'components/forms_elements_fuelux-checkbox/fuelux-checkbox.init.js',
        'components/forms_elements_fuelux-radio/fuelux-radio.init.js',
        'plugins/forms_elements_jasny-fileupload/js/bootstrap-fileupload.js',
        'plugins/forms_elements_uniform/js/jquery.uniform.min.js',
        'components/tables/tables-classic.init.js',
        'plugins/ui_modals/bootbox.min.js',
        'plugins/notifications_notyfy/js/jquery.notyfy.js',
        'plugins/forms_elements_bootstrap-datepicker/js/bootstrap-datepicker.js',
        'plugins/tables_datatables/js/jquery.dataTables.min.js',
        'plugins/tables_datatables/extras/TableTools/media/js/TableTools.min.js',
        'plugins/tables_datatables/extras/ColVis/media/js/ColVis.min.js',
        'plugins/forms_elements_inputmask/jquery.inputmask.bundle.min.js',
        'plugins/moment/moment.js',
        'laravel-delete.js',
        'setup.init.js',
        'laroute.js'
    ], 'public/js/build-vendor-top.js')
        .scripts([
            'plugins/i18next/i18next.js',
            'plugins/i18next/plugins/i18nextXHRBackend.js',
            'components/forms_validator/form-validator.init.js',
            'components/core/core.init.js',
            'common.js'
        ], 'public/js/build-vendor-bottom.js')
        .scripts([
            usersJsAssetPath + 'user-search.js'
        ], 'public/js/page/build-user-search.js')
        .scripts([
            usersJsAssetPath + 'institute-users-list.js'
        ], 'public/js/page/build-institute-users-list.js')
        .scripts([
            usersJsAssetPath + 'course-invitation.js'
        ], 'public/js/page/build-course-invitation.js')
        .scripts([
            usersJsAssetPath + 'copy-learners.js'
        ], 'public/js/page/build-copy-learners.js')
        .scripts([
            salesJsAssetPath + '/inst-call-visit.js'
        ], 'public/js/page/build-inst-call-visit.js')
        .scripts([
            salesJsAssetPath + '/inst-call-visit-list.js'
        ], 'public/js/page/build-inst-call-visit-list.js')
        .scripts([
            salesJsAssetPath + '/sales-team.js'
        ], 'public/js/page/build-sales-team.js')
        .scripts([
            salesJsAssetPath + '/visit-acquisition.js'
        ], 'public/js/page/build-visit-acquisition.js')
        .scripts([
            salesJsAssetPath + '/inst-acq-report.js'
        ], 'public/js/page/build-inst-acq-report.js')
        .scripts([
            salesJsAssetPath + '/after-sales-visit.js'
        ], 'public/js/page/build-after-sales-visit.js')
        .scripts([
            salesJsAssetPath + '/after-sales-visit-list.js'
        ], 'public/js/page/build-after-sales-visit-list.js')
        .scripts([
            reportJsAssetPath+ '/content-user-report.js'
        ], 'public/js/page/build-content-user-report.js')
        .scripts([
            reportJsAssetPath+ '/institute-list.js'
        ], 'public/js/page/build-institute-list.js')
        .scripts([
            reportJsAssetPath+ '/users-count.js'
        ], 'public/js/page/build-users-count.js')
        .scripts([
            accountJsAssetPath + '/institute-bank.js'
        ], 'public/js/page/build-institute-bank.js')
        .scripts([
            accountJsAssetPath + '/user-commission-list.js'
        ], 'public/js/page/build-user-commission-list.js')
        .scripts([
            accountJsAssetPath + '/user-commission.js'
        ], 'public/js/page/build-user-commission.js')
        .scripts([
            contentJsAssetPath + '/courses-review.js'
        ], 'public/js/page/build-courses-review.js')
        .scripts([
            contentJsAssetPath + '/fapps.js'
        ], 'public/js/page/build-fapps.js')
        .scripts([
            contentJsAssetPath + '/plugins.js'
        ], 'public/js/page/build-plugins.js')
        .scripts([
            contentJsAssetPath + '/course.js'
        ], 'public/js/page/build-course.js')
        .scripts([
            '/flinnt.htmleditor.js'
        ], 'public/js/flinnt.htmleditor.js')
        .scripts([
            accountJsAssetPath + '/course-orders.js'
        ], 'public/js/page/build-course-orders.js')
        .scripts([
            publisherJsAsserpath + '/cambridge-tkt-exam-list.js'
        ], 'public/js/page/build-cambridge-tkt-exam-list.js')
        .scripts([
            publisherJsAsserpath + '/cambridge-tkt-exam.js'
        ], 'public/js/page/build-cambridge-tkt-exam.js')
        .scripts([
            publisherJsAsserpath + '/lingua-skill-list.js'
        ], 'public/js/page/build-lingua-skill-list.js')
        .scripts([
            publisherJsAsserpath + '/cambridge-registrations-list.js'
        ], 'public/js/page/build-cambridge-registrations-list.js')
        .scripts([
            publisherJsAsserpath + '/cambridge-submissions-list.js'
        ], 'public/js/page/build-cambridge-submissions-list.js')
        .scripts([
            courseJsAssetPath + '/promotion.js'
        ],  'public/js/page/build-promotion.js')
        .scripts([
            courseJsAssetPath + '/offline_payment.js'
        ],  'public/js/page/build-offline_payment.js')
        .scripts([
            courseJsAssetPath + '/verify_offline_payment.js'
        ],  'public/js/page/build-verify_offline_payment.js');

    mix.version([
        'public/css/all.css',
        'public/js/build-vendor-top.js',
        'public/js/build-vendor-bottom.js',
        'public/js/page/build-user-search.js',
        'public/js/page/build-institute-users-list.js',
        'public/js/page/build-inst-call-visit.js',
        'public/js/page/build-inst-call-visit-list.js',
        'public/js/page/build-sales-team.js',
        'public/js/page/build-visit-acquisition.js',
        'public/js/page/build-inst-acq-report.js',
        'public/js/page/build-after-sales-visit.js',
        'public/js/page/build-after-sales-visit-list.js',
        'public/js/page/build-institute-bank.js',
        'public/js/page/build-user-commission-list.js',
        'public/js/page/build-user-commission.js',
        'public/js/page/build-content-user-report.js',
        'public/js/page/build-institute-list.js',
        'public/js/page/build-courses-review.js',
        'public/js/page/build-fapps.js',
        'public/js/page/build-plugins.js',
        'public/js/page/build-course.js',
        'public/js/page/build-users-count.js',
        'public/js/page/build-cambridge-tkt-exam-list.js',
        'public/js/page/build-cambridge-tkt-exam.js',
        'public/js/page/build-lingua-skill-list.js',
        'public/js/page/build-course-orders.js',
        'public/js/page/build-cambridge-registrations-list.js',
        'public/js/page/build-cambridge-submissions-list.js',
        'public/js/page/build-promotion.js',
        'public/js/page/build-offline_payment.js',
        'public/js/page/build-verify_offline_payment'
    ]);

    mix.copy('resources/assets/fonts', 'public/build/fonts');
    mix.copy('resources/assets/css/images', 'public/build/css/images');
});
