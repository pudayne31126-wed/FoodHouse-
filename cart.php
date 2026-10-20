<?php
/**
 * TRANG GIỎ HÀNG - FOODHOUSE
 */
$pageTitle = 'Giỏ Hàng Của Bạn';
require_once __DIR__ . '/includes/header.php';

$cart = cart_get();
$subtotal = cart_subtotal();
$shipping = ($subtotal > 0) ? DEFAULT_SHIPPING_FEE : 0;
$total = cart_total();
?>

<div class="container" style="margin-top: 30px; margin-bottom: 60px;">
    <!-- Đường dẫn chuyển hướng (Breadcrumb) -->
    <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 20px;">
        <a href="<?= url('index.php') ?>" style="color: #64748b;">Trang chủ</a> / 
        <span style="color: var(--primary); font-weight: 600;">Giỏ hàng</span>
    </div>

    <h1 style="font-size: 1.8rem; font-weight: 700; color: #1e293b; margin-bottom: 25px;">
        <i class="fa-solid fa-bag-shopping" style="color: var(--primary); margin-right: 8px;"></i>
        Giỏ Hàng Của Bạn (<?= cart_count() ?> món)
    </h1>

    <?php if (!empty($cart)): ?>
        <div class="cart-layout">
            <!-- BẢNG DANH SÁCH MÓN ĂN -->
            <div>
                <div class="cart-table-card">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Món Ăn</th>
                                <th>Đơn Giá</th>
                                <th style="text-align: center;">Số Lượng</th>
                                <th>Thành Tiền</th>
                                <th style="text-align: center;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                                <?php $itemSubtotal = $item['price'] * $item['quantity']; ?>
                                <tr>
                                    <td>
                                        <div class="cart-item-info">
                                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img">
                                            <div>
                                                <a href="<?= url('product_detail.php?id=' . $item['id']) ?>" style="font-weight: 600; color: #1e293b;">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-weight: 500;">
                                        <?= format_price($item['price']) ?>
                                    </td>
                                    <td>
                                        <div class="quantity-picker" style="margin: 0 auto; width: 100px; height: 36px;">
                                            <button type="button" class="qty-btn" data-action="minus" data-id="<?= $item['id'] ?>">-</button>
                                            <input type="number" class="qty-input" value="<?= $item['quantity'] ?>" min="1" max="99" readonly>
                                            <button type="button" class="qty-btn" data-action="plus" data-id="<?= $item['id'] ?>">+</button>
                                        </div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--primary);">
                                        <?= format_price($itemSubtotal) ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-remove-cart" data-id="<?= $item['id'] ?>" style="background: transparent; border: none; color: #ef4444; font-size: 1.1rem; cursor: pointer;" title="Xóa món này">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <a href="<?= url('products.php') ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục chọn món
                    </a>
                </div>
            </div>

            <!-- TÓM TẮT ĐƠN HÀNG -->
            <div>
                <div class="cart-summary-card">
                    <h3 class="summary-title">Tóm Tắt Đơn Hàng</h3>

                    <div class="summary-row">
                        <span>Tiền món ăn:</span>
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

                    <div style="margin-top: 25px;">
                        <a href="<?= url('checkout.php') ?>" class="btn btn-primary btn-lg" style="width: 100%; text-align: center;">
                            Tiến Hành Đặt Hàng <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div style="background: white; border-radius: var(--radius-md); padding: 60px 20px; text-align: center; border: 1px solid var(--border-color); max-width: 550px; margin: 0 auto;">
            <div style="font-size: 3.5rem; color: #cbd5e1; margin-bottom: 15px;">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 8px;">Giỏ hàng của bạn đang trống</h2>
            <p style="color: #64748b; margin-bottom: 20px;">Hãy dạo một vòng thực đơn và chọn cho mình những món ngon nhé!</p>
            <a href="<?= url('products.php') ?>" class="btn btn-primary">
                <i class="fa-solid fa-utensils me-2"></i> Xem Thực Đơn Ngay
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
