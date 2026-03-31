<?php
require_once '../../config/database.php';
require_once '../../config/session.php';

header('Content-Type: application/json');

$db=getDB();
$stmt = $db->query("SELECT id,client_name , email FROM clients ORDER BY client_name ASC");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($clients);