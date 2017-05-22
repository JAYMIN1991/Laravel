<?php
	const APP_ENV_LOCAL = 'local'; // Local environment constant. Used to compare current environment

	const BACKOFFICE_ADMIN_ID = 1;

	const LOG_APP_BACK_OFFICE = 'BACKOFFICE';

	const LOG_EQUAL_TO = '__EQUAL__';
	const LOG_SEPARATOR = '__NEXT__';
	const LOG_NEWLINE = '__NL__';
	const LOG_EMPTY_VALUE = '__EMPTY__';

	const LOG_MODULE_SMS = 'sms';
	const LOG_MODULE_ACQUISITION = 'acquisition';
	const LOG_MODULE_SALES_VISIT = 'sales_visit';
	const LOG_MODULE_SALES_VISIT_LIST = 'sales_visit_list';
	const LOG_MODULE_COMPLAIN_USER_SEARCH = 'complain_user_search';
	const LOG_MODULE_COMPLAIN_SEARCH = 'complain_search';
	const LOG_MODULE_COMPLAIN = 'complain';
	const LOG_MODULE_CONTENT_USE_REPORT = 'content_use_report'; // used on content_use_report.php
	const LOG_MODULE_ACQ_REPORT = 'acq_report';
	const LOG_MODULE_USER_COURSE_LIST = 'user_course_list';
	const LOG_MODULE_COURSE_POST_LIST = 'course_post_list';
	const LOG_MODULE_INST_LIST = 'inst_list';
	const LOG_MODULE_INST_USER_LIST = 'inst_user_list'; // used on inst_user_list.php
	const LOG_MODULE_USER_COUNT_LIST = 'user_count_list'; // used on user_count_list.php
	const LOG_MODULE_NEW_USERS_LIST = 'new_users_list'; // used on user_count_list.php
	const LOG_MODULE_MANAGE_USER_ACCOUNT = 'manage_user_account'; // used on manage_user_account.php
	const LOG_MODULE_USER_SEARCH = 'user_search'; // used on user_search.php
	const LOG_MODULE_USER_RIGHTS = 'user_rights'; // used on user_rights.php
	const LOG_MODULE_COPY_LEARNERS = 'copy_learners'; // used on copy_learners.php
	const LOG_MODULE_ACC_PENDING_VERIFICATION = 'acc_pending_verification'; // used on acc_pending_verification.php
	const LOG_MODULE_ADMIN_USERS = 'admin_users';
	const LOG_MODULE_ADMIN_USERS_IP = 'admin_users_ip'; // used on bo_user_ip.php
	const LOG_MODULE_SALES_TEAM = 'sales_team';
	const LOG_MODULE_BKOFF_INST_SALES_TEAM = 'inst_sales_team';
	const LOG_MODULE_USERS = 'user';
	const LOG_MODULE_USER_REMARKS = 'user_remarks'; // used on inst_user_list_ajax.php
	const LOG_MODULE_POST_VIEW_USERS = 'post_view_users'; // used on post_view_users.php
	const LOG_MODULE_FRMRGHT = 'frmrght'; // used on user_rights.php
	const LOG_MODULE_BKOFF_COURSE_INVITATIONS = 'course_invitations'; // used on course_invite_users.php
	const LOG_MODULE_COURSE = 'course';
	const LOG_MODULE_COURSE_ORDER_SUMMARY = 'course_order_summary';
	const LOG_MODULE_AFTER_SALES_VISIT = 'after_sales_visit';
	const LOG_MODULE_AFTER_SALES_VISIT_LIST = 'after_sales_visit_list';
	const LOG_MODULE_USER_COMMISSION = 'commission';
	const LOG_MODULE_MOBILE_APP_UPDATES = 'mobile_app_updates';
	const LOG_MODULE_INSTITUTE_BANK_DETAILS = 'institute_bank_details';
	const LOG_MODULE_PLAN = 'plan';
	const LOG_MODULE_CAMBRIDGE_TKT_EXAM = 'cambridge_tkt_exam';
	const LOG_MODULE_LINGUA_SEARCH = 'lingua_search';
	const LOG_MODULE_CAMBRIDGE_REGISTRATION = 'cambridge_registration';
	const LOG_MODULE_CAMBRIDGE_SUBMISSION = 'cambridge_submission';
	const LOG_MODULE_COURSE_PROMOTION = 'course_promotion';
	const LOG_MODULE_COURSE_OFFLINE_PAYMENT = 'course_offline_payment';
	const LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT = 'course_verify_offline_payment';

	/*
	 * Amount and tax calculation based on 12.36% services tax
	 */
	/*const INVOICE_BASIC_NET_AMOUNT = 4450;
	const INVOICE_BASIC_TAX_AMOUNT = 550;*/

	/*
	 * Amount and tax calculation based on 14% services tax
	 */
	const INVOICE_BASIC_NET_AMOUNT = 4386;
	const INVOICE_BASIC_TAX_AMOUNT = 614;
	const INVOICE_BASIC_GROSS_AMOUNT = 5000;

	/*
	 * Amount and tax calculation based on 12.36% services tax
	 */
	/*const INVOICE_PROF_NET_AMOUNT = 13350;
	const INVOICE_PROF_TAX_AMOUNT = 1650;*/
	const INVOICE_PROF_NET_AMOUNT = 13158;
	const INVOICE_PROF_TAX_AMOUNT = 1842;

	const INVOICE_PROF_GROSS_AMOUNT = 15000;

	/*const INVOICE_SERVICE_TAX_RATE = 12.36;*/
	const INVOICE_SERVICE_TAX_RATE = 14;

	const PLAN_BASIC = 3;
	const PLAN_PROFESSIONAL = 2;

	const API_STATUS_CODE_400 = 400;
	const API_STATUS_CODE_557 = 557;
	const API_STATUS_CODE_409 = 409;

	const API_ENDPOINT_LOCAL = 'https://flintv5.com:9001/API';
	const API_ENDPOINT_PRODUCTION = 'https://flinnt.com:9001/API';
	const API_VERSION_1_0 = 'v1.0';
	const API_VERSION_2_0 = 'v2.0';

	const COURSE_IMAGE_LARGE = 1;
	const COURSE_IMAGE_MEDIUM = 2;
	const COURSE_IMAGE_SMALL = 3;
	const COURSE_IMAGE_XLARGE = 4;

	const DIR_WS_COURSE_XLARGE = '419x280/';
	const DIR_WS_COURSE_LARGE = '280x187/';
	const DIR_WS_COURSE_MEDIUM = '240x160/';
	const DIR_WS_COURSE_SMALL = '70x70/';

	const COURSE_IMAGE_SMALL_W = 70;
	const COURSE_IMAGE_SMALL_H = 70;
	const COURSE_IMAGE_MEDIUM_W = 240;
	const COURSE_IMAGE_MEDIUM_H = 160;
	const COURSE_IMAGE_LARGE_W = 280;
	const COURSE_IMAGE_LARGE_H = 187;
	const COURSE_IMAGE_XLARGE_W = 419;
	const COURSE_IMAGE_XLARGE_H = 280;

	const USER_PICTURE_LARGE = 1;
	const USER_PICTURE_MEDIUM = 2;
	const USER_PICTURE_SMALL = 3;

	const DIR_WS_PROFILE_LARGE = 'x140/';
	const DIR_WS_PROFILE_MEDIUM = '75x75/';
	const DIR_WS_PROFILE_SMALL = '40x40/';

	const DIR_WS_PROFILE_COURSE_LARGE = 'x140/';
	const DIR_WS_PROFILE_COURSE_MEDIUM = '75x75/';
	const DIR_WS_PROFILE_COURSE_SMALL = '40x40/';

	const PROFILE_IMAGE_LARGE_W = 140;
	const PROFILE_IMAGE_LARGE_H = 140;
	const PROFILE_IMAGE_MEDIUM_W = 75;
	const PROFILE_IMAGE_MEDIUM_H = 75;
	const PROFILE_IMAGE_SMALL_W = 40;
	const PROFILE_IMAGE_SMALL_H = 40;

	const GALLERY_IMAGE_XLARGE = 1;
	const GALLERY_IMAGE_LARGE = 2;
	const GALLERY_IMAGE_SMALL = 3;

	const DIR_WS_GALLERY_XLARGE = 'xlarge/';
	const DIR_WS_GALLERY_LARGE = '300x200/';
	const DIR_WS_GALLERY_SMALL = '75x75/';
	const DIR_WS_GALLERY_MOBILE = 'mobile/';
	const DIR_WS_GALLERY_NOCROP = 'ncrop/';

	const GALLERY_IMAGE_LARGE_W = 300;
	const GALLERY_IMAGE_LARGE_H = 200;
	const GALLERY_IMAGE_SMALL_W = 75;
	const GALLERY_IMAGE_SMALL_H = 75;

	const USER_ACCOUNT_AUTH_MODE_MOBILE = 'mobile';
	const USER_ACCOUNT_AUTH_MODE_EMAIL = 'email';

	const BKOFF_COURSE_INVITE_READY = 'READY';
	const BKOFF_COURSE_INVITE_QUEUED = 'QUEUED';
	const BKOFF_COURSE_INVITE_NOUSER = 'NOUSER';
	const BKOFF_COURSE_INVITE_INVALID = 'INVALID';
	const BKOFF_COURSE_INVITE_PROCESSED = 'INVITED';
	const BKOFF_COURSE_INVITE_SUBSCRIBED = 'ALREADY_SUBSCRIBED';
	const BKOFF_COURSE_INVITE_DUPLICATE = 'ALREADY_INVITED';
	const BKOFF_COURSE_INVITE_ERROR = 'ERROR';

	const BACKOFFICE_JOB_STATUS_INIT = 0;
	const BACKOFFICE_JOB_STATUS_RUNNING = 1;
	const BACKOFFICE_JOB_STATUS_FINISHED = 2;

	const BACKOFFICE_JOB_COPY_LEARNERS = 'job_copy_learners';

	const COURSE_REVIEW_PENDING = 1;
	const COURSE_REVIEW_ACCEPT = 2;
	const COURSE_REVIEW_REJECT = 3;
	const COURSE_REVIEW_DEACTIVATE = 4;

	const COURSE_STATUS_DRAFT = 1;
	const COURSE_STATUS_PUBLISH = 2;
	const COURSE_STATUS_CLOSE = 3;

	const MAX_DISPLAY_SEARCH_RESULT_INST_ACQ = 25;
	const DEFAULT_COUNTRY_ID = 99;
	const DEVICE_TYPE_ANDROID = 1;
	const DEVICE_TYPE_IPHONE = 2;
	const DEVICE_TYPE_WINDOWS = 3;

	const INST_CALL_VISIT_DELETE_ACTION = 'delete_call_visit';
	const INST_CALL_VISIT_ADMIN_IDS = '1';
	const CATEGORY_ACTIVE = 1;

	const COPY_CONTENT_JOB_NOT_STARTED = 1;
	const COPY_CONTENT_JOB_RUNNING = 2;
	const COPY_CONTENT_JOB_COMPLETED = 3;
	const COPY_CONTENT_JOB_FAILED = 4;

	const COMPLAIN_DELETE_ACTION = 'delete_complain';

	const TRAN_STATUS_INITIALIZED = 0;
	const TRAN_STATUS_IN_SESSION = 1;
	const TRAN_STATUS_PROCESSING = 2;
	const TRAN_STATUS_CANCELLED = 3;
	const TRAN_STATUS_FAILED = 4;
	const TRAN_STATUS_COMPLETED = 5;

	const MAX_DISPLAY_POST_LIST = 10;

	const MOBILE_APP_UPDATES_DELETE_ACTION = 'mobile_app_updates_delete';

	const COURSE_ORDER_SUMMARY_ADMIN_IDS = '1';

	/* For Content preview API call */
	const API_APP_VERSION_1 = 'v1.0';
	const API_LMS_UPDATE_SECTION_CONTENT_PREVIEW = 'lms/user/%1$d/course/%2$d/section/%3$s/content/%4$s/attachment/%5$s';


    const MAX_INSTITUTE_STUDENT_STRENGTH = 99999;
    const PAGINATION_RECORD_COUNT = 10;

	const TESTING_USERS = '7107'; // User id of testing users


	/**
	 * PARAM_ALPHA - contains only english ascii letters a-zA-Z.
	 */
	const PARAM_ALPHA = 'alpha';

	/**
	 * PARAM_ALPHAEXT the same contents as PARAM_ALPHA plus the chars in quotes: '_-' allowed
	 * NOTE: originally this allowed '/' too, please use PARAM_SAFEPATH if '/' needed
	 */
	const PARAM_ALPHAEXT = 'alphaext';

	/**
	 * PARAM_ALPHANUM - expected numbers and letters only.
	 */
	const PARAM_ALPHANUM = 'alphanum';

	/**
	 * PARAM_ALPHANUMEXT - expected numbers, letters only and _-.
	 */
	const PARAM_ALPHANUMEXT = 'alphanumext';

	/**
	 * PARAM_BASE64 - Base 64 encoded format
	 */
	const PARAM_BASE64 = 'base64';

	/**
	 * PARAM_BOOL - converts input into 0 or 1, use for switches in forms and urls.
	 */
	const PARAM_BOOL = 'bool';


	/**
	 * PARAM_FILE - safe file name, all dangerous chars are stripped, protects against XSS, SQL injections and directory traversals
	 */
	const PARAM_FILE = 'file';

	/**
	 * PARAM_FLOAT - a real/floating point number.
	 *
	 * Note that you should not use PARAM_FLOAT for numbers typed in by the user.
	 * It does not work for languages that use , as a decimal separator.
	 * Instead, do something like
	 *     $rawvalue = required_param('name', PARAM_RAW);
	 *     // ... other code including require_login, which sets current lang ...
	 *     $realvalue = unformat_float($rawvalue);
	 *     // ... then use $realvalue
	 */
	const PARAM_FLOAT = 'float';

	/**
	 * PARAM_HOST - expected fully qualified domain name (FQDN) or an IPv4 dotted quad (IP address)
	 */
	const PARAM_HOST = 'host';

	/**
	 * PARAM_INT - integers only, use when expecting only numbers.
	 */
	const PARAM_INT = 'int';

	/**
	 * PARAM_NOTAGS - all html tags are stripped from the text. Do not abuse this type.
	 */
	const PARAM_NOTAGS = 'notags';

	/**
	 * PARAM_PATH - safe relative path name, all dangerous chars are stripped, protects against XSS, SQL injections and directory
	 * traversals note: the leading slash is not removed, window drive letter is not allowed
	 */
	const PARAM_PATH = 'path';

	/**
	 * PARAM_PEM - Privacy Enhanced Mail format
	 */
	const PARAM_PEM = 'pem';

	/**
	 * PARAM_RAW specifies a parameter that is not cleaned/processed in any way except the discarding of the invalid utf-8 characters
	 */
	const PARAM_RAW = 'raw';

	/**
	 * PARAM_RAW_TRIMMED like PARAM_RAW but leading and trailing whitespace is stripped.
	 */
	const PARAM_RAW_TRIMMED = 'raw_trimmed';

	/**
	 * PARAM_SAFEDIR - safe directory name, suitable for include() and require()
	 */
	const PARAM_SAFEDIR = 'safedir';

	/**
	 * PARAM_SAFEPATH - several PARAM_SAFEDIR joined by '/', suitable for include() and require(), plugin paths, etc.
	 */
	const PARAM_SAFEPATH = 'safepath';

	/**
	 * PARAM_SEQUENCE - expects a sequence of numbers like 8 to 1,5,6,4,6,8,9.  Numbers and comma only.
	 */
	const PARAM_SEQUENCE = 'sequence';

	// DEPRECATED PARAM TYPES OR ALIASES - DO NOT USE FOR NEW CODE.
	/**
	 * PARAM_CLEAN - obsoleted, please use a more specific type of parameter.
	 * It was one of the first types, that is why it is abused so much ;-)
	 * @deprecated since 2.0
	 */
	const PARAM_CLEAN = 'clean';

	/**
	 * PARAM_TAG - converts value to sentance case
	 */
	const PARAM_TAG = 'tag';

	/**
	 * PARAM_TAG - converts value to sentance case
	 */
	const PARAM_CATEGORY = 'category';

	/**
	 * PARAM_IST_DATE - check for indian date format i.e. dd/mm/YYYY
	 */
	const PARAM_IST_DATE = 'ist_date';


	/**
	 * PARAM_ENC_RAW - check for indian date format i.e. dd/mm/YYYY
	 */
	const PARAM_ENC_RAW = 'encrypted_raw_text';

	/**
	 * PARAM_ENC_RAW - check for indian date format i.e. dd/mm/YYYY
	 */
	const PARAM_ENC_INT = 'encrypted_integer';

	const FLINNT_LOG_ERROR = 'error';
	const FLINNT_LOG_INFO = 'info';
	const FLINNT_LOG_WARNING = 'warning';
	const FLINNT_LOG_ALERT = 'alert';
	const FLINNT_LOG_EMERGENCY = 'emergency';
	const FLINNT_LOG_CRITICAL = 'critical';
	const FLINNT_LOG_NOTICE = 'notice';
	const FLINNT_LOG_DEBUG = 'debug';

    const USER_COMMISSION_TIME_OUT = '7.00';
    const USER_COMMISSION_SELF_PACED = '50.00';

	const FILENAME_CONTENT_USER_REPORT = 'content_use_report';
	const FILENAME_USERS_LIST = 'users_list';
	const FILENAME_INSTITUTE_LIST = 'institute_list';

	const FILENAME_CONSTANT_CAMBRIDGE_LINGUA_SKILL_REPORT = 'cambridge_lingua_skill';
	const FILENAME_CONSTANT_CAMBRIDGE_REGISTRATION_REPORT = 'cambridge_registration';
	const FILENAME_CONSTANT_CAMBRIDGE_SUBMISSION_REPORT = 'cambridge_submission';
	const MYSQL_DATE_FORMAT = 'Y-m-d';

	const COURSE_SUB_VALID_SUCCESS = 1;
	const COURSE_SUB_VALID_NO_COURSE = 2;
	const COURSE_SUB_VALID_IS_FREE = 4;
	const COURSE_SUB_VALID_NOT_PUBLISHED = 8;
	const COURSE_SUB_VALID_DISABLED = 16;
	const COURSE_SUB_VALID_PLAN_EXPIRED = 32;
	const COURSE_SUB_VALID_MAX_LIMIT = 64;
	const COURSE_SUB_VALID_DATE_EXPIRED = 128;
	const COURSE_SUB_VALID_END_DATE = 256;
	const COURSE_SUB_VALID_REMAINING_SUBSCRIPTION = 512;
	const COURSE_SUB_VALID_INSUFFICIENT_SUBSCRIPTION = 1024;

	const COURSE_TYPE_PRIVATE = 1;
	const COURSE_TYPE_TIMEBOUND = 2;
	const COURSE_TYPE_SELFPACED = 3;
	const USER_COURSE_ROLE_LEARNER = 3;

	const DELETE_OFFLINE_PAYMENT_ACTION = "delete_offline_payment";
	const EXPORT_OFFLINE_COUPON = "export_offline_coupon";
	const EXPORT_VERIFY_OFFLINE = "export_verify_offline";
	const INSTRUMENT_TYPE_CHEQUE = "Cheque";
	const INSTRUMENT_TYPE_DRAFT = "Draft";

	const OFFLINE_PAY_STATUS_DEFAULT = 0;
	const OFFLINE_PAY_STATUS_UNCONFIRMED = 1;
	const OFFLINE_PAY_STATUS_CONFIRMED = 2;
	const OFFLINE_PAY_STATUS_CANCELLED = 3;
	const OFFLINE_PAY_STATUS_INSTRUMENT_INVALID = 4;
