<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Поиск записей</title>
</head>
<body>
    <form method="GET" action="index.php">
        <input type="text" name="query" placeholder="Введите текст для поиска" required>
        <button type="submit">Найти</button>
    </form>

    <?php

    require 'db_config.php';

    if (isset($_GET['query']) && strlen($_GET['query']) >= 3) {
        $query = $_GET['query'];

        $conn = getConnection();

        $stmt = $conn->prepare("
            SELECT p.title, c.body
            FROM posts p
            JOIN comments c ON p.id = c.postId
            WHERE c.body LIKE ?
        ");
        $searchQuery = "%$query%";
        $stmt->bind_param("s", $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<h2>{$row['title']}</h2>";
                echo "<p>{$row['body']}</p>";
            }
        } else {
            echo "<p>Ничего не найдено</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
