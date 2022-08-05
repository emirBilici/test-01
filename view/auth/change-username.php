<!doctype html>
<html lang="en">
<head>
    <title><?=Title('Kullanıcı Adı Değiştir')?></title>
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
                <form class="auth" id="change-username">
                    <h2>Kullanıcı Adı Değiştirme Süreci</h2>
                    <div class="form-field">
                        <label for="pass">Şifreniz</label>
                        <input type="password" id="pass" autocomplete="current-password">
                        <small class="error">Hata!</small>
                    </div>
                    <div class="form-field">
                        <label for="newUsername">Yeni Kullanıcı Adınız</label>
                        <input type="text" id="newUsername" autocomplete="username">
                        <small class="error">Hata!</small>
                    </div>
                    <button type="submit">Kullanıcı Adımı Değiştir</button>
                </form>
            </section>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

<script type="module">
    import Validation from "../../public/js/Validation.js";
    import Alert from "../../public/js/Alert.js";
    new Validation(document.getElementById("change-username"), async (value) => {
        // handle signup
        let object = {}
        value.forEach(({data}) => {
            for (const datum of data) {
                const [key, value] = datum;
                object[key] = value
            }
        });

        const res = await fetch('/change-username', {
            method: 'post',
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json'
            },
            body: JSON.stringify({
                ...object,
                submit: 1
            })
        }), response = await res.json();

        if (response === 'incorrect_password') {
            let a = new Alert('danger');
            a.setMessage('Şifre yanlış');
            a.showAlert();
        } else if (response === 'username_exist') {
            let a = new Alert('danger');
            a.setMessage('Kullanıcı adı kullanılıyor');
            a.showAlert();
        } else if (response === 'error!') {
            let a = new Alert('danger');
            a.setMessage('Bir sorun oluştu');
            a.showAlert();
        } else {
            location.href = '/logout';
        }
    });
</script>

</body>
</html>