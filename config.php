<?php
/**
 * LoneWalkerLee 魔兽世界注册站
 * AzerothCore 3.3.5a
 *
 * 数据库密码不要提交到 GitHub。
 * 推荐通过环境变量提供：
 *   ACORE_DB_HOST
 *   ACORE_DB_USER
 *   ACORE_DB_PASS
 *   ACORE_DB_NAME
 */

// Database configuration
$host = getenv('ACORE_DB_HOST') ?: '127.0.0.1';
$user = getenv('ACORE_DB_USER') ?: 'acore';
$pass = getenv('ACORE_DB_PASS') ?: '';
$db   = getenv('ACORE_DB_NAME') ?: 'acore_auth';

// Username / password limits compatible with WoW 3.3.5a.
define('USERNAME_MIN_LENGTH', 1);
define('USERNAME_MAX_LENGTH', 17);
define('PASSWORD_MIN_LENGTH', 1);
define('PASSWORD_MAX_LENGTH', 16);

// LoneWalkerLee registration does not require e-mail.
define('EMAIL_ENABLED', false);

// Theme: 'dark' or 'light'
define('DEFAULT_THEME', 'dark');

// Website identity
$title = 'LoneWalkerLee | 魔兽世界注册站';
$slogan = '创建你的艾泽拉斯账号';
$description = '注册一个 AzerothCore 账号，进入属于你的巫妖王之怒世界。';

// Basic security / session settings
define('SESSION_NAME', 'LWL_WOW_REGISTER');
define('COOKIE_SECURE', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'));

// Check required PHP extensions.
if (!extension_loaded('gmp')) {
    die('服务器缺少 GMP 扩展，请联系管理员。');
}

if (!extension_loaded('mbstring')) {
    die('服务器缺少 MBString 扩展，请联系管理员。');
}

if (!extension_loaded('pdo') || !extension_loaded('pdo_mysql')) {
    die('服务器缺少 PDO-MySQL 扩展，请联系管理员。');
}

// Check PHP version.
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('服务器 PHP 版本过低，请升级到 PHP 7.4 或更高版本。');
}

// Initialize models.
require_once __DIR__ . '/app/models/database.model.php';
require_once __DIR__ . '/app/models/srp6.model.php';
require_once __DIR__ . '/app/models/auth.model.php';

// Initialize the database layer.
$database = new Database($host, $user, $pass, $db);
$srp6 = new SRP6();
$auth = new Auth($host, $user, $pass, $db);
?>
