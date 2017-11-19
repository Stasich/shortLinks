<!doctype>
<html>
<head>
    <meta charset = 'utf-8'>
    <title>ShortLinks</title>
    <link href = 'style.css' rel = 'stylsheet'>
</head>
<body>
<?php if ($redirect === null) echo "Нет соответсвия с короткой ссылкой. Попробуйт создать ещё раз.<br>\n"?>
    <form action = '' method="POST">
        <label>
            Введите ссылку: http://
            <input type='text' placeholder='stasich.ru' name = 'link'>
        </label>
        <input type='text' name = 'token' value='<?php echo $token; ?>' hidden>
        <input type='submit' value = 'ok'>
    </form>
    <div <?php if (!isset($shortLink)) echo 'hidden'?>>
        <p>Ваша ссылка: <?php if (isset($shortLink)) echo "<a href ='$shortLink'>$shortLink</a>"?></p>
    </div>
</body>
</html>