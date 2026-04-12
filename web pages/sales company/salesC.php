<?php 
    $pageTitle = "Sales & Services"; 
    include('../squelettes entreprise/header.php'); 

?>
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        <section id="sales-kpis" class="mb-5">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Sales Performance</h2>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card p-4 text-center stats-card">
                        <h6 class="text-secondary text-uppercase small">Monthly Target</h6>
                        <h3 class="fw-bold" id="kpi-revenue">0 Dt</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 text-center stats-card" style="border-left-color: #102E4A;">
                        <h6 class="text-secondary text-uppercase small">Active Leads</h6>
                        <h3 class="fw-bold" id="kpi-clients">0</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 text-center stats-card" style="border-left-color: #8D9B6A;">
                        <h6 class="text-secondary text-uppercase small">Conversion Rate</h6>
                        <h3 class="fw-bold" id="kpi-conversion">0%</h3>
                    </div>
                </div>
            </div>
        </section>

        <section id="sales-analytics" class="mb-5">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 fw-bold" style="color: #102E4A;">Revenue Overview</h2>
                    <select id="yearFilter" class="form-select w-auto">
                        <?php
                        $currentYear = date("Y");
                        for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                            echo "<option value='$y'>$y</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </section>

        <section id="client-explorer" class="mb-5">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Client Information Explorer</h2>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">Search Client Name</label>
                            <div class="position-relative">
                                <input type="text" id="explorer-client-name" class="form-control explorer-search" placeholder="Search client...">
                                <input type="hidden" id="explorer-client-id">
                                <div class="explorer-results"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Email Address</label>
                            <input type="text" id="explorer-email" class="form-control bg-light" readonly placeholder="-">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Account Type</label>
                            <input type="text" id="explorer-type" class="form-control bg-light" readonly placeholder="-">
                        </div>
                    </div>

                    <div id="explorer-stats" class="rounded-3 p-4" style="background-color: #f0f4f8; border-left: 5px solid #102E4A;">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <i class="bi bi-clock-history fs-2 text-primary mb-2"></i>
                                <p class="text-secondary small text-uppercase mb-1">Last Purchase</p>
                                <h5 class="fw-bold mb-0" id="explorer-last-date">-- / -- / --</h5>
                            </div>
                            <div class="col-6">
                                <i class="bi bi-wallet2 fs-2 text-success mb-2"></i>
                                <p class="text-secondary small text-uppercase mb-1">Lifetime Value</p>
                                <h5 class="fw-bold mb-0"><span id="explorer-total-spent">0.00</span> Dt</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="add-sale" class="mb-5">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 fw-bold mb-0" style="color: #102E4A;" id="form-title">Record New Sale</h2>
                    <div class="d-flex align-items-center gap-2">
                        <div class="switcher-cards">
                            <div class="card-btn sales active">
                                <h4>Sales</h4>
                            </div>
                            <div class="card-btn services">
                                <h4>Services</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="service-form" class="d-none">
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Select Client</label>
                            <div class="position-relative">
                                <input type="text" class="form-control client-search" placeholder="Search client..." required>
                                <input type="hidden" name="client_id" class="client-id">
                                <div class="client-results"></div>
                            </div>
                        </div>
                    </div>

                    <div id="service-lines">
                        <div class="row g-2 mb-3 service-row align-items-end">

                            <div class="col-12 col-md-5 position-relative">
                                <label class="form-label small fw-bold">Service</label>
                                <input type="text" class="form-control service-search" placeholder="Search service...">
                                <input type="hidden" name="service_ids[]" class="service-id">
                                <div class="service-results"></div>
                            </div>

                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold">Price (DT)</label>
                                <input type="number" class="form-control price-input" readonly>
                            </div>

                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold">Hours</label>
                                <input type="number" name=hours[] class="form-control hour-input" placeholder="0">
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="button" class="btn btn-outline-primary w-100 add-line">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label small fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="row mt-4 align-items-center">
                        <div class="col-md-6">
                            <h4 class="fw-bold mb-0" style="color: #102E4A;">Total: <span id="form-total-service">0.00</span> Dt</h4>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Discount: 
                                <span id="discountValueService">0%</span>
                            </label>

                            <input type="range" name="discount" id="discountRangeService" min="0" max="75" step="5" value="0" class="form-range">
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-finance px-5 py-2 rounded-pill">
                                <i class="bi bi-cart-check me-2"></i>Finalize Sale Service
                            </button>
                        </div>
                    </div>
                </form>

                <form  id="sales-form">
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Select Client</label>
                            <div class="position-relative">
                                <input type="text" class="form-control client-search" placeholder="Search client..." required>
                                <input type="hidden" name="client_id" class="client-id">
                                <div class="client-results"></div>
                            </div>
                        </div>
                    </div>

                    <div id="product-lines">
                        <div class="row g-2 mb-3 product-row align-items-end">

                            <div class="col-12 col-md-5 position-relative">
                                <label class="form-label small fw-bold">Product</label>
                                <input type="text" class="form-control product-search" placeholder="Search product...">
                                <input type="hidden" name="product_ids[]" class="product-id">
                                <div class="product-results"></div>
                            </div>

                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold">Price (Dt)</label>
                                <input type="number" class="form-control price-input" readonly>
                            </div>

                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold">Quantity</label>
                                <input type="number" name="quantities[]" class="form-control quantity-input" placeholder="0">
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="button" class="btn btn-outline-primary w-100 add-line">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label small fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="row mt-4 align-items-center">
                        <div class="col-md-6">
                            <h4 class="fw-bold mb-0" style="color: #102E4A;">Total: <span id="form-total">0.00</span> Dt</h4>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Discount: 
                                <span id="discountValue">0%</span>
                            </label>

                            <input type="range" name="discount" id="discountRange" min="0" max="75" step="5" value="0" class="form-range">
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-finance px-5 py-2 rounded-pill">
                                <i class="bi bi-cart-check me-2"></i>Finalize Sale
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section id="sales-history" class="mb-5">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Recent Orders</h2>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text border-0 bg-white px-3">
                            <i class="bi bi-search text-secondary"></i>
                        </span>
                        <input type="text" id="sales-search" class="form-control border-0 py-2" placeholder="Search by Client name or Order ID...">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh List
                    </button>
                </div>
            </div>
            
            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">Order ID</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-center">Invoice</th>
                        </tr>
                    </thead>
                    <tbody id="sales-list">
                        <?php
                            $query = "SELECT s.*, c.client_name 
                                    FROM sales s 
                                    JOIN clients c ON s.client_id = c.id 
                                    WHERE s.company_id = $company_id
                                    ORDER BY s.id DESC";
                            
                            $stmt = $pdo->query($query);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $isPending = ($row['payment_status'] == 'Pending');
                                $badgeClass = $isPending ? 'bg-warning-subtle text-warning status-clickable' : 'bg-success-subtle text-success';
                                $cursor = $isPending ? 'style="cursor:pointer"' : 'style="cursor:default"';
                                $queryType = "SELECT 1
                                            FROM sale_items
                                            WHERE sale_id = {$row['id']} 
                                            LIMIT 1";
                                $stmtType = $pdo->query($queryType); 
                                $sale_type = $stmtType->fetch() ? 'Product Sale' : 'Service';
                                $typeClass = $sale_type == 'Service' ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary';

                                echo "<tr>
                                        <td class='px-4 fw-bold text-muted'>#{$row['transaction_id']}</td>
                                        <td>
                                            <span class='badge {$typeClass}'>
                                                {$sale_type}
                                            </span>
                                        </td>
                                        <td>{$row['sale_date']}</td>
                                        <td class='fw-bold'>{$row['client_name']}</td>
                                        <td class='fw-bold'>{$row['total_amount']} Dt</td>
                                        <td>
                                            <span class='badge {$badgeClass} px-3 status-badge' 
                                                data-id='{$row['id']}' 
                                                data-status='{$row['payment_status']}' 
                                                {$cursor}>
                                                {$row['payment_status']}
                                            </span>
                                        </td>
                                        <td class='text-center'>
                                            <a href='generate_invoice.php?sale_id={$row['id']}' target='_blank' class='btn btn-sm btn-outline-primary rounded-pill'>
                                                <i class='bi bi-file-earmark-pdf'></i> View
                                            </a>
                                        </td>
                                    </tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <div class="text-center my-4">
                    <button id="show-more-sales" class="btn btn-outline-primary rounded-pill px-5">
                        Show More
                    </button>
                </div>
            </div>
        </section>
    </div>
<?php
    $pagePath = '"../sales company/salesC.js"';
    include('../squelettes entreprise/footer.php'); 
?>