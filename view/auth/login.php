<!doctype html>
<html lang="en">
<head>
    <title><?=Title('Giriş Yap')?></title>
    <?php require_once realpath('.') . '/static/head/require.php'; ?>
    <script src="/public/js/Authentication.js" type="module" defer></script>
</head>
<body>

<?php require_once realpath('.') . '/static/loader.php'; ?>
<?php require_once realpath('.') . '/static/header.php'; ?>
<div id="container">
    <?php require_once realpath('.') . '/static/sidebar.php'; ?>
    <div id="main">
        <div id="content" class="auth">
            <section id="content-body">
                <form class="auth" id="log-in">
                    <h2>Giriş yap</h2>
                    <button type="button" class="login-with-google-btn">
                        Google ile giriş yap
                    </button>
                    <button type="button" class="login-with-google-btn github">
                        Github ile giriş yap
                    </button>
                    <div id="form-error" style="display: none;"></div>
                    <div class="form-field">
                        <label for="username_or_email">Kullanıcı Adı / Email</label>
                        <input type="text" id="username_or_email" autocomplete="email" data-check="username/email">
                        <small class="error">Hata!</small>
                    </div>
                    <div class="form-field password">
                        <label for="currPass">Şifre</label>
                        <input type="password" id="currPass" autocomplete="current-password">
                        <small class="error">Hata!</small>
                    </div>
                    <div class="form-field checkbox">
                        <input type="checkbox" id="rememberMe">
                        <label for="rememberMe">Beni hatırla</label>
                    </div>
                    <button type="submit" id="submit-btn">Gönder</button>
                </form>
                <div class="useful-links">
                    <a href="/reset-password">Şifremi unuttum</a>
                    <div>
                        Hala bir hesabınız yok mu? <a href="/signup">Kayıt ol</a>
                    </div>
                </div>
            </section>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

</body>
</html>