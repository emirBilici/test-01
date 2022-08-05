<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=Title('Notify user - report', 'admin')?></title>
</head>
<body>

<form action="/notify/report" method="post">
    <h1>Kullanıcıya rapor hakkında bildirim gönder!</h1>
    <textarea name="text" cols="50" rows="10" placeholder="Bilgilendirmek istediğin mesajı yaz!"></textarea>
    <input type="hidden" name="notifyUser" value="<?=$data->reporter_id?>">
    <input type="hidden" name="reportedPostKey" value="<?=$data->reported_post_key?>"><br><br>
    <button type="submit">Gönder</button>
</form>

</body>
</html>