<?php
require_once __DIR__ . '/../../config/session_check.php';
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

$pdo = getDB();
$stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
$stmt->execute([$currentUser['id']]);
$company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {

        case 'get_client':
            $stmt = $pdo->prepare("SELECT email, client_type, last_purchase_date, total_spent 
                                   FROM clients 
                                   WHERE client_name = ? AND company_id = ? LIMIT 1");
            $stmt->execute([$_GET['name'], $company_id]);
            $client = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($client
                ? ['status' => 'success', 'data' => $client]
                : ['status' => 'error', 'message' => 'Client not found']);
            break;

        case 'get_kpis':
            $s1 = $pdo->prepare("SELECT SUM(total_amount) as total FROM sales
                                  WHERE MONTH(sale_date) = MONTH(CURDATE())
                                  AND YEAR(sale_date) = YEAR(CURDATE())
                                  AND payment_status = 'Paid' AND company_id = ?");
            $s1->execute([$company_id]);
            $monthlyRevenue = $s1->fetch()['total'] ?? 0;

            $s2 = $pdo->prepare("SELECT COUNT(DISTINCT client_id) as total FROM sales
                                  WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                  AND payment_status = 'Paid' AND company_id = ?");
            $s2->execute([$company_id]);
            $activeClients = $s2->fetch()['total'] ?? 0;

            $s3 = $pdo->prepare("SELECT SUM(CASE WHEN payment_status = 'Paid' THEN 1 ELSE 0 END) as paid,
                                         COUNT(*) as total
                                  FROM sales WHERE company_id = ?");
            $s3->execute([$company_id]);
            $data = $s3->fetch();
            $conversionRate = $data['total'] > 0
                ? round(($data['paid'] / $data['total']) * 100, 2) : 0;

            echo json_encode(['status' => 'success', 'data' => [
                'monthlyRevenue'  => $monthlyRevenue,
                'activeClients'   => $activeClients,
                'conversionRate'  => $conversionRate
            ]]);
            break;

        case 'get_sales_data':
            $year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
            $stmt = $pdo->prepare("SELECT MONTH(sale_date) AS month, SUM(total_amount) AS total_sales
                                   FROM sales
                                   WHERE YEAR(sale_date) = ? AND payment_status = 'Paid' AND company_id = ?
                                   GROUP BY MONTH(sale_date) ORDER BY MONTH(sale_date)");
            $stmt->execute([$year, $company_id]);
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            break;

        case 'search_clients':
            $q = $_GET['q'] ?? '';
            $stmt = $pdo->prepare("SELECT id, client_name FROM clients
                                   WHERE client_name LIKE ? AND company_id = ?
                                   ORDER BY client_name ASC LIMIT 10");
            $stmt->execute(["%$q%", $company_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'search_products':
            $q = $_GET['q'] ?? '';
            $stmt = $pdo->prepare("SELECT id, product_name, price, stock_quantity FROM products
                                   WHERE product_name LIKE ? AND company_id = ?
                                   ORDER BY product_name ASC LIMIT 10");
            $stmt->execute(["%$q%", $company_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'search_services':
            $q = $_GET['q'] ?? '';
            $stmt = $pdo->prepare("SELECT id, service_name, base_price FROM services
                                   WHERE service_name LIKE ? AND company_id = ?
                                   ORDER BY service_name ASC LIMIT 10");
            $stmt->execute(["%$q%", $company_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'update_status':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
                break;
            }
            $stmt = $pdo->prepare("UPDATE sales SET payment_status = ? WHERE id = ? AND company_id = ?");
            $ok = $stmt->execute([$_POST['status'], $_POST['id'], $company_id]);
            echo json_encode(['status' => $ok ? 'success' : 'error']);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}