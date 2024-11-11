<?php
$host = 'localhost'; // или ваш хост
$db = 'datebas'; // имя базы данных
$user = 'root'; // имя пользователя
$pass = ''; // пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Ошибка подключения: ' . $e->getMessage();
}
?>
