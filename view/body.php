<body>
    <form action = '' method="POST">
        <label>
            Введите ссылку:<br>
            <input type='text' placeholder='stasich.ru' name = 'link'>
        </label>
        <br>
        <label>
            Введите желаемое имя ссылки:<br>
            <input type='text' placeholder='mylink' name = 'hash'>
        </label>
        <br>
        <input type='text' name = 'token' value='<?php echo $token; ?>' hidden>
        <input type='submit' value = 'получить ссылку'>
    </form>
