<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ?");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заявки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Мои заявки</h1>
        <table>
            <tr>
                <th>Категория</th>
                <th>Описание</th>
                <th>Статус</th>
            </tr>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ticket['category']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['description']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="create_ticket.php">Создать новую заявку</a>
    </div>
</body>
</html>