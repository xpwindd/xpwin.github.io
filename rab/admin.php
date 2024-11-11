<?php
session_start();
require 'db.php';

// Проверка авторизации
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Если не авторизован, проверяем логин и пароль
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Проверяем логин и пароль
        if ($username === 'help' && $password === 'helpme') {
            $_SESSION['loggedin'] = true;
        } else {
            echo "Неверный логин или пароль.";
        }
    }
}

// Если пользователь не авторизован, показываем форму логина
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход в панель администратора</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h1>Вход в панель администратора</h1>
            <form action="" method="POST">
                <label for="username">Логин:</label>
                <input type="text" name="username" required>
                <label for="password">Пароль:</label>
                <input type="password" name="password" required>
                <button type="submit">Войти</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Обработка изменения статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id']) && isset($_POST['status'])) {
    $ticket_id = $_POST['ticket_id'];
    $status = $_POST['status'];

    // Проверка на допустимые значения статуса
    $allowed_statuses = ['новое', 'в процессе', 'выполнено', 'отменено'];
    if (in_array($status, $allowed_statuses)) {
        try {
            // Обновление статуса в базе данных
            $stmt = $pdo->prepare("UPDATE tickets SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $status, 'id' => $ticket_id]);
        } catch (PDOException $e) {
            echo "Ошибка при обновлении статуса: " . htmlspecialchars($e->getMessage());
            exit(); // Завершаем выполнение скрипта при ошибке
        }
    } else {
        echo "Недопустимый статус.";
        exit();
    }
}

try {
    $stmt = $pdo->query("SELECT t.*, u.full_name, u.department FROM tickets t JOIN users u ON t.user_id = u.id");
    $tickets = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Ошибка при получении данных: " . htmlspecialchars($e->getMessage());
    exit(); // Завершаем выполнение скрипта при ошибке
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Все заявки</h1>
        <table>
            <tr>
                <th>ФИО пользователя</th>
                <th>Отдел</th>
                <th>Категория</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Изменить статус</th>
            </tr>
            <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="6">Нет заявок для отображения.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['department']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['category']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['description']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                        <td>
                            <form action="admin.php" method="POST">
                                <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket['id']); ?>">
                                <select name="status" required>
                                    <option value="новое" <?php if ($ticket['status'] == 'новое') echo 'selected'; ?>>Новое</option>
                                    <option value="в процессе" <?php if ($ticket['status'] == 'в процессе') echo 'selected'; ?>>В процессе</option>
                                    <option value="выполнено" <?php if ($ticket['status'] == 'выполнено') echo 'selected'; ?>>Выполнено</option>
                                    <option value="отменено" <?php if ($ticket['status'] == 'отменено') echo 'selected'; ?>>Отменено</option>
                                </select>
                                <button type="submit">Обновить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <form action="logout.php" method="POST">
            <button type="submit">Выйти</button>
        </form>
    </div>
</body>
</html>

