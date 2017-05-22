<?php
if ( ! defined('DB_PREFIX') ) {
	define('DB_PREFIX', 'flt_');
}
define('TABLE_CONFIGURATION', DB_PREFIX . 'configuration');

define('TABLE_COUNTRIES', DB_PREFIX . 'countries');
define('TABLE_FRMMST', DB_PREFIX . 'frmmst');
define('TABLE_FRMRGHT', DB_PREFIX . 'frmrght');

define('TABLE_COURSES', DB_PREFIX . "courses");
define('TABLE_COURSE_STATISTICS', DB_PREFIX . 'course_statistics');
define('TABLE_COURSE_STATUS', DB_PREFIX . 'course_status');
define('TABLE_COURSE_TAGS', DB_PREFIX . 'course_tags');
define('TABLE_COURSE_CATEGORIES', DB_PREFIX . 'course_categories');
define('TABLE_COURSE_CATEGORY_APPLICABLE', DB_PREFIX . 'course_category_applicable');
define('TABLE_COURSE_PERMISSIONS', DB_PREFIX . 'course_permissions');
define('TABLE_NEW_COURSE_PERM', DB_PREFIX . 'new_course_perm');
define('TABLE_COURSE_COMMENTS', DB_PREFIX . 'course_comments');
define('TABLE_COURSE_TYPES', DB_PREFIX . 'course_types');
define('TABLE_COURSE_LOCATION', DB_PREFIX . 'course_location');
define('TABLE_COURSE_REVIEW_LOG', DB_PREFIX . 'course_review_log');

define('TABLE_USERS', DB_PREFIX . "users");
define('TABLE_USER_PERMISSIONS', DB_PREFIX . 'user_permissions');
define('TABLE_USER_COURSES', DB_PREFIX . 'user_courses');
define('TABLE_USER_COURSES_HISTORY', DB_PREFIX . 'user_courses_history');
define('TABLE_USER_COURSE_ROLES', DB_PREFIX . 'user_course_roles');
define('TABLE_USER_COURSE_INVITATIONS', DB_PREFIX . 'user_course_invitations');
define('TABLE_USER_COURSE_IMPORT_ACC', DB_PREFIX . 'user_course_import_acc');
define('TABLE_USER_PLAN_DETAILS', DB_PREFIX . 'user_plan_details');
define('TABLE_RESET_PWD_REQUESTS', DB_PREFIX . 'reset_pwd_requests');
define('TABLE_USER_POST_DELETE_STAT', DB_PREFIX . 'user_post_delete_stat');
define('TABLE_USER_ACC_VERIFICATIONS', DB_PREFIX . 'user_acc_verifications');
define('TABLE_USER_VERIFY_COURSE_SUBSCRIPTION', DB_PREFIX . 'user_verify_course_subscription');

define('TABLE_USER_ACC_TYPES', DB_PREFIX . 'user_acc_types');
define('TABLE_USER_ACC_HISTORY', DB_PREFIX . 'user_acc_history');
define('TABLE_USER_ACC_APPROVAL', DB_PREFIX . 'user_acc_approval');

define('TABLE_POST', DB_PREFIX . 'post');
define('TABLE_POST_COMMENT_USER_DETAIL', DB_PREFIX . 'post_comment_user_detail');
define('TABLE_POST_COMMENTS', DB_PREFIX . 'post_comments');
define('TABLE_POST_DETAIL', DB_PREFIX . 'post_detail');
define('TABLE_POST_TYPE_MASTER', DB_PREFIX . 'post_type_master');
define('TABLE_POST_USER_DETAIL', DB_PREFIX . 'post_user_detail');
define('TABLE_POST_TAGS', DB_PREFIX . 'post_tags');
define('TABLE_POLL_OPTIONS', DB_PREFIX . 'poll_options');
define('TABLE_POLL_RESULT', DB_PREFIX . 'poll_result');

