// LoneWalkerLee WoW Registration - client-side validation

const validEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const validUsername = new RegExp(`^[a-zA-Z0-9]{${USERNAME_MIN_LENGTH},${USERNAME_MAX_LENGTH}}$`);
const validPassword = new RegExp(
  `^[a-zA-Z0-9!#$%&'()*+,\\-./:;<=>?@[\\]^_\`{}~]{${PASSWORD_MIN_LENGTH},${PASSWORD_MAX_LENGTH}}$`
);

const usernameHelper = document.getElementById('usernameHelper');
const emailHelper = document.getElementById('emailHelper');
const passwordCharsHelper = document.getElementById('passwordCharsHelper');
const passwordMatchHelper = document.getElementById('passwordMatchHelper');

const inputUsername = document.getElementById('username');
const inputEmail = EMAIL_ENABLED ? document.getElementById('email') : null;
const inputPassword = document.getElementById('password');
const inputConfirmPassword = document.getElementById('passwordRepeat');
const submitButton = document.getElementById('submit');

let usernameTouched = false;
let emailTouched = false;
let passwordTouched = false;
let confirmPasswordTouched = false;

function validateForm() {
  if (!inputUsername || !inputPassword || !inputConfirmPassword || !submitButton) {
    return;
  }

  const username = inputUsername.value;
  const email = inputEmail ? inputEmail.value : '';
  const password = inputPassword.value;
  const confirmPassword = inputConfirmPassword.value;

  const isUsernameValid = validUsername.test(username);
  const isEmailValid = EMAIL_ENABLED ? validEmail.test(email) && email.length <= 255 : true;
  const isPasswordValid = validPassword.test(password);
  const isPasswordMatchValid = password === confirmPassword && confirmPassword.length > 0;

  if (usernameTouched) {
    usernameHelper.classList.toggle('text-danger', !isUsernameValid);
    usernameHelper.classList.toggle('text-success', isUsernameValid);
    usernameHelper.textContent = isUsernameValid
      ? '✓ 账号格式正确'
      : `账号需要 ${USERNAME_MIN_LENGTH}～${USERNAME_MAX_LENGTH} 位英文字母或数字`;
  }

  if (EMAIL_ENABLED && emailTouched && inputEmail) {
    const emailValid = validEmail.test(email) && email.length <= 255;
    emailHelper.classList.toggle('text-danger', !emailValid);
    emailHelper.classList.toggle('text-success', emailValid);
    emailHelper.textContent = emailValid
      ? '✓ 邮箱格式正确'
      : '请输入有效的邮箱地址，长度不能超过 255 个字符';
  }

  if (passwordTouched) {
    passwordCharsHelper.classList.toggle('text-danger', !isPasswordValid);
    passwordCharsHelper.classList.toggle('text-success', isPasswordValid);
    passwordCharsHelper.textContent = isPasswordValid
      ? '✓ 密码格式正确'
      : `密码需要 ${PASSWORD_MIN_LENGTH}～${PASSWORD_MAX_LENGTH} 位，可使用字母、数字及常用特殊字符`;
  }

  if (passwordTouched && confirmPasswordTouched) {
    passwordMatchHelper.classList.toggle('text-danger', !isPasswordMatchValid);
    passwordMatchHelper.classList.toggle('text-success', isPasswordMatchValid);
    passwordMatchHelper.textContent = isPasswordMatchValid
      ? '✓ 两次密码一致'
      : '两次输入的密码不一致';
  }

  submitButton.disabled = !(isUsernameValid && isEmailValid && isPasswordValid && isPasswordMatchValid);
}

function setTheme(theme) {
  const normalizedTheme = theme === 'light' ? 'light' : 'dark';

  document.body.classList.toggle('dark-mode', normalizedTheme === 'dark');
  document.body.classList.toggle('light-mode', normalizedTheme === 'light');

  const toggle = document.getElementById('themeToggle');
  if (toggle) {
    toggle.textContent = normalizedTheme === 'dark' ? '☀' : '☾';
    toggle.setAttribute(
      'aria-label',
      normalizedTheme === 'dark' ? '切换到明亮主题' : '切换到暗色主题'
    );
  }

  localStorage.setItem('theme', normalizedTheme);
}

function getTheme() {
  return localStorage.getItem('theme') || DEFAULT_THEME || 'dark';
}

if (inputUsername) {
  inputUsername.addEventListener('input', () => {
    usernameTouched = true;
    validateForm();
  });
}

if (inputEmail) {
  inputEmail.addEventListener('input', () => {
    emailTouched = true;
    validateForm();
  });
}

if (inputPassword) {
  inputPassword.addEventListener('input', () => {
    passwordTouched = true;
    validateForm();
  });
}

if (inputConfirmPassword) {
  inputConfirmPassword.addEventListener('input', () => {
    confirmPasswordTouched = true;
    validateForm();
  });
}

document.addEventListener('DOMContentLoaded', () => {
  setTheme(getTheme());

  const themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      setTheme(getTheme() === 'dark' ? 'light' : 'dark');
    });
  }

  validateForm();
});
