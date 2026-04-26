<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { echo json_encode(['success' => false, 'error' => 'Company not found']); exit; }
    $company_id = $row['id'];

    $action = $_GET['action'] ?? '';

    try {
        switch ($action) {

            case 'list':
                $q = $_GET['search'] ?? '';
                $sql = "SELECT * FROM clients WHERE company_id = ?";
                $params = [$company_id];
                if ($q) {
                    $sql .= " AND (client_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
                    $params[] = "%$q%"; $params[] = "%$q%"; $params[] = "%$q%";
                }
                $sql .= " ORDER BY client_name ASC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
                break;

            case 'create':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    echo json_encode(['success' => false, 'error' => 'Method not allowed']); exit;
                }
                $data = json_decode(file_get_contents('php://input'), true);
                if (empty($data['client_name'])) {
                    echo json_encode(['success' => false, 'error' => 'Client name is required']); exit;
                }
                $stmt = $pdo->prepare("
                    INSERT INTO clients (company_id, client_name, email, phone, address, client_type, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $company_id,
                    $data['client_name'],
                    $data['email'] ?? null,
                    $data['phone'] ?? null,
                    $data['address'] ?? null,
                    $data['client_type'] ?? 'B2C',
                    $data['status'] ?? 'Active'
                ]);
                echo json_encode(['success' => true, 'client_id' => $pdo->lastInsertId()]);
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    echo json_encode(['success' => false, 'error' => 'Method not allowed']); exit;
                }
                $clientId = $_GET['id'] ?? 0;
                $data = json_decode(file_get_contents('php://input'), true);
                $check = $pdo->prepare("SELECT id FROM clients WHERE id = ? AND company_id = ?");
                $check->execute([$clientId, $company_id]);
                if (!$check->fetch()) {
                    echo json_encode(['success' => false, 'error' => 'Client not found']); exit;
                }
                $stmt = $pdo->prepare("
                    UPDATE clients SET client_name=?, email=?, phone=?, address=?, client_type=?, status=?
                    WHERE id=?
                ");
                $stmt->execute([
                    $data['client_name'],
                    $data['email'] ?? null,
                    $data['phone'] ?? null,
                    $data['address'] ?? null,
                    $data['client_type'] ?? 'B2C',
                    $data['status'] ?? 'Active',
                    $clientId
                ]);
                echo json_encode(['success' => true]);
                break;

            case 'delete':
                $clientId = $_GET['id'] ?? 0;
                $check = $pdo->prepare("SELECT id FROM clients WHERE id = ? AND company_id = ?");
                $check->execute([$clientId, $company_id]);
                if (!$check->fetch()) {
                    echo json_encode(['success' => false, 'error' => 'Client not found']); exit;
                }
                $salesCheck = $pdo->prepare("SELECT COUNT(*) as cnt FROM sales WHERE client_id = ?");
                $salesCheck->execute([$clientId]);
                if ($salesCheck->fetch(PDO::FETCH_ASSOC)['cnt'] > 0) {
                    echo json_encode(['success' => false, 'error' => 'Cannot delete client with existing sales']); exit;
                }
                $pdo->prepare("DELETE FROM clients WHERE id = ?")->execute([$clientId]);
                echo json_encode(['success' => true]);
                break;

            case 'churn':
                $clientId = $_GET['id'] ?? null;
                if (!$clientId) { echo json_encode(['error' => 'No client ID']); break; }

                $stmt = $pdo->prepare("
                    SELECT 
                        COALESCE(c.total_spent, 0) as total_spent,
                        COALESCE(DATEDIFF(CURDATE(), c.last_purchase_date), 365) as days_since_last_purchase,
                        COUNT(s.id) as purchase_count,
                        COALESCE(AVG(s.total_amount), 0) as avg_order_value
                    FROM clients c
                    LEFT JOIN sales s ON c.id = s.client_id
                    WHERE c.id = ? AND c.company_id = ?
                    GROUP BY c.id
                ");
                $stmt->execute([$clientId, $company_id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$data) { echo json_encode(['error' => 'Client not found']); break; }

                $data = array_map('floatval', $data);

                $ch = curl_init("https://churn-prediction-ydmo.onrender.com/predict");
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS     => json_encode($data),
                    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                    CURLOPT_TIMEOUT        => 10,
                ]);
                $response = curl_exec($ch);
                echo curl_errno($ch)
                    ? json_encode(['error' => 'AI Service Offline', 'details' => curl_error($ch)])
                    : $response;
                curl_close($ch);
                break;

            case 'bulk_churn':
                $ch = curl_init("https://churn-prediction-ydmo.onrender.com/bulk_predict");
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => 15,
                ]);
                $response = curl_exec($ch);
                echo curl_errno($ch)
                    ? json_encode(['success' => false, 'error' => curl_error($ch)])
                    : $response;
                curl_close($ch);
                break;
                
            case 'stats':
                $s = $pdo->prepare("
                    SELECT COUNT(*) as total_clients,
                        COALESCE(AVG(total_amount), 0) as avg_revenue
                    FROM sales 
                    WHERE company_id = ? AND payment_status = 'Paid'
                ");
                $s->execute([$company_id]);
                $stats = $s->fetch(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $stats]);
                break;

            default:
                echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }