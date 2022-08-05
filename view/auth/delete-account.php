<!doctype html>
<html lang="en">
<head>
    <title><?=Title('Hesabımı Sil')?></title>
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
                <div class="useful-links" style="padding: 12px 0;">
                    <a href="#" style="grid-gap: 8px;" onclick="history.go(-1)">
                        <i aria-hidden="true" class="bi bi-arrow-left"></i>
                        Geri git
                    </a>
                </div>
                <form class="auth" id="delete-account">
                    <h2>Hesabımı Sil</h2>
                    <div class="form-field">
                        <label for="pass">Şifre</label>
                        <input type="password" id="pass" autocomplete="off">
                        <p style="font-size:12px;color:#444;padding-top:4px;">Hesabınızı silmek için şifrenizi girmelisiniz.</p>
                        <small class="error">Hata!</small>
                    </div>
                    <button type="submit" class="danger">Sil</button>
                </form>
            </section>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

<script type="module">
    import Validation from "../../public/js/Validation.js";
    new Validation(document.getElementById("delete-account"), () => {
        // handle signup
        alert("Handle Delete Account");
    });
</script>

</body>
</html>