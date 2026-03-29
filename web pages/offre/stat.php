<?php
require_once __DIR__ . '/../../config/database.php';
try {
    $db = getDB();

    $stmt = $db->query("SELECT COUNT(*) AS total FROM job_offers");
    $total1 = $stmt->fetch(); // get single row

    $totaloffre=(int)$total1['total'];
    
    $stmt2 = $db->query("SELECT COUNT(*) AS totall FROM companies");
    $total2 = $stmt2->fetch(); // get single row
$totalcompany=(int)$total2['totall'];
   $stmt3 = $db->query("SELECT COUNT(*) AS totall FROM cv_applications");
    $total3 = $stmt3->fetch(); // get single row
$totalapplications=(int)$total3['totall'];
   
} catch (Exception $e) {
    // fallback if something goes wrong
    echo 0;
}