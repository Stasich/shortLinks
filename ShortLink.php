<?php
class ShortLink
{
    private static $shortLinkObj = null;
    private static $pdo = null;

    private function __construct($username, $password, $dbname, $charset)
    {
        self::$pdo = new PDO('mysql:host=localhost;dbname='.$dbname.';charset='.$charset, $username, $password);
    }
    private function __clone() {}
    private function __wakeup() {}
    // cтатик функция для создания или возвращения существующего объекта.
    public static function getObjLink($username, $password, $dbname, $charset)
    {
        if (is_null(self::$shortLinkObj))
            self::$shortLinkObj = new ShortLink($username, $password, $dbname, $charset);
        return self::$shortLinkObj;
    }
    // токен для session
    public function getToken()
    {
        return hash('ripemd160', 'mytime'.time() );
    }
    // получаем сокращённую сылку
    public function getShortLink($longLink)
    {
        $scheme = parse_url($longLink , PHP_URL_SCHEME);
        if (empty($scheme))
            $longLink = 'http://'.$longLink;

        $query = 'insert into links values (:hash,:link)';
        $stmt = self::$pdo->prepare($query);

        // если hash уже есть в базе то пробуем 5 раз сформировать другой и записать его.
        for ($i = 0; $i < 5; $i++)
        {
            try{
                //берём первые 5 символов в hash
                $hash = substr(md5('time'.time()), 0, 6 );
                $sqlAnswer = $stmt->execute([
                    'hash' => $hash,
                    'link' => $longLink
                ]);
                //если запрос выполнился с ошибкой
                if ($sqlAnswer == false)
                    //если ошибка уникальности 'Duplicate entry'
                    if ($stmt->errorInfo()[1] == 1062)
                        throw new Exception($stmt->errorInfo()[2]);
            }
            catch(Exception $e) {
                continue;
            }
            break;
        }
        if ($i == 5){
            echo "Не получается создать ссылку, попробуйте позже<br>\n";
            return null;
        }
        return 'http://'.$_SERVER['HTTP_HOST'].'/'.$hash;
    }
    // перходим по ссылке если есть соответствие в базе
    public function goToLink($uri)
    {
        $uriArr = explode('/', $uri);

        if (empty($uriArr[1]))
            return 'no link';

        $query = 'select hash, link from links where hash = :hash';
        $stmt = self::$pdo->prepare($query);
        $sqlAnswer = $stmt->execute([
            'hash' => $uriArr[1]
        ]);

        if (!$sqlAnswer)
            return null;
        $link = $stmt->fetch()['link'];
        header('Location: '. $link);

    }
}