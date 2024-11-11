<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO tickets (user_id, category, description, status) VALUES (?, ?, ?, 'новое')");
    $stmt->execute([$user_id, $category, $description]);
    header("Location: tickets.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создание заявки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Создание заявки</h1>
        <form action="create_ticket.php" method="POST">
            <select name="category" required>
                <option value="">Выберите категорию</option>
                <option value="Техническая проблема">Техническая проблема</option>
                <option value="Запрос информации">Запрос информации</option>
                <option value="Другие">Другие</option>
            </select>
            <textarea name="description" placeholder="Описание проблемы" required></textarea>
            <button type="submit">Создать заявку</button>
        </form>
        <a href="tickets.php">Назад к заявкам</a>
    </div>
</body>
</html>