define('TABLE_UNIQUE_ID_PWD', DB_PREFIX . 'unique_id_pwd');
define('TABLE_LOG', DB_PREFIX . 'log');
define('TABLE_DEVICE_REGISTRATIONS', DB_PREFIX . 'device_registrations');
define('TABLE_BACKOFFICE_LOG', DB_PREFIX . 'backoffice_log');
define('TABLE_BKOFF_COURSE_INVITATIONS', DB_PREFIX . 'bkoff_course_invitations');
define('TABLE_BACKOFFICE_JOB_RESULTS', DB_PREFIX . "backoffice_job_results");
define('TABLE_BACKOFFICE_USER_REMARKS', DB_PREFIX . "backoffice_user_remarks");
define('TABLE_BACKOFFICE_SALES_TEAM', DB_PREFIX . "backoffice_sales_team");
define('TABLE_BKOFF_INST_SALES_TEAM', DB_PREFIX . "bkoff_inst_sales_team");
define('TABLE_BACKOFFICE_INST_CATEGORY', DB_PREFIX . "backoffice_inst_category");
define('TABLE_BACKOFFICE_INST_INQUIRY', DB_PREFIX . "backoffice_inst_inquiry");
define('TABLE_BACKOFFICE_SALES_VISIT', DB_PREFIX . "backoffice_sales_visit");
define('TABLE_BACKOFFICE_AFTER_SALES_VISIT', DB_PREFIX . 'backoffice_after_sales_visit');
define('TABLE_BACKOFFICE_COMPLAINTS', DB_PREFIX . 'backoffice_complaints');
define('TABLE_BACKOFFICE_SESSION', DB_PREFIX . 'backoffice_session');

define('TABLE_SUBSCRIPTION_CART', DB_PREFIX . 'subscription_cart');
define('TABLE_SUBSCRIPTION_PLANS', DB_PREFIX . 'subscription_plans');
define('TABLE_SUBSCRIPTION_PLAN_DETAILS', DB_PREFIX . 'subscription_plan_details');
define('TABLE_SUBSCRIPTION_PLAN_FEATURES', DB_PREFIX . 'subscription_plan_features');
define('TABLE_SUBSCRIPTION_PLAN_PERM', DB_PREFIX . 'subscription_plan_perm');

define('TABLE_ORDERS', DB_PREFIX . 'orders');

define('TABLE_COUPONS', DB_PREFIX . 'coupons');
define('TABLE_COUPON_TYPES', DB_PREFIX . 'coupon_types');
define('TABLE_COUPON_USAGE', DB_PREFIX . 'coupon_usage');

define('TABLE_PERMISSIONS', DB_PREFIX . 'permissions');
define('TABLE_SIGNUP_PERMISSIONS_USER', DB_PREFIX . 'signup_permissions_user');

define('TABLE_SIDEBARS', DB_PREFIX . "sidebars");
define('TABLE_CRONJOBS', DB_PREFIX . "cronjobs");

define('TABLE_INVOICES', DB_PREFIX . "invoices");
define('TABLE_INVOICE_PRINTS', DB_PREFIX . "invoice_prints");

define('TABLE_EMAIL_QUEUE', DB_PREFIX . 'email_queue');
define('TABLE_LINK_VISIT_LOG', DB_PREFIX . 'link_visit_log');
define('TABLE_ADMIN_USERS', DB_PREFIX . 'admin_users');
define('TABLE_FORM_MASTER', DB_PREFIX . 'frmmst');
define('TABLE_FORM_RIGHT', DB_PREFIX . 'frmrght');
define('TABLE_SMS_HISTORY', DB_PREFIX . 'sms_history');
define('TABLE_SMS', DB_PREFIX . 'sms');
define('TABLE_SMS_QUEUE', DB_PREFIX . 'sms_queue');
define('TABLE_COURSE_CODES', DB_PREFIX . 'course_codes');
define('TABLE_POST_REPOST', DB_PREFIX . 'post_repost');
define('TABLE_ADMIN_USERS_IP', DB_PREFIX . 'admin_users_ip');

define('TABLE_CELAT_REGISTRATIONS', 'celat_registrations');
define('TABLE_CELAT_SUBMISSIONS', 'celat_submissions');
define('TABLE_CELAT_SUBMISSION_FILES', 'celat_submission_files');

define('TABLE_STATES', DB_PREFIX . "states");

define('TABLE_LMS_COPY_CONTENT', DB_PREFIX . 'lms_copy_content');
define('TABLE_LMS_SECTION_STATS', DB_PREFIX . 'lms_section_stats');
define('TABLE_LMS_SECTIONS', DB_PREFIX . 'lms_sections');
define('TABLE_LMS_CONTENTS', DB_PREFIX . 'lms_contents');
define('TABLE_LMS_ATTACHMENTS', DB_PREFIX . 'lms_attachments');
define('TABLE_LMS_CONTENT_USERS', DB_PREFIX . 'lms_content_users');
define('TABLE_LMS_CONTENT_STATS', DB_PREFIX . 'lms_content_stats');

// New Tables for ACL
define('TABLE_ADMIN_ACTIVATIONS', DB_PREFIX . 'admin_activations');
define('TABLE_ADMIN_PERSISTENCES', DB_PREFIX . 'admin_persistences');
define('TABLE_ADMIN_REMINDERS', DB_PREFIX . 'admin_reminders');
define('TABLE_ADMIN_ROLES', DB_PREFIX . 'admin_roles');
define('TABLE_ADMIN_ROLE_USERS', DB_PREFIX . 'admin_role_users');
define('TABLE_ADMIN_THROTTLES', DB_PREFIX . 'admin_throttle');

define('TABLE_USER_BANK_DETAILS',DB_PREFIX. 'user_bank_details');
define('TABLE_USER_INVOICE_SETTINGS',DB_PREFIX. 'user_invoice_settings');
define('TABLE_PAY_COMMISSION_DISCOUNT',DB_PREFIX. 'pay_commission_discounts');
define('TABLE_COURSE_PRICES', DB_PREFIX . 'course_prices');
define('TABLE_PAY_COMMISSIONS', DB_PREFIX. 'pay_commissions');

define('TABLE_PAY_TRANSACTIONS', DB_PREFIX. 'pay_transactions');

define('TABLE_PAY_TRAN_PAYMENT', DB_PREFIX. 'pay_tran_payment');
define('TABLE_PAY_BUYER_INVOICES', DB_PREFIX. 'pay_buyer_invoices');
define('TABLE_PAY_SELLER_INVOICES', DB_PREFIX. 'pay_seller_invoices');
define('TABLE_PAY_TRAN_ITEMS', DB_PREFIX. 'pay_tran_items');

define('TABLE_LEARN_TKT_TESTS', 'learn_tkt_tests');
define('TABLE_LEARN_LINGUASKILL_REG', 'learn_linguaskill_registration');
define('TABLE_LEARN_LINGUASKILL_CAND_RANGE', 'learn_linguaskill_cand_range');
define('TABLE_LEARN_LINGUASKILL_EXAM_DATES', 'learn_linguaskill_exam_dates');
define('TABLE_LEARN_LINGUASKILL_INST_TYPES', 'learn_linguaskill_inst_types');

define('TABLE_COURSE_PROMO_LOCATIONS', DB_PREFIX . 'course_promo_locations');
define('TABLE_COURSE_PROMOTION_BANNERS', DB_PREFIX . 'course_promotion_banners');

define('TABLE_PAY_OFFLINE', DB_PREFIX . 'pay_offline');
define('TABLE_PAY_COUPONS', DB_PREFIX . 'pay_coupons');
define('TABLE_PAY_COUPON_COURSES', DB_PREFIX . 'pay_coupon_courses');
define('TABLE_PAY_OFFLINE_STATUS_HISTORY', DB_PREFIX . 'pay_offline_status_history');

define('TABLE_PAY_COMMISSION_DISCOUNTS', DB_PREFIX . 'pay_commission_discounts');

define('TABLE_PAY_OFFLINE_SELLER_INVOICES', DB_PREFIX . 'pay_offline_seller_invoices');
define('TABLE_PAY_OFFLINE_SELLER_INVOICE_TAXES', DB_PREFIX . 'pay_offline_seller_invoice_taxes');
define('TABLE_PAY_OFFLINE_BUYER_INVOICES', DB_PREFIX . 'pay_offline_buyer_invoices');
define('TABLE_PAY_OFFLINE_BUYER_INVOICE_TAXES', DB_PREFIX . 'pay_offline_buyer_invoice_taxes');
