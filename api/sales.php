<?php
/**
 * Sales API
 * 
 * Handles all sales-related operations
 */

require_once '../config/database.php';
require_once '../config/session.php';

header('Content-Type: application/json');

// Check if user is authenticated
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$db = getDB();
$user = getCurrentUser();
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        
        // GET ALL SALES
        case 'list':
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
            $offset = ($page - 1) * $perPage;
            
            $search = $_GET['search'] ?? '';
            $startDate = $_GET['start_date'] ?? '';
            $endDate = $_GET['end_date'] ?? '';
            
            // Build query
            $query = "SELECT s.*, c.client_name, e.first_name, e.last_name,
                             CONCAT(e.first_name, ' ', e.last_name) as employee_name
                      FROM sales s
                      LEFT JOIN clients c ON s.client_id = c.id
                      LEFT JOIN employees e ON s.employee_id = e.id
                      WHERE s.company_id = :company_id";
            
            $params = ['company_id' => $user['company_id']];
            
            if ($search) {
                $query .= " AND (s.transaction_id LIKE :search OR c.client_name LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            if ($startDate) {
                $query .= " AND s.sale_date >= :start_date";
                $params['start_date'] = $startDate;
            }
            
            if ($endDate) {
                $query .= " AND s.sale_date <= :end_date";
                $params['end_date'] = $endDate;
            }
            
            $query .= " ORDER BY s.sale_date DESC, s.id DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $sales = $stmt->fetchAll();
            
            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM sales s
                          LEFT JOIN clients c ON s.client_id = c.id
                          WHERE s.company_id = :company_id";
            
            if ($search) {
                $countQuery .= " AND (s.transaction_id LIKE :search OR c.client_name LIKE :search)";
            }
            if ($startDate) {
                $countQuery .= " AND s.sale_date >= :start_date";
            }
            if ($endDate) {
                $countQuery .= " AND s.sale_date <= :end_date";
            }
            
            $countStmt = $db->prepare($countQuery);
            foreach ($params as $key => $value) {
                if ($key !== 'limit' && $key !== 'offset') {
                    $countStmt->bindValue(':' . $key, $value);
                }
            }
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];
            
            echo json_encode([
                'success' => true,
                'data' => $sales,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
            break;
        
        // GET SINGLE SALE
        case 'get':
            $saleId = $_GET['id'] ?? 0;
            
            $stmt = $db->prepare("
                SELECT s.*, c.client_name, c.email as client_email, c.phone as client_phone,
                       CONCAT(e.first_name, ' ', e.last_name) as employee_name
                FROM sales s
                LEFT JOIN clients c ON s.client_id = c.id
                LEFT JOIN employees e ON s.employee_id = e.id
                WHERE s.id = :id AND s.company_id = :company_id
            ");
            $stmt->execute(['id' => $saleId, 'company_id' => $user['company_id']]);
            $sale = $stmt->fetch();
            
            if (!$sale) {
                http_response_code(404);
                echo json_encode(['error' => 'Sale not found']);
                exit();
            }
            
            // Get sale items
            $stmt = $db->prepare("SELECT * FROM sale_items WHERE sale_id = :sale_id");
            $stmt->execute(['sale_id' => $saleId]);
            $sale['items'] = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'data' => $sale]);
            break;
        
        // CREATE SALE
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                exit();
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            $required = ['client_id', 'sale_date', 'items', 'payment_method'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => "Missing required field: $field"]);
                    exit();
                }
            }
            
            $db->beginTransaction();
            
            try {
                // Generate transaction ID
                $transactionId = 'TX-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                
                // Calculate totals
                $subtotal = 0;
                foreach ($data['items'] as $item) {
                    $itemTotal = $item['quantity'] * $item['unit_price'];
                    $discount = $itemTotal * ($item['discount_percent'] / 100);
                    $subtotal += ($itemTotal - $discount);
                }
                
                $discount = $data['discount'] ?? 0;
                $tax = $data['tax'] ?? ($subtotal * 0.1); // Default 10% tax
                $total = $subtotal - $discount + $tax;
                
                // Insert sale
                $stmt = $db->prepare("
                    INSERT INTO sales (transaction_id, company_id, employee_id, client_id, 
                                      sale_date, subtotal, discount, tax, total_amount, 
                                      payment_method, payment_status, notes)
                    VALUES (:transaction_id, :company_id, :employee_id, :client_id,
                           :sale_date, :subtotal, :discount, :tax, :total_amount,
                           :payment_method, :payment_status, :notes)
                ");
                
                $stmt->execute([
                    'transaction_id' => $transactionId,
                    'company_id' => $user['company_id'],
                    'employee_id' => $user['employee_id'],
                    'client_id' => $data['client_id'],
                    'sale_date' => $data['sale_date'],
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total_amount' => $total,
                    'payment_method' => $data['payment_method'],
                    'payment_status' => $data['payment_status'] ?? 'Paid',
                    'notes' => $data['notes'] ?? null
                ]);
                
                $saleId = $db->lastInsertId();
                
                // Insert sale items
                $itemStmt = $db->prepare("
                    INSERT INTO sale_items (sale_id, product_id, product_name, quantity, 
                                           unit_price, discount_percent, total_price)
                    VALUES (:sale_id, :product_id, :product_name, :quantity,
                           :unit_price, :discount_percent, :total_price)
                ");
                
                foreach ($data['items'] as $item) {
                    $itemTotal = $item['quantity'] * $item['unit_price'];
                    $itemDiscount = $itemTotal * (($item['discount_percent'] ?? 0) / 100);
                    $itemFinal = $itemTotal - $itemDiscount;
                    
                    $itemStmt->execute([
                        'sale_id' => $saleId,
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount_percent' => $item['discount_percent'] ?? 0,
                        'total_price' => $itemFinal
                    ]);
                }
                
                // Create invoice
                $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $dueDate = date('Y-m-d', strtotime($data['sale_date'] . ' +30 days'));
                
                $db->prepare("
                    INSERT INTO invoices (invoice_number, sale_id, issue_date, due_date, status)
                    VALUES (:invoice_number, :sale_id, :issue_date, :due_date, 'Sent')
                ")->execute([
                    'invoice_number' => $invoiceNumber,
                    'sale_id' => $saleId,
                    'issue_date' => $data['sale_date'],
                    'due_date' => $dueDate
                ]);
                
                // Update client total spent
                $db->prepare("
                    UPDATE clients 
                    SET total_spent = total_spent + :amount,
                        last_purchase_date = :date
                    WHERE id = :client_id
                ")->execute([
                    'amount' => $total,
                    'date' => $data['sale_date'],
                    'client_id' => $data['client_id']
                ]);
                
                $db->commit();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Sale created successfully',
                    'sale_id' => $saleId,
                    'transaction_id' => $transactionId,
                    'invoice_number' => $invoiceNumber
                ]);
                
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;
        
        // DELETE SALE
        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                exit();
            }
            
            $saleId = $_GET['id'] ?? 0;
            
            // Verify ownership
            $stmt = $db->prepare("SELECT * FROM sales WHERE id = :id AND company_id = :company_id");
            $stmt->execute(['id' => $saleId, 'company_id' => $user['company_id']]);
            $sale = $stmt->fetch();
            
            if (!$sale) {
                http_response_code(404);
                echo json_encode(['error' => 'Sale not found']);
                exit();
            }
            
            $db->beginTransaction();
            
            try {
                // Update client total spent
                $db->prepare("
                    UPDATE clients 
                    SET total_spent = total_spent - :amount
                    WHERE id = :client_id
                ")->execute([
                    'amount' => $sale['total_amount'],
                    'client_id' => $sale['client_id']
                ]);
                
                // Delete sale (cascades to items and invoice)
                $stmt = $db->prepare("DELETE FROM sales WHERE id = :id");
                $stmt->execute(['id' => $saleId]);
                
                $db->commit();
                
                echo json_encode(['success' => true, 'message' => 'Sale deleted successfully']);
                
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;
        
        // GET DASHBOARD STATS
        case 'stats':
            $stats = [];
            
            // Today's sales
            $stmt = $db->prepare("
                SELECT COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total
                FROM sales
                WHERE company_id = :company_id AND DATE(sale_date) = CURDATE()
            ");
            $stmt->execute(['company_id' => $user['company_id']]);
            $today = $stmt->fetch();
            $stats['today'] = $today;
            
            // This month's sales
            $stmt = $db->prepare("
                SELECT COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total
                FROM sales
                WHERE company_id = :company_id 
                AND YEAR(sale_date) = YEAR(CURDATE())
                AND MONTH(sale_date) = MONTH(CURDATE())
            ");
            $stmt->execute(['company_id' => $user['company_id']]);
            $stats['this_month'] = $stmt->fetch();
            
            // Total clients
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM clients WHERE company_id = :company_id");
            $stmt->execute(['company_id' => $user['company_id']]);
            $stats['total_clients'] = $stmt->fetch()['total'];
            
            // Recent sales (last 7 days)
            $stmt = $db->prepare("
                SELECT DATE(sale_date) as date, COUNT(*) as count, SUM(total_amount) as total
                FROM sales
                WHERE company_id = :company_id AND sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(sale_date)
                ORDER BY date DESC
            ");
            $stmt->execute(['company_id' => $user['company_id']]);
            $stats['recent_sales'] = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'data' => $stats]);
            break;
        
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
