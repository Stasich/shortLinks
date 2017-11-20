<?php
require_once 'config.php';
require_once 'ShortLink.php';

session_start();
//создём обект для работы с сылками и сразу подключаемся к базе
$objLink = ShortLink::getObjLink($username, $password, $dbname, $charset);
//передаём URI  в объект, если всё ок, то произойдёт переход.
if (!isset($_GET['ajax']))
    $redirect = $objLink->goToLink($_SERVER['REQUEST_URI']);

//токен для защиты от csrf
$token = $objLink->getToken();

//если пришли данные из пост запроса, то сформировать ссылку.
if (isset($_POST['link']) && isset($_POST['token'])) {
    if ($_SESSION['token'] === $_POST['token'])
        if (!empty(trim($_POST['link']))) {
            $longLink = $_POST['link'];
            //$longLink = htmlentities($_POST['link']);
            $shortLink = $objLink->getShortLink($longLink, $_POST['hash']);
        }
}
$_SESSION['token'] = $token;
if (isset($_GET['ajax'])){
    $objLink->Ajax($shortLink, $token);
    die;
}

if ($redirect === null)
    echo "Нет соответсвия с короткой ссылкой. Попробуйт создать ещё раз.<br>\n";
require_once 'view/head.php';
require_once 'view/body.php';
if (isset($shortLink))
    if ($shortLink == 'duplicate')
        echo "Такое имя ссылки уже занято.<br>\n";
    else
        require_once 'view/link.php';
require_once 'view/footer.php';
