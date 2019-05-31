<?php
/**
 * 自定义一些常量，可以项目中使用
 */

// Constants
! defined('DS') && define('DS', DIRECTORY_SEPARATOR);

// App name
! defined('APP_NAME') && define('APP_NAME', 'w7');

// Project base path
! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
! defined('APP_PATH') && define('APP_PATH', BASE_PATH. DIRECTORY_SEPARATOR. 'app');
! defined('RUNTIME_PATH') && define('RUNTIME_PATH', BASE_PATH. DIRECTORY_SEPARATOR. 'runtime');
! defined('CDN_URL') && define('CDN_URL', "//cdn.w7.cc/");
