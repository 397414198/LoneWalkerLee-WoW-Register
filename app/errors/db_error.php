<?php
require_once __DIR__ . '/../../config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#07111f">
  <title>服务暂时不可用 · LoneWalkerLee</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEisjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjR0J6hW+ALEwIH"
        crossorigin="anonymous">
</head>
<body class="dark-mode">
  <main class="register-page">
    <div class="container py-4 py-md-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
          <section class="custom-card">
            <header class="site-header">
              <div>
                <div class="brand-kicker">⚔️ LONEWALKERLEE · AZEROTHCORE</div>
                <h1>服务暂时不可用</h1>
                <p>注册服务当前无法连接游戏数据库，请稍后再试。</p>
              </div>
              <button id="themeToggle" type="button" class="theme-toggle" aria-label="切换主题">☾</button>
            </header>

            <div class="ice-divider"><span>❄</span></div>

            <div class="register-alert mx-4 mx-md-5 mb-4 alert" role="alert">
              <strong>数据库连接失败</strong>
              <p class="mb-0 mt-2">
                这是服务器端连接问题，不是你的账号或密码问题。
                请联系服务器管理员检查注册服务。
              </p>
            </div>

            <div class="px-4 px-md-5 pb-4">
              <a href="../../index.php" class="register-button d-flex align-items-center justify-content-center text-decoration-none">
                返回注册页面
              </a>
            </div>

            <footer class="site-footer">
              <div>❄ LoneWalkerLee · AzerothCore</div>
              <small>艾泽拉斯正在等待你的归来。</small>
            </footer>
          </section>
        </div>
      </div>
    </div>
  </main>

  <script>
    const DEFAULT_THEME = <?= json_encode(strtolower(DEFAULT_THEME), JSON_UNESCAPED_UNICODE) ?>;

    function setTheme(theme) {
      const normalized = theme === 'light' ? 'light' : 'dark';
      document.body.classList.toggle('dark-mode', normalized === 'dark');
      document.body.classList.toggle('light-mode', normalized === 'light');

      const toggle = document.getElementById('themeToggle');
      if (toggle) {
        toggle.textContent = normalized === 'dark' ? '☀' : '☾';
      }

      localStorage.setItem('theme', normalized);
    }

    document.addEventListener('DOMContentLoaded', function () {
      setTheme(localStorage.getItem('theme') || DEFAULT_THEME);

      const toggle = document.getElementById('themeToggle');
      if (toggle) {
        toggle.addEventListener('click', function () {
          setTheme((localStorage.getItem('theme') || DEFAULT_THEME) === 'dark' ? 'light' : 'dark');
        });
      }
    });
  </script>
</body>
</html>
