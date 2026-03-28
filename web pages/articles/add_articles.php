<?php
include ('../../config/database.php');

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $author_name = $_POST['name']; 
        $title  =$_POST['title'];   
        $company_id  = 1;              
        $category    = $_POST['category'];
        $ar_description  = $_POST['description'];
        $ar_date   = $_POST['date'];;
        $link= $_POST['link'];
        $ar_image= $_POST['image'];

        $pdo = getDB();

        $sql = "INSERT INTO articles ( company_id, author_name, title, category , ar_date,ar_description,link,ar_image)
                VALUES (:company_id, :author_name, :title, :category , :ar_date, :ar_description, :link, :ar_image)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':company_id' => $company_id,
            ':author_name' => $author_name,
            ':title'  => $title,
            ':category'   => $category,
            ':ar_date' => $ar_date,
            ':ar_description'  => $ar_description,
            ':link' => $link,
            ':ar_image'=> $ar_image
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'article added successfully'
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
?>