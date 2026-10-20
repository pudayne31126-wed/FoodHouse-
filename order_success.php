<?php
/**
 * TRANG ĐẶT HÀNG THÀNH CÔNG - FOODHOUSE
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$orderCode = clean($_GET['code'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_code = ?");
$stmt->execute([$orderCode]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: ' . url('index.php'));
    exit;
}

// Lấy danh sách món ăn trong đơn
$itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemsStmt->execute([$order['id']]);
$orderItems = $itemsStmt->fetchAll();

$pageTitle = 'Đặt Hàng Thành Công - ' . $order['order_code'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 700px; margin: 40px auto 70px;">
    <div style="background: white; border-radius: var(--radius-md); padding: 35px 30px; border: 1px solid var(--border-color); text-align: center;">
        
        <!-- ICON THÀNH CÔNG -->
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #dcfce7; color: #16a34a; font-size: 2rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
            <i class="fa-solid fa-check"></i>
        </div>

        <h1 style="font-size: 1.8rem; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
            Đặt Hàng Thành Công!
        </h1>
        <p style="font-size: 0.95rem; color: #64748b; margin-bottom: 25px;">
            Cảm ơn bạn đã lựa chọn FoodHouse. Đơn hàng đang được chuẩn bị để giao đến bạn sớm nhất!
        </p>

        <!-- THÔNG TIN ĐƠN HÀNG TỔNG QUAN -->
        <div style="background: #f8fafc; border-radius: var(--radius-sm); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 10px;">
            <div style="text-align: left;">
                <span style="font-size: 0.8rem; color: #64748b; text-transform: uppercase;">Mã Đơn Hàng</span>
                <div style="font-size: 1.2rem; font-weight: 700; color: var(--primary);"><?= htmlspecialchars($order['order_code']) ?></div>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 0.8rem; color: #64748b; text-transform: uppercase;">Trạng Thái</span>
                <div><?= render_status_badge($order['status']) ?></div>
            </div>
        </div>

        <!-- DANH SÁCH MÓN ĐÃ ĐẶT -->
        <div style="text-align: left; margin-bottom: 25px;">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border-color);">
                Món Ăn Đã Đặt
            </h3>
            <?php foreach ($orderItems as $it): ?>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <img src="<?= htmlspecialchars($it['product_image']) ?>" alt="Món" style="width: 44px; height: 44px; border-radius: 4px; object-fit: cover;">
                        <div>
                            <div style="font-weight: 600; font-size: 0.92rem; color: #1e293b;"><?= htmlspecialchars($it['product_name']) ?></div>
                            <div style="font-size: 0.8rem; color: #64748b;"><?= format_price($it['price']) ?> x <?= $it['quantity'] ?></div>
                        </div>
                    </div>
                    <div style="font-weight: 600; color: var(--primary); font-size: 0.95rem;">
                        <?= format_price($it['subtotal']) ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between;">
                <strong>Tổng thanh toán:</strong>
                <strong style="color: var(--primary); font-size: 1.2rem;"><?= format_price($order['total_amount']) ?></strong>
            </div>
        </div>

        <!-- THÔNG TIN NGƯỜI NHẬN -->
        <div style="background: #f8fafc; border-radius: var(--radius-sm); padding: 15px 18px; text-align: left; font-size: 0.9rem; margin-bottom: 25px; line-height: 1.6; border: 1px solid #e2e8f0;">
            <div><strong>Người nhận:</strong> <?= htmlspecialchars($order['customer_name']) ?> - <?= htmlspecialchars($order['customer_phone']) ?></div>
            <div><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['customer_address']) ?></div>
            <div><strong>Hình thức:</strong> <?= ($order['payment_method'] === 'Banking') ? 'Chuyển khoản ngân hàng' : 'Thanh toán tiền mặt (COD)' ?></div>
            <?php if (!empty($order['note'])): ?>
                <div><strong>Ghi chú:</strong> <?= htmlspecialchars($order['note']) ?></div>
            <?php endif; ?>
        </div>

        <!-- NÚT THAO TÁC -->
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= url('products.php') ?>" class="btn btn-secondary">
                <i class="fa-solid fa-utensils me-1"></i> Tiếp Tục Chọn Món
            </a>
            <a href="<?= url('orders.php') ?>" class="btn btn-primary">
                <i class="fa-solid fa-receipt me-1"></i> Xem Đơn Hàng Của Tôi
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
