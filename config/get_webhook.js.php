<?php
header("Content-Type: application/javascript");

$envPath = __DIR__ . '/../d41d8cd9.env'; 
$webhook = "";

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        
        if (strpos($line, 'ZAPIER_URL=') === 0) {
            $webhook = trim(substr($line, strpos($line, '=') + 1), " \"'");
            break;
        }
    }
} else {
    echo 'console.error("Erreur PHP : Le fichier .env est introuvable au chemin : ' . addslashes($envPath) . '");' . "\n";
}
?>
const ZAPIER_WEBHOOK = "<?= $webhook ?>";