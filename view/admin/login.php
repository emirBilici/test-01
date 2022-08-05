<!doctype html>
<html lang="en">
<head>
    <title>~admin~</title>
    <script type="module" defer>
        import Validation from "/public/js/Validation.js";
        (async function() {
            "use strict";
            const $post = async (url, data) => {
                const res = await fetch(url, {
                    method: 'post',
                    headers: {
                        'accept': 'application/json',
                        'content-type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                return await res.json();
            }
            const form = document.querySelector('form');
            const handleLogin = async (arr) => {
                let $data = {}
                arr.forEach(({data: val}) => {
                    for (let i = 0; i < val.length; i++) {
                        const [id, value] = val[i];
                        $data[id] = value
                    }
                });
                let jdata = await $post('/admin', {
                    ...$data,
                    submit: 'check_admin'
                });

                if (!jdata.status) {
                    location.href = '/';
                } else {
                    location.reload();
                }
            }
            new Validation(form, handleLogin);
        })()
    </script>
    <style>
        .form-field:not(.error) small.error {
            display: none;
        }
    </style>
</head>
<body>

<h2>~login~</h2>
<form>
    <div class="form-field">
        <label>
            <input type="email" autocomplete="off" placeholder="Email" id="email">
            <small class="error"></small>
        </label>
    </div>
    <br>
    <div class="form-field">
        <label>
            <input type="password" autocomplete="off" placeholder="Password" id="password">
            <small class="error"></small>
        </label>
    </div>
    <br>
    <button type="submit">Gönder</button>
</form>

</body>
</html>