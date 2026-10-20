<?php
/**
 * TRANG CHI TIẾT MÓN ĂN - FOODHOUSE
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$productId = (int)($_GET['id'] ?? 0);
$product = get_product_by_id($productId);

if (!$product) {
    set_flash('error', 'Không tìm thấy món ăn này!');
    header('Location: ' . url('products.php'));
    exit;
}

$pageTitle = $product['name'] . ' - Chi Tiết Món Ăn';
require_once __DIR__ . '/includes/header.php';

$relatedProducts = get_related_products($product['category_id'], $product['id'], 4);
?>

<div class="container" style="margin-top: 20px; margin-bottom: 60px;">
    <!-- Đường dẫn Breadcrumb -->
    <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 20px;">
        <a href="<?= url('index.php') ?>" style="color: #64748b;">Trang chủ</a> / 
        <a href="<?= url('products.php?category=' . urlencode($product['category_slug'])) ?>" style="color: #64748b;"><?= htmlspecialchars($product['category_name']) ?></a> / 
        <span style="color: var(--primary); font-weight: 600;"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <!-- KHUNG CHI TIẾT MÓN ĂN -->
    <div class="product-detail-layout">
        <!-- Ảnh món ăn -->
        <div class="detail-gallery">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="detail-img-main">
        </div>

        <!-- Thông tin món ăn -->
        <div class="detail-info">
            <span class="detail-category-badge">
                <i class="fa-solid fa-tag me-1"></i> <?= htmlspecialchars($product['category_name']) ?>
            </span>
            <h1 class="detail-title"><?= htmlspecialchars($product['name']) ?></h1>

            <!-- Giá bán -->
            <div class="detail-price-box">
                <span class="detail-current-price"><?= format_price($product['price']) ?></span>
                <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                    <span class="detail-original-price"><?= format_price($product['original_price']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Mô tả chi tiết -->
            <p class="detail-desc"><?= nl2br(htmlspecialchars($product['description'] ?? 'Món ăn thơm ngon, nóng hổi, nguyên liệu được chế biến tươi sạch mỗi ngày.')) ?></p>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: var(--radius-sm); padding: 12px 16px; margin-bottom: 25px; font-size: 0.88rem; color: #475569;">
                <div><i class="fa-solid fa-motorcycle me-2" style="color: var(--primary);"></i> Giao hàng tận nơi trong 30 phút</div>
                <div style="margin-top: 5px;"><i class="fa-solid fa-box-open me-2" style="color: var(--primary);"></i> Đóng gói hộp giữ nhiệt sạch sẽ, an toàn</div>
            </div>

            <!-- Chọn số lượng & Nút thêm vào giỏ -->
            <div class="detail-action-group">
                <div class="quantity-picker">
                    <button type="button" class="qty-btn" data-action="minus"><i class="fa-solid fa-minus"></i></button>
                    <input type="number" id="product-qty" class="qty-input" value="1" min="1" max="99" readonly>
                    <button type="button" class="qty-btn" data-action="plus"><i class="fa-solid fa-plus"></i></button>
                </div>

                <button type="button" class="btn btn-primary btn-lg add-cart-btn" data-id="<?= $product['id'] ?>" style="flex: 1;">
                    <i class="fa-solid fa-cart-plus me-1"></i> Thêm Vào Giỏ Hàng
                </button>
            </div>
        </div>
    </div>

    <!-- MÓN ĂN GỢI Ý CÙNG DANH MỤC -->
    <?php if (!empty($relatedProducts)): ?>
        <div style="margin-top: 60px;">
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 1.4rem; font-weight: 700; color: #1e293b;">Món Ngon Cùng Danh Mục</h3>
            </div>

            <div class="products-grid">
                <?php foreach ($relatedProducts as $rel): ?>
                    <div class="product-card">
                        <div class="product-thumb">
                            <a href="<?= url('product_detail.php?id=' . $rel['id']) ?>">
                                <img src="<?= htmlspecialchars($rel['image']) ?>" alt="<?= htmlspecialchars($rel['name']) ?>" class="product-img" loading="lazy">
                            </a>
                        </div>
                        <div class="product-body">
                            <h4 class="product-title">
                                <a href="<?= url('product_detail.php?id=' . $rel['id']) ?>">
                                    <?= htmlspecialchars($rel['name']) ?>
                                </a>
                            </h4>
                            <div class="product-price-row">
                                <span class="current-price"><?= format_price($rel['price']) ?></span>
                                <button type="button" class="add-cart-icon-btn" data-id="<?= $rel['id'] ?>" title="Thêm vào giỏ">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
