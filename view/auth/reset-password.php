<!doctype html>
<html lang="en">
<head>
    <title><?=Title('Şifre Sıfırlama Süreci')?></title>
    <?php require_once realpath('.') . '/static/head/require.php'; ?>
</head>
<body>

<?php require_once realpath('.') . '/static/loader.php'; ?>
<?php require_once realpath('.') . '/static/header.php'; ?>
<div id="container">
    <?php require_once realpath('.') . '/static/sidebar.php'; ?>
    <div id="main">
        <div id="content" class="auth">
            <section id="content-body">
                <form class="auth" id="reset-password">
                    <h2>Şifre Sıfırlama Süreci</h2>
                    <div class="form-field">
                        <label for="emailAd">Email Adresi</label>
                        <input type="email" id="emailAd" autocomplete="email" placeholder="Kayıt olduğunuz email adresinizi yazın" required>
                        <small class="error">Hata!</small>
                    </div>
                    <button type="submit">Mail Gönder</button>
                </form>
                <div class="useful-links">
                    <a href="/login">Giriş yap</a>
                    <div>
                        Hala bir hesabınız yok mu? <a href="/signup">Kayıt ol</a>
                    </div>
                </div>
            </section>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

<script type="module">
    import Validation from "../../public/js/Validation.js";
    new Validation(document.getElementById("reset-password"), () => {
        // handle signup
        alert("Handle Reset Password");
    });
</script>

</body>
</html>