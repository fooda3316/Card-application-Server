<?php
define('DB_USERS_USERNAME', 'fooda');

define('DB_USERNAME', 'onlihnmd');
define('DB_PASSWORD', 'MOyopRAWvcMF');
define('DB_HOST', 'localhost');
define('DB_NAME', 'onlihnmd_billing_db');
define('USERS_DB_NAME', 'onlihnmd_users');

// define('UPLOAD_IMPROVEMENT_PATH', 'http://onlinesd.store/billing/ImageUploadApi/uploads/improvement/');
// define('UPLOAD_AUT_PATH', 'https://onlinesd.store/billing/ImageUploadApi/uploads/registrations/');
//define('UPLOAD_IMPROVEMENT_PATH', '/uploads/improvement/');
// define('UPLOAD_AUT_PATH', '/uploads/registrations/');
define('UPLOAD_IMPROVEMENT_PATH', '/uploads/improvement/');
define('UPLOAD_AUT_PATH', '/uploads/registrations/');
define('UPLOAD_CARD_PATH', '/uploads/cards/');
define('UPLOAD_PAGE_PATH', '/uploads/pages/');

define('USER_CREATED', 101);
define('USER_EXIST', 102);
define('USER_CREATION_FAILED', 103);
define('USER_AUTHENTICATED', 201);
define('USER_NOT_FOUND', 202);
define('USER_PASSWORD_DO_NOT_MATCH', 203);
define('REQUEST_HAS_SENT', 301);
define('REQUEST_HAS_NOT_SENT', 302);
define('REQUEST_HAS_SAVED', 303);
define('PURCHASE_COMPLETED', 401);
define('PURCHASE_FAILED', 402);
define('LESS_BALANCE', 403);
define('CARD_NOT_FOUND', 404);
define('CARD_ADDED', 501);
define('CARD_NOT_ADDED', 502);
define('PAGE_ADDED', 601);
define('PAGE_NOT_ADDED', 602);