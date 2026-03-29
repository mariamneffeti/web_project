<?php
    require __DIR__ . '/../../config/database.php';
    $pdo = Database::getInstance()->getConnection(); 

    $sale_id = $_GET['sale_id'] ?? null;

    if (!$sale_id) die("No Sale ID provided.");

    $stmt = $pdo->prepare("SELECT s.*, c.client_name 
                        FROM sales s 
                        JOIN clients c ON s.client_id = c.id 
                        WHERE s.id = ?");
    $stmt->execute([$sale_id]);
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sale) die("Invoice not found.");

    $stmtItems = $pdo->prepare("SELECT * FROM sale_items WHERE sale_id = ?");
    $stmtItems->execute([$sale_id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    
    $society = "My Company"; 
    $orderId = $sale['transaction_id']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?php echo htmlspecialchars($orderId); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --primary-dark: #102E4A; }
        body { background-color: #f4f7f6; font-family: sans-serif; }
        .invoice-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin: 50px auto; max-width: 850px; overflow: hidden; }
        .invoice-header { background: var(--primary-dark); color: white; padding: 40px; }
        .invoice-body { padding: 50px; }
        .total-section { background: #f8f9fa; border-radius: 15px; padding: 20px; }
        @media print { .no-print { display: none; } .invoice-card { box-shadow: none; margin: 0; } }
    </style>
</head>
<body>

<div class="container">
    <div class="invoice-card">
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <h1 class="fw-bold mb-0"><?php echo $society; ?></h1>
            <div class="text-end">
                <h3 class="mb-0 text-uppercase">Invoice</h3>
                <p class="mb-0">#<?php echo htmlspecialchars($orderId); ?></p>
            </div>
        </div>

        <div class="invoice-body">
            <div class="row mb-5">
                <div class="col-sm-6">
                    <h6 class="text-muted text-uppercase small fw-bold">Billed To:</h6>
                    <h4 class="fw-bold text-dark"><?php echo htmlspecialchars($sale['client_name']); ?></h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <h6 class="text-muted text-uppercase small fw-bold">Date:</h6>
                    <p class="h5"><?php echo date('d M, Y', strtotime($sale['sale_date'])); ?></p>
                </div>
            </div>

            <div class="table-responsive mb-5">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="py-3">
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($item['product_name']); ?></span>
                            </td>
                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                            <td class="text-end"><?php echo number_format($item['unit_price'], 2); ?> Dt</td>
                            <td class="text-end py-3 fw-bold"><?php echo number_format($item['total_price'], 2); ?> Dt</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="total-section text-end">
                        <p class="mb-2 text-muted">Subtotal: <span class="text-dark"><?php echo number_format($sale['subtotal'], 2); ?> Dt</span></p>
                        
                        <?php if ($sale['discount'] > 0): ?>
                        <p class="mb-2 text-danger">Discount: <span class="fw-bold">-<?php echo number_format($sale['discount'], 2); ?> Dt</span></p>
                        <?php endif; ?>

                        <p class="mb-3 text-muted">Tax (2%): <span class="text-dark"><?php echo number_format($sale['tax'], 2); ?> Dt</span></p>
                        
                        <div class="border-top pt-3">
                            <h3 class="fw-bold" style="color: var(--primary-dark);">
                                <?php echo number_format($sale['total_amount'], 2); ?> Dt
                            </h3>
                            <small class="text-muted">Payment Method: <?php echo $sale['payment_method']; ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-5 border-top text-center">
                <div class="no-print">
                    <button onclick="window.print()" class="btn btn-dark px-4 py-2 rounded-pill me-2">
                        <i class="bi bi-printer me-2"></i>Print / Save PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>