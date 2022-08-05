<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=Title("Entry'i raporla - #" . $data->puID)?></title>
    <style>
        * {
            font-family: 'Avenir Next', sans-serif;
        }
        #submit_form {
            background-color: #e3e3e3;
            border: 0.25px #000 solid;
            margin: 0 auto;
            float: left;
            padding: 1rem;
            box-shadow: #000 0 0 20px;
            max-width: 40.25rem;
            transition: 0.2s;
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            top: 2em;
        }
        #submit_form:hover {
            padding: 2rem;
            box-shadow: #000 0 0 35px;
        }
        h1 {
            background-color: #a3a3a3;
            padding: 1rem;
            border: 0.25px solid #000;
            box-shadow: inset #000 0 0 7px;
            transition: 0.2s;
        }
        h1:hover {
            box-shadow: inset #000 0 0 15px;
        }
    </style>
</head>
<body>

<main>
    <div id="submit_form">
        <form>
            <h2>Rapor et - <?=$data->post_title?> - #<?=$data->puID?></h2>
            <div class="form-field">
                <textarea name="" cols="60" rows="10" placeholder="Neden raporlamak istediğinizi yazın.." style="width:100%"></textarea>
                <small class="error" style="display:none">Bu alanın doldurulması zorunludur.</small>
            </div>
            <p>Site içerisindeki olumsuz durumları görüp raporladığınız için teşekkür ederiz.</p>
            <input type="hidden" name="key" value="<?=$data->puID?>">
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
</main>

<script type="module">
    import Validation from "/public/js/Validation.js";
    (function() {
        "use strict";
        const form = document.querySelector('form');
        new Validation(form, value => {
            let data = value[0].data[0][1]
                , len = data.trim().length;
            if (len < 60 || len > 600) {
                alert('Açıklama 60-600 karakter aralığında olmalıdır..');
            } else {
                location.href = `/report?submit=1&postKey=<?=$data->puID?>&reporterMsg=${data.trim()}`;
            }
        });
    })()
</script>

</body>
</html>