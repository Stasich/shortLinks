<body>
    <div id = 'form'>
        <form action = '' method="POST">
            <label>
                Введите ссылку:<br>
                <input type='text' placeholder='stasich.ru' name = 'link' required>
            </label>
            <br>
            <label>
                Введите желаемое имя ссылки:<br>
                <input type='text' placeholder='mylink' name = 'hash'>
            </label>
            <br>
            <input type='text' name = 'token' value='<?php echo $token; ?>' hidden>
            <!-- для того что бы работало без js, сменить на type='submit'
             и убрать подключение js-ников-->
            <input type='submit' value = 'получить ссылку'>
        </form>
    </div>