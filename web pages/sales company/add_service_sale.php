<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $employee_id = 1; 
        
        $client_id = $_POST['client_id'] ?? null; 
        $service_ids = $_POST['service_ids'] ?? [];
        $hours = $_POST['hours'] ?? [];
        $discount = isset($_POST['discount']) ? $_POST['discount'] / 100 : 0;
        $payment_method = $_POST['payment_method'] ?? 'Cash';
        
        if (!$client_id || empty($service_ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing data']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            $subtotal = 0;
            $saleItems = [];

            foreach ($service_ids as $index => $s_id) {
                $hours_worked = floatval($hours[$index] ?? 0);

                if ($hours_worked <= 0) continue;

                $stmtService = $pdo->prepare("SELECT service_name, base_price FROM services WHERE id = ? AND company_id = ?");
                $stmtService->execute([$s_id, $company_id]);
                $service = $stmtService->fetch(PDO::FETCH_ASSOC);

                if (!$service) continue;

                $unit_price = floatval($service['base_price']);
                $service_name = $service['service_name'];

                $total_price = $unit_price * $hours_worked;
                $subtotal += $total_price;

                $saleItems[] = [
                    'se_id' => $s_id,
                    'service_name' => $service_name,
                    'hours' => $hours_worked,
                    'unit_price' => $unit_price,
                    'total_price' => $total_price
                ];
            }

            if ($subtotal <= 0) {
                throw new Exception("Invalid sale data.");
            }

            $discountValue = $subtotal * $discount;
            $tax = ($subtotal - $discountValue) * 0.02;
            $total_amount = $subtotal - $discountValue + $tax;
            $transaction_id = 'TX-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $date = date('Y-m-d');

            $stmt = $pdo->prepare("
                INSERT INTO sales 
                (transaction_id, company_id, employee_id, client_id, sale_date, subtotal, discount, tax, total_amount, payment_method, payment_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $transaction_id, 
                $company_id, 
                $employee_id, 
                $client_id, 
                $date, 
                $subtotal, 
                $discountValue, 
                $tax, 
                $total_amount, 
                $payment_method, 
                'Pending'
            ]);
            
            $sale_id = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("
                INSERT INTO service_sale_items 
                (sale_id, service_id, service_name, quantity_hours, unit_price, total_price) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            foreach ($saleItems as $item) {
                $stmtItem->execute([
                    $sale_id,
                    $item['se_id'],
                    $item['service_name'],
                    $item['hours'],
                    $item['unit_price'],
                    $item['total_price']
                ]);
            }

            $inv_num = 'INV-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $stmtInv = $pdo->prepare("
                INSERT INTO invoices (invoice_number, sale_id, issue_date) 
                VALUES (?, ?, ?)
            ");
            $stmtInv->execute([$inv_num, $sale_id, $date]);

            $stmtClient = $pdo->prepare("UPDATE clients SET last_purchase_date = ?, total_spent = total_spent + ? WHERE id = ?");
            $stmtClient->execute([$date, $total_amount, $client_id]);
            
            $pdo->commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Sale recorded successfully!'
            ]);

        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();

            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }