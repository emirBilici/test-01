<!doctype html>
<html lang="en">
<head>
    <title><?=Title('Şifre Değiştir')?></title>
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
                <form class="auth" id="change-password">
                    <h2>Şifre Değiştirme Süreci</h2>
                    <div class="form-field">
                        <label for="oldPass">Eski şifre</label>
                        <input type="password" id="oldPass" autocomplete="current-password" data-novalidate="true">
                        <small class="error">Hata!</small>
                    </div>
                    <div class="form-field">
                        <label for="currPass">Yeni şifre</label>
                        <input type="password" id="currPass" autocomplete="new-password">
                        <small class="error">Hata!</small>
                    </div>
                    <div class="form-field">
                        <label for="confPass">Yeni şifre (Tekrar)</label>
                        <input type="password" id="confPass" autocomplete="new-password" data-confirm-password="currPass">
                        <small class="error">Hata!</small>
                    </div>
                    <button type="submit">Şifremi Değiştir</button>
                </form>
            </section>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

<script type="module">
    import Validation from "../../public/js/Validation.js";
    import Alert from "../../public/js/Alert.js";
    new Validation(document.getElementById("change-password"), async (value) => {
        // handle signup
        let object = {}
        value[0].data.forEach(data => {
            const [id, pass] = data;
            object[id] = pass
        });
        const res = await fetch('/change-password', {
            method: 'post',
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json'
            },
            body: JSON.stringify({
                ...object,
                submit: 1
            })
        }), response = await res.json()

        if (response === 'updated!') {
            location.href = '/logout';
        }
        if (response === 'error!') {
            let a = new Alert('danger');
            a.setMessage('Şifre değiştirilirken bir sorun oluştu')
            a.showAlert();
        }
    });
</script>

</body>
</html>