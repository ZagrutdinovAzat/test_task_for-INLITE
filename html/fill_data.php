<?php

require 'db_config.php';
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}

function insertPosts($conn, $posts) {
    $stmt = $conn->prepare("INSERT INTO posts (userId, title, body) VALUES (?, ?, ?)");
    foreach ($posts as $post) {
        $userId = $post['userId'];
        $title = $post['title'];
        $body = $post['body'];
        $stmt->bind_param("iss", $userId, $title, $body);
        $stmt->execute();
    }
    $stmt->close();
}

function insertComments($conn, $comments) {
    $stmt = $conn->prepare("INSERT INTO comments (postId, name, email, body) VALUES (?, ?, ?, ?)");
    foreach ($comments as $comment) {
        $postId = $comment['postId'];
        $name = $comment['name'];
        $email = $comment['email'];
        $body = $comment['body'];
        $stmt->bind_param("isss", $postId, $name, $email, $body);
        $stmt->execute();
    }
    $stmt->close();
}

$conn = getConnection();

$posts = fetchData('https://jsonplaceholder.typicode.com/posts');
$comments = fetchData('https://jsonplaceholder.typicode.com/comments');

insertPosts($conn, $posts);
insertComments($conn, $comments);

echo "Загружено " . count($posts) . " записей и " . count($comments) . " комментариев\n";

$conn->close();
