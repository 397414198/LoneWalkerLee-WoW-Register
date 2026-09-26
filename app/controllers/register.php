<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name(SESSION_NAME);
    session_start();
}

// CSRF protection.
$csrfToken = $_POST['csrf_token'] ?? '';
if (
    empty($_SESSION['csrf_token']) ||
    !is_string($csrfToken) ||
    !hash_equals($_SESSION['csrf_token'], $csrfToken)
) {
    echo "<div class='alert alert-danger register-alert' role='alert'>安全校验失败，请刷新页面后重试。</div>";
    return;
}

// Read raw inputs. Passwords are intentionally not trimmed.
$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$passwordRepeat = (string)($_POST['passwordRepeat'] ?? '');

if (EMAIL_ENABLED) {
    $emailInput = trim((string)($_POST['email'] ?? ''));

    if ($emailInput === '' || strlen($emailInput) > 255 || !filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger register-alert' role='alert'>请输入有效的电子邮箱地址。</div>";
        return;
    }

    $email = strtoupper($emailInput);
} else {
    $email = '';
}

// Username: AzerothCore account names are traditionally ASCII letters/numbers.
if (
    strlen($username) < USERNAME_MIN_LENGTH ||
    strlen($username) > USERNAME_MAX_LENGTH ||
    !preg_match('/^[A-Za-z0-9]+$/', $username)
) {
    echo "<div class='alert alert-danger register-alert' role='alert'>游戏账号必须为 " .
         USERNAME_MIN_LENGTH . "～" . USERNAME_MAX_LENGTH . " 位英文字母或数字。</div>";
    return;
}

// Password length / allowed characters.
if (
    strlen($password) < PASSWORD_MIN_LENGTH ||
    strlen($password) > PASSWORD_MAX_LENGTH ||
    !preg_match("/^[A-Za-z0-9!#$%&'()*+,\-.\/:;<=>?@[\]^_`{}~]+$/", $password)
) {
    echo "<div class='alert alert-danger register-alert' role='alert'>密码长度或字符不符合要求，请检查后重试。</div>";
    return;
}

// Confirm password before doing any SRP6 work.
if (!hash_equals($password, $passwordRepeat)) {
    echo "<div class='alert alert-danger register-alert' role='alert'>两次输入的密码不一致。</div>";
    return;
}

$username = strtoupper($username);

// Check username before generating SRP6 data.
if (Auth::checkUsername($username)) {
    echo "<div class='alert alert-danger register-alert' role='alert'>账号 <b>" .
         htmlspecialchars($username, ENT_QUOTES, 'UTF-8') .
         "</b> 已经存在，请换一个账号。</div>";
    return;
}

if (EMAIL_ENABLED && Auth::checkEmail($email)) {
    echo "<div class='alert alert-danger register-alert' role='alert'>这个邮箱已经被使用，请换一个邮箱。</div>";
    return;
}

// Keep the original AzerothCore-compatible SRP6 registration algorithm.
try {
    [$salt, $verifier] = SRP6::getRegistrationData($username, $password);
} catch (Throwable $e) {
    error_log('[LoneWalkerLee WoW Register] SRP6 generation failed: ' . $e->getMessage());
    echo "<div class='alert alert-danger register-alert' role='alert'>账号创建失败，请稍后再试。</div>";
    return;
}

if (Auth::Register($username, $email, $salt, $verifier)) {
    echo "<div class='register-success' role='alert'>
            <div class='success-icon'>⚔️</div>
            <h2>账号创建成功</h2>
            <p>欢迎来到艾泽拉斯，账号 <strong>" .
            htmlspecialchars($username, ENT_QUOTES, 'UTF-8') .
            "</strong> 已创建。</p>
            <p class='mb-0'>现在可以打开《魔兽世界 3.3.5a》客户端登录游戏。</p>
          </div>";
    return;
}

echo "<div class='alert alert-danger register-alert' role='alert'>账号创建失败，请稍后再试。</div>";
?>
