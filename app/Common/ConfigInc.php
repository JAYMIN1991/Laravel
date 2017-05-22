<?php
const HTTP_SERVER = 'https://localhost:8080';
const DIR_WS_CATALOG = '/flinnt_backoffice/';
const DIR_WEB_LINK = 'https://flintv5.com:9001/';
const HTTP_API = 'https://flintv5.com:9001/API';
const HTTP_SERVER_CATALOG = 'https://flintv5.com:9001/';
const DIR_WS_IMAGES = 'images/';
const DIR_WS_INCLUDES = 'includes/';
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_LIB', DIR_WS_INCLUDES . 'lib/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_TEMPLATES', 'templates/');
define('DIR_WS_JSCRIPT', DIR_WS_TEMPLATES . 'jscript/');
define('DIR_WS_SLIDER', DIR_WS_TEMPLATES . DIR_WS_IMAGES . 'slider/');
const DIR_WS_QUIZ = 'quiz/';

const DIR_FS_CATALOG = '/media/d/Projects/PHPProjects/flinnt_backoffice_laravel/';
define('DIR_WS_CSS', DIR_WS_TEMPLATES . 'css/');
const DIR_WS_RESOURCES = '';
define('DIR_WS_RESOURCES_TEMP', DIR_WS_RESOURCES . 'temp/');
define('DIR_WS_RESOURCES_COURSES', DIR_WS_RESOURCES . 'courses/');
define('DIR_WS_RESOURCES_AUDIO', DIR_WS_RESOURCES . 'audio/');
define('DIR_WS_RESOURCES_VIDEO', DIR_WS_RESOURCES . 'video/');
define('DIR_WS_RESOURCES_GALLERY', DIR_WS_RESOURCES . 'gallery/');
define('DIR_WS_RESOURCES_PROFILE_IMAGE', DIR_WS_RESOURCES . 'profile_image/');
define('DIR_WS_RESOURCES_PROFILE_IMAGE_COURSE', DIR_WS_RESOURCES_PROFILE_IMAGE . 'courses/');
define('DIR_WS_RESOURCES_DOCS', DIR_WS_RESOURCES . 'docs/');
const DIR_WS_RESOURCES_WORD_TEMPLATES = 'resources/word_templates/';
const DIR_WS_RESOURCES_EXCEL_TEMPLATES = 'resources/excel_templates/';
const DIR_WS_INVOICES = "resources/invoices/";

define('DIR_FS_RESOURCES', DIR_FS_CATALOG . 'resources/');
define('DIR_FS_RESOURCES_TEMP', DIR_FS_RESOURCES . 'temp/');
define('DIR_FS_RESOURCES_COURSES', DIR_FS_RESOURCES . 'courses/');
define('DIR_FS_RESOURCES_AUDIO', DIR_FS_RESOURCES . 'audio/');
define('DIR_FS_RESOURCES_VIDEO', DIR_FS_RESOURCES . 'video/');
define('DIR_FS_RESOURCES_GALLERY', DIR_FS_RESOURCES . 'gallery/');
define('DIR_FS_RESOURCES_PROFILE_IMAGE', DIR_FS_RESOURCES . 'profile_image/');
define('DIR_FS_RESOURCES_PROFILE_IMAGE_COURSE', DIR_FS_RESOURCES_PROFILE_IMAGE . 'courses/');
define('DIR_FS_RESOURCES_DOCS', DIR_FS_RESOURCES . 'docs/');
define('DIR_FS_EXTERNAL_WEB_TOOLS', DIR_FS_CATALOG . '_ext_web_tools/');
define('DIR_FS_YUI_COMPRESSOR', DIR_FS_EXTERNAL_WEB_TOOLS . 'yuicompressor/');
define('DIR_FS_INVOICES', DIR_FS_RESOURCES . "invoices/");

const DIR_FS_CELAT = 'media/d/Projects/PHPProjects/Cambridge_English/';
define('DIR_FS_CELAT_SUBMISSION', DIR_FS_CELAT . "static/submissions/");

const HTTP_RESOURCE = "https://flinnt1.s3.amazonaws.com";
const AWS_S3_RESOURCE_BUCKET = 'flinnt1';
const AWS_S3_CACHE_BUCKET = 'flinnt-cache1';
const DIR_WS_RESOURCE_CATALOG = '/';

const DIR_FS_MOBILE_APP = 'media/d/Projects/PHPProjects/flinnt_mobile_v3/';
define('DIR_FS_MOBILE_APP_TEMP', DIR_FS_MOBILE_APP . 'temp/');
const DIR_FS_FLINNT = 'media/d/Projects/PHPProjects/flinnt_v4/';

define('DIR_FS_LOGS', DIR_FS_CATALOG . 'logs/');
define('DIR_FS_APP_LOG', DIR_FS_LOGS . 'app/');
define('DIR_FS_CRON_LOG', DIR_FS_LOGS . 'cron/');
define('DIR_FS_API_LOG', DIR_FS_LOGS . 'api/');

define('DIR_FS_CACHE', DIR_FS_CATALOG . 'cache/');
define('DIR_FS_CACHE_DB', DIR_FS_CACHE . 'db');
define('DIR_FS_CACHE_PAGES', DIR_FS_CACHE . 'pages/');

define('DIR_WS_RESOURCES_BUYER_INVOICES', 'buyer_invoices/');
define('DIR_WS_RESOURCES_SELLER_INVOICES', 'seller_invoices/');
define('DIR_WS_RESOURCES_SELLER_OFFLINE_INVOICES', 'seller_offline_invoices/');
define('DIR_WS_RESOURCES_BUYER_OFFLINE_INVOICES', 'buyer_offline_invoices/');
define('DIR_WS_RESOURCES_PROMO_OFFLINE_INVOICES', 'promo_offline_invoices/');

const DB_SERVER = '192.168.1.10';
const DB_SERVER_USERNAME= 'flntrd';
const DB_SERVER_PASSWORD = 'flinnt';
const DB_DATABASE = 'flinnt_v3';
const DB_PREFIX = 'flt_';
const USE_PCONNECT = 'false'; // use persistent connections
const STORE_SESSIONS =  'db'; // use 'db' for best support, or '' for file-based storage

const PHP_CLI_PATH = "C:\PHP549\php.exe";

const URL_REWRITE = 1;

const APP_DEBUG_MODE = 0;
const APP_PAYMENT_TESTMODE = 1;
const CSS_VERSION = '10102015144825';
const APP_DEV_MODE = 'production';

const XOR_ENC_BASE = 943135049;

const MEM_CACHE_HOST = '127.0.0.1';
const MEM_CACHE_PORT = '11211';

const SMS_MAX_CHARS = '160';
const SMS_GATEWAY = 'fullonsms';    //way2sms or fullonsms
const SMS_MAX_SEND_PER_DAY = 100;