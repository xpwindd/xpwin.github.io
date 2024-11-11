<?php
session_start();
require 'db.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $department = $_POST['department'];

    // Добавление пользователя в базу данных
    $stmt = $pdo->prepare("INSERT INTO users (full_name, phone, email, password, department) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $phone, $email, $password, $department]);
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <form action="register.php" method="POST">
            <input type="text" name="full_name" placeholder="ФИО" required>
            <input type="text" name="phone" placeholder="Телефон" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <select name="department" required>
                <option value="">Выберите отдел</option>
                <option value="IT">IT</option>
                <option value="HR">HR</option>
                <option value="Sales">Sales</option>
                <!-- Добавьте другие отделы по необходимости -->
            </select>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p>Уже зарегистрированы? <a href="login.php">Войти</a></p>
    </div>
</body>
</html>