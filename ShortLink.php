<?php

class ShortLink
{
    private static $shortLinkObj = null;
    private static $pdo = null;

    private function __construct($username, $password, $dbname, $charset)
    {
        self::$pdo = new PDO('mysql:host=localhost;dbname=' . $dbname . ';charset=' . $charset, $username, $password);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

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
        return hash('ripemd160', 'mytime' . time());
    }

    // получаем сокращённую сылку
    public function getShortLink($longLink, $hash = '')
    {
        $scheme = parse_url($longLink, PHP_URL_SCHEME);
        if (empty($scheme))
            $longLink = 'http://' . $longLink;

        $query = 'insert into links values (:hash,:link)';
        $stmt = self::$pdo->prepare($query);

        do {
            if ($hash == '')
                $hash = substr(md5('time' . time()), 0, 6);
            else {
                if ($this->isHashInDB($hash))
                    return 'duplicate';
            }
            if ($this->isHashInDB($hash))
                continue;
            $stmt->execute([
                'hash' => $hash,
                'link' => $longLink
            ]);
            break;
        } while (true);

        return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $hash;
    }

    // перходим по ссылке если есть соответствие в базе
    public function goToLink($uri)
    {
        $uri = urldecode($uri);
        $uriArr = explode('/', $uri);

        if (empty($uriArr[1]))
            return 'no link';

        $query = 'select link from links where hash = :hash';
        $stmt = self::$pdo->prepare($query);
        $sqlAnswer = $stmt->execute([
            'hash' => $uriArr[1]
        ]);

        if (!$sqlAnswer)
            return null;
        $link = $stmt->fetch()['link'];
        header('Location: ' . $link);

    }

    private function isHashInDB($hash)
    {
        $query = 'select hash from links where hash = :hash';
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['hash' => $hash]);
        $result = $stmt->fetch();
        if (!empty($result))
            return true;
        return false;
    }

    public function Ajax($shortLink, $token)
    {
        echo json_encode(['shortLink' => $shortLink, 'token' => $token]);
    }
}