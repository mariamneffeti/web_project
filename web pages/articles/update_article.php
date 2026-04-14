<?php
require '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $pdo = getDB();

        $id         = $_POST['id'];
        $title       = $_POST['title'];
        $category      = $_POST['category'];
        $author = $_POST['author'];
        $description   = $_POST['description'];
        $date = $_POST['date'];
        $link = $_POST['link'];
        $image= $_POST['image'];


        $stmt = $pdo->prepare("UPDATE articles 
            SET title = ?, category = ?, author_name = ?, ar_description = ? , ar_date = ? , link = ? , ar_image = ?
            WHERE id = ?");

        $stmt->execute([$title , $category , $author , $description , $date , $link , $image , $id ]);

        echo json_encode([
            "status" => "success",
            "message" => "Article updated successfully"
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}