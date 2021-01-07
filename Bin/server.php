#!/usr/bin/env php
<?php

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
! defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);
! defined('CONFIG_PATH') && define('CONFIG_PATH', BASE_PATH . '/App/Config/');
! defined('APP_PATH') && define('APP_PATH',BASE_PATH . '/App/');

require BASE_PATH . '/vendor/autoload.php';

\Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_FLAGS);

\PTFramework\Application::run();
