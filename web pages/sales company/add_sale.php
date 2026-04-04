<?php
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $company_id = 1; 
        $employee_id = 1; 
        
        $client_id = $_POST['client_id'] ?? null; 
        $product_ids = $_POST['product_ids'] ?? [];
        $quantities = $_POST['quantities'] ?? [];
        $discount = $_POST['discount']/100 ?? 0; 
        $payment_method = $_POST['payment_method'] ?? 'Cash';
        
        if (!$client_id || empty($product_ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing data']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            $subtotal = 0;
            $saleItems = [];

            foreach ($product_ids as $index => $p_id) {
                $quantity = intval($quantities[$index] ?? 0);

                if ($quantity <= 0) continue;

                $stmtProd = $pdo->prepare("SELECT product_name, price FROM products WHERE id = ?");
                $stmtProd->execute([$p_id]);
                $product = $stmtProd->fetch(PDO::FETCH_ASSOC);

                if (!$product) continue;

                $unit_price = floatval($product['price']);
                $product_name = $product['product_name'];

                $total_price = $unit_price * $quantity;
                $subtotal += $total_price;

                $saleItems[] = [
                    'product_id' => $p_id,
                    'product_name' => $product_name,
                    'quantity' => $quantity,
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
                INSERT INTO sale_items 
                (sale_id, product_id, product_name, quantity, unit_price, total_price) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            foreach ($saleItems as $item) {
                $stmtItem->execute([
                    $sale_id,
                    $item['product_id'],
                    $item['product_name'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['total_price']
                ]);
            }

            $stmtInv = $pdo->prepare("
                INSERT INTO invoices (sale_id, issue_date) 
                VALUES (?, ?)
            ");
            $stmtInv->execute([$sale_id, $date]);

            $invoice_id = $pdo->lastInsertId();
            $inv_num = 'INV-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $stmt = $pdo->prepare("UPDATE invoices SET invoice_number = ? WHERE id = ?");
            $stmt->execute([$inv_num, $invoice_id]);


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