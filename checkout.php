<?php
/**
 * TRANG THANH TOÁN & ĐẶT MÓN - FOODHOUSE
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$cart = cart_get();
if (empty($cart)) {
    set_flash('warning', 'Giỏ hàng đang trống! Hãy chọn món trước khi đặt hàng.');
    header('Location: ' . url('products.php'));
    exit;
}

$user = get_logged_in_user();
$subtotal = cart_subtotal();
$shipping = DEFAULT_SHIPPING_FEE;
$total = cart_total();

$errors = [];

// Xử lý khi nhấn nút Đặt Hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = clean($_POST['customer_name'] ?? '');
    $phone   = clean($_POST['customer_phone'] ?? '');
    $email   = clean($_POST['customer_email'] ?? '');
    $address = clean($_POST['customer_address'] ?? '');
    $note    = clean($_POST['note'] ?? '');
    $payment = clean($_POST['payment_method'] ?? 'COD');

    if (empty($name)) {
        $errors[] = 'Vui lòng nhập họ và tên người nhận!';
    }
    if (empty($phone)) {
        $errors[] = 'Vui lòng nhập số điện thoại nhận hàng!';
    }
    if (empty($address)) {
        $errors[] = 'Vui lòng nhập địa chỉ giao hàng!';
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            $orderCode = generate_order_code();
            $userId = $user['id'] ?? null;

            // 1. Lưu vào bảng orders
            $stmt = $pdo->prepare("
                INSERT INTO orders (order_code, user_id, customer_name, customer_phone, customer_email, customer_address, note, payment_method, shipping_fee, total_amount, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Chờ xác nhận')
            ");
            $stmt->execute([
                $orderCode, $userId, $name, $phone, $email, $address, $note, $payment, $shipping, $total
            ]);
            $orderId = $pdo->lastInsertId();

            // 2. Lưu từng món vào bảng order_items
            $itemStmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, product_image, price, quantity, subtotal)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($cart as $item) {
                $itemSubtotal = $item['price'] * $item['quantity'];
                $itemStmt->execute([
                    $orderId, $item['id'], $item['name'], $item['image'], $item['price'], $item['quantity'], $itemSubtotal
                ]);
            }

            $pdo->commit();

            // Xóa sạch giỏ hàng
            cart_clear();

            // Chuyển hướng sang trang thành công
            header('Location: ' . url('order_success.php?code=' . urlencode($orderCode)));
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Lỗi trong khi tạo đơn hàng: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Thanh Toán & Đặt Món';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="margin-top: 30px; margin-bottom: 60px;">
    <!-- Breadcrumb -->
    <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 20px;">
        <a href="<?= url('index.php') ?>" style="color: #64748b;">Trang chủ</a> / 
        <a href="<?= url('cart.php') ?>" style="color: #64748b;">Giỏ hàng</a> / 
        <span style="color: var(--primary); font-weight: 600;">Thanh toán</span>
    </div>

    <h1 style="font-size: 1.8rem; font-weight: 700; color: #1e293b; margin-bottom: 25px;">
        <i class="fa-solid fa-credit-card" style="color: var(--primary); margin-right: 8px;"></i>
        Thông Tin Đặt Hàng
    </h1>

    <?php if (!empty($errors)): ?>
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px 18px; border-radius: var(--radius-sm); margin-bottom: 20px; color: #b91c1c;">
            <strong><i class="fa-solid fa-triangle-exclamation me-1"></i> Vui lòng kiểm tra lại:</strong>
            <ul style="margin-top: 5px; padding-left: 20px;">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="checkout-layout">
            <!-- CỘT TRÁI: FORM ĐIỀN THÔNG TIN -->
            <div>
                <div class="checkout-card">
                    <h3 class="checkout-card-title">
                        <i class="fa-solid fa-user me-1" style="color: var(--primary);"></i> 1. Thông Tin Nhận Hàng
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div class="form-group">
                            <label class="form-label">Họ và tên <span class="req">*</span></label>
                            <input type="text" name="customer_name" class="form-control" required 
                                   value="<?= htmlspecialchars($_POST['customer_name'] ?? ($user['fullname'] ?? '')) ?>" 
                                   placeholder="Nguyễn Văn A">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại <span class="req">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" required 
                                   value="<?= htmlspecialchars($_POST['customer_phone'] ?? ($user['phone'] ?? '')) ?>" 
                                   placeholder="0988xxxxxx">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email (tùy chọn)</label>
                        <input type="email" name="customer_email" class="form-control" 
                               value="<?= htmlspecialchars($_POST['customer_email'] ?? ($user['email'] ?? '')) ?>" 
                               placeholder="email@example.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ nhận hàng <span class="req">*</span></label>
                        <input type="text" name="customer_address" class="form-control" required 
                               value="<?= htmlspecialchars($_POST['customer_address'] ?? ($user['address'] ?? '')) ?>" 
                               placeholder="Số nhà, tên đường, phường, quận...">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Ghi chú cho quán</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Đồ ăn làm ít cay, gọi trước khi đến..."><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="checkout-card">
                    <h3 class="checkout-card-title">
                        <i class="fa-solid fa-wallet me-1" style="color: var(--primary);"></i> 2. Hình Thức Thanh Toán
                    </h3>

                    <div class="payment-options-grid">
                        <label class="payment-radio-box active">
                            <input type="radio" name="payment_method" value="COD" checked>
                            <div style="font-size: 1.4rem; color: #16a34a;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                            <div>
                                <strong style="display: block;">Thanh toán khi nhận hàng (COD)</strong>
                                <span style="font-size: 0.8rem; color: #64748b;">Trả tiền mặt cho người giao hàng</span>
                            </div>
                        </label>

                        <label class="payment-radio-box">
                            <input type="radio" name="payment_method" value="Banking">
                            <div style="font-size: 1.4rem; color: #0284c7;"><i class="fa-solid fa-building-columns"></i></div>
                            <div>
                                <strong style="display: block;">Chuyển khoản ngân hàng</strong>
                                <span style="font-size: 0.8rem; color: #64748b;">Chuyển qua số tài khoản ngân hàng</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG -->
            <div>
                <div class="cart-summary-card">
                    <h3 class="summary-title">Món Ăn Đã Chọn (<?= count($cart) ?>)</h3>

                    <div style="max-height: 220px; overflow-y: auto; margin-bottom: 18px; padding-right: 5px;">
                        <?php foreach ($cart as $item): ?>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px dashed #f1f5f9;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="Food" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                    <div>
                                        <div style="font-size: 0.88rem; font-weight: 600; color: #1e293b;"><?= htmlspecialchars($item['name']) ?></div>
                                        <div style="font-size: 0.78rem; color: #64748b;"><?= format_price($item['price']) ?> x <?= $item['quantity'] ?></div>
                                    </div>
                                </div>
                                <div style="font-weight: 600; color: var(--primary); font-size: 0.88rem;">
                                    <?= format_price($item['price'] * $item['quantity']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-row">
                        <span>Tiền món:</span>
                        <strong><?= format_price($subtotal) ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Phí giao hàng:</span>
                        <span><?= format_price($shipping) ?></span>
                    </div>

                    <div class="summary-row total-row">
                        <span>Tổng cộng:</span>
                        <span style="color: var(--primary); font-size: 1.3rem;"><?= format_price($total) ?></span>
                    </div>

                    <div style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            <i class="fa-solid fa-circle-check me-2"></i> Xác Nhận Đặt Hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
