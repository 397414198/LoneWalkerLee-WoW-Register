<?php
require_once __DIR__ . '/config.php';

// Start a session for CSRF protection.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'httponly' => true,
        'secure' => COOKIE_SECURE,
        'samesite' => 'Lax',
        'path' => '/'
    ]);
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check database availability without exposing the actual PDO error to visitors.
try {
    $conn = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('[LoneWalkerLee WoW Register] Database connection failed: ' . $e->getMessage());
    header('Location: db_error', true, 302);
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="theme-color" content="#07111f">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
</head>

<body class="dark-mode">
  <main class="register-page">
    <div class="container py-4 py-md-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">

          <section class="custom-card">
            <div class="card-glow"></div>

            <header class="site-header">
              <div>
                <div class="brand-kicker">⚔️ AZEROTHCORE · 3.3.5a</div>
                <h1><?= htmlspecialchars($slogan, ENT_QUOTES, 'UTF-8') ?></h1>
                <p><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
              </div>

              <button id="themeToggle" type="button" class="theme-toggle" aria-label="切换主题">
                ☾
              </button>
            </header>

            <div class="ice-divider"><span>❄</span></div>

            <?php include __DIR__ . '/app/controllers/register.php'; ?>

            <form action="" method="post" id="registerForm" novalidate>
              <input type="hidden" name="csrf_token"
                     value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

              <div class="form-section-title">🎮 创建游戏账号</div>

              <div class="form-group mb-4">
                <label for="username" class="form-label">游戏账号</label>
                <input type="text"
                       class="form-control"
                       id="username"
                       name="username"
                       maxlength="<?= USERNAME_MAX_LENGTH ?>"
                       minlength="<?= USERNAME_MIN_LENGTH ?>"
                       autocomplete="username"
                       required>
                <div id="usernameHelper" class="form-text"></div>
              </div>

              <?php if (EMAIL_ENABLED): ?>
              <div class="form-group mb-4">
                <label for="email" class="form-label">电子邮箱</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       maxlength="255"
                       autocomplete="email"
                       required>
                <div id="emailHelper" class="form-text"></div>
              </div>
              <?php endif; ?>

              <div class="form-group mb-4">
                <label for="password" class="form-label">登录密码</label>
                <input type="password"
                       class="form-control"
                       id="password"
                       name="password"
                       maxlength="<?= PASSWORD_MAX_LENGTH ?>"
                       minlength="<?= PASSWORD_MIN_LENGTH ?>"
                       autocomplete="new-password"
                       required>
                <div id="passwordCharsHelper" class="form-text"></div>
              </div>

              <div class="form-group mb-4">
                <label for="passwordRepeat" class="form-label">确认密码</label>
                <input type="password"
                       class="form-control"
                       id="passwordRepeat"
                       name="passwordRepeat"
                       maxlength="<?= PASSWORD_MAX_LENGTH ?>"
                       autocomplete="new-password"
                       required>
                <div id="passwordMatchHelper" class="form-text"></div>
              </div>

              <div class="rules-box">
                <div class="rules-title">📜 注册说明</div>
                <ul>
                  <li>账号长度：<?= USERNAME_MIN_LENGTH ?>～<?= USERNAME_MAX_LENGTH ?> 个字符。</li>
                  <li>账号仅支持英文字母和数字。</li>
                  <li>密码长度：<?= PASSWORD_MIN_LENGTH ?>～<?= PASSWORD_MAX_LENGTH ?> 个字符。</li>
                  <li>密码支持字母、数字及常用特殊字符。</li>
                  <li>本注册站不要求填写邮箱。</li>
                  <li>注册完成后即可使用游戏客户端登录。</li>
                </ul>
              </div>

              <button type="submit" id="submit" class="register-button" disabled>
                <span>⚔️</span> 创建艾泽拉斯账号
              </button>
            </form>

            <footer class="site-footer">
              <div>❄ LoneWalkerLee · AzerothCore</div>
              <small>欢迎来到属于你的艾泽拉斯。</small>
            </footer>
          </section>

        </div>
      </div>
    </div>
  </main>

  <script>
    const USERNAME_MIN_LENGTH = <?= USERNAME_MIN_LENGTH ?>;
    const USERNAME_MAX_LENGTH = <?= USERNAME_MAX_LENGTH ?>;
    const PASSWORD_MIN_LENGTH = <?= PASSWORD_MIN_LENGTH ?>;
    const PASSWORD_MAX_LENGTH = <?= PASSWORD_MAX_LENGTH ?>;
    const EMAIL_ENABLED = <?= EMAIL_ENABLED ? 'true' : 'false' ?>;
    const DEFAULT_THEME = <?= json_encode(strtolower(DEFAULT_THEME), JSON_UNESCAPED_UNICODE) ?>;
  </script>
  <script src="assets/js/script.js"></script>
</body>
</html>
