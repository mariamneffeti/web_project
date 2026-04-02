<?php
require_once '../../config/database.php';
require_once '../../config/session.php';



$error   = '';
$success = '';

$offre_id   = isset($_GET['offre_id']) ? (int)$_GET['offre_id'] : null;
$company_id = null;
$offre      = null;

$db = getDB();

if ($offre_id) {
    $stmt = $db->prepare("
        SELECT o.*, c.company_name 
        FROM job_offers o 
        JOIN companies c ON o.company_id = c.id 
        WHERE o.id = ? AND o.status = 'active'
    ");
    $stmt->execute([$offre_id]);
    $offre = $stmt->fetch();

    if ($offre) {
        $company_id = $offre['company_id'];
    } else {
        $error = 'This job offer no longer exists or is closed.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {

    if (empty($_FILES['fichier']['name'])) {
        $error = 'Please upload your CV.';
    } else {
        $allowedTypes = ['application/pdf',
                         'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $fileType = mime_content_type($_FILES['fichier']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            $error = 'Only PDF and DOCX files are accepted.';
        } elseif ($_FILES['fichier']['size'] > 5 * 1024 * 1024) {
            $error = 'File must be under 5MB.';
        }
    }

    if (empty($error)) {
      $uploadDir = __DIR__ . '/cvfiles/company_' . $company_id . '/offer_' . $offre_id . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // true = creates parent folders too
}

$filename = uniqid() . '_' . basename($_FILES['fichier']['name']);
$filepath = $uploadDir . $filename;
$db_path  = 'cvfiles/company_' . $company_id . '/offer_' . $offre_id . '/' . $filename;

        if (move_uploaded_file($_FILES['fichier']['tmp_name'], $filepath)) {
            $stmt = $db->prepare("
                INSERT INTO cv_applications 
                (company_id, offre_id, first_name, last_name, email, phone, nationality, address, linkedin, file_path)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $company_id,
                $offre_id,
                $_POST['firstName']   ?? '',
                $_POST['lastName']    ?? '',
                $_POST['email']       ?? '',
                $_POST['phone']       ?? '',
                $_POST['nationality'] ?? '',
                $_POST['address']     ?? '',
                $_POST['linkedin']    ?? '',
                $db_path
            ]);

            $success = 'Your application was submitted successfully!';
        } else {
            $error = 'File upload failed. Please try again.';
        }
    }
}
?>