<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

    $employee_id    = null;
    $client_id      = $_POST['client_id'] ?? null;
    $product_ids    = $_POST['product_ids'] ?? [];
    $quantities     = $_POST['quantities'] ?? [];
    $discount       = isset($_POST['discount']) ? floatval($_POST['discount']) / 100 : 0;
    $payment_method = $_POST['payment_method'] ?? 'Cash';

    if (!$client_id || empty($product_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing data']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $subtotal  = 0;
        $saleItems = [];

        foreach ($product_ids as $index => $p_id) {
            $quantity = intval($quantities[$index] ?? 0);
            if ($quantity <= 0) continue;

            $stmtProd = $pdo->prepare(
                "SELECT product_name, price, stock_quantity FROM products WHERE id = ? AND company_id = ?"
            );
            $stmtProd->execute([$p_id, $company_id]);
            $product = $stmtProd->fetch(PDO::FETCH_ASSOC);

            if (!$product) continue;

            // Stock check
            if ($product['stock_quantity'] < $quantity) {
                $pdo->rollBack();
                echo json_encode([
                    'status'  => 'error',
                    'message' => "Insufficient stock for \"{$product['product_name']}\". 
                                Available: {$product['stock_quantity']}, Requested: {$quantity}."
                ]);
                exit;
            }

            $unit_price  = floatval($product['price']);
            $total_price = $unit_price * $quantity;
            $subtotal   += $total_price;

            $saleItems[] = [
                'product_id'   => $p_id,
                'product_name' => $product['product_name'],
                'quantity'     => $quantity,
                'unit_price'   => $unit_price,
                'total_price'  => $total_price
            ];
        }

        if ($subtotal <= 0) throw new Exception("Invalid sale data.");

        $discountValue  = $subtotal * $discount;
        $tax            = ($subtotal - $discountValue) * 0.02;
        $total_amount   = $subtotal - $discountValue + $tax;
        $transaction_id = 'TX-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $date           = date('Y-m-d');

        $stmtSale = $pdo->prepare("
            INSERT INTO sales 
            (transaction_id, company_id, employee_id, client_id, sale_date, subtotal, discount, tax,
            total_amount, payment_method, payment_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtSale->execute([
            $transaction_id, $company_id, $employee_id, $client_id, $date,
            $subtotal, $discountValue, $tax, $total_amount, $payment_method, 'Pending'
        ]);
        $sale_id = $pdo->lastInsertId();

        $stmtItem = $pdo->prepare("
            INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, total_price)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmtStock = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");

        foreach ($saleItems as $item) {
            $stmtItem->execute([
                $sale_id, $item['product_id'], $item['product_name'],
                $item['quantity'], $item['unit_price'], $item['total_price']
            ]);
            $stmtStock->execute([$item['quantity'], $item['product_id']]);
        }

        // Invoice
        $inv_num    = 'INV-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $stmtInv    = $pdo->prepare("INSERT INTO invoices (invoice_number, sale_id, issue_date) VALUES (?, ?, ?)");
        $stmtInv->execute([$inv_num, $sale_id, $date]);

        // Update client
        $stmtClient = $pdo->prepare("UPDATE clients SET last_purchase_date = ?, total_spent = total_spent + ? WHERE id = ?");
        $stmtClient->execute([$date, $total_amount, $client_id]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Sale recorded successfully!']);

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }