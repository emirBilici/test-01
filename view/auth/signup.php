<!doctype html>
<html lang="en">
<head>
    <title><?=Title('Kayıt ol')?></title>
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
                <form class="auth" id="sign-up">
                    <h2>Kayıt Ol</h2>
                    <button type="button" class="login-with-google-btn">
                        Google ile kayıt ol
                    </button>
                    <button type="button" class="login-with-google-btn github">
                        Github ile kayıt ol
                    </button>
                    <div id="form-error" style="display: none;">Bir sorun oluştu ve kayıt olamadınız, lütfen tekrar deneyin.</div>
                    <div class="form-field">
                        <label for="userName">Kullanıcı Adı</label>
                        <input type="text" id="userName" autocomplete="username" data-check="username">
                        <small class="error">Hata!</small>
                    </div>
                    <div class="form-field">
                        <label for="emailAd">Email Adresi</label>
                        <input type="email" id="emailAd" autocomplete="email">
                        <small class="error">Hata!</small>
                    </div>
                    <div class="form-field password">
                        <label for="newPass">Şifre</label>
                        <input type="password" id="newPass" autocomplete="new-password" data-novalidate="true">
                    </div>
                    <div class="password-check">
                        <span class="rule">Şifre en az 8 karakter</span>
                        <span class="rule">en az bir büyük harf</span>
                        <span class="rule">bir küçük harf</span>
                        <span class="rule">rakam içermelidir.</span>
                    </div>
                    <div class="form-field">
                        <label for="confirmPass">Şifre (Tekrar)</label>
                        <input type="password" id="confirmPass" autocomplete="new-password" data-novalidate="true">
                    </div>
                    <div class="password-check">
                        <span class="rule" data-rule="lowercase">En az 1 küçük harf</span>
                        <span class="rule" data-rule="uppercase">En az 1 büyük harf</span>
                        <span class="rule" data-rule="number">En az 1 rakam</span>
                        <span class="rule" data-rule="count">En az [8-16] karakter</span>
                    </div>
                    <button type="submit" id="submit-btn">Gönder</button>
                </form>
                <div class="useful-links">
                    <div>
                        Zaten bir hesabınız var mı? <a href="/login">Giriş yap</a>
                    </div>
                </div>
            </section>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

</body>
</html>