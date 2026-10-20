<?php
/**
 * TRANG CHỦ - FOODHOUSE
 */
$pageTitle = 'Trang Chủ - Đặt Món Ăn Nhanh & Tươi Ngon';
require_once __DIR__ . '/includes/header.php';

$categories = get_all_categories();
$featuredProducts = get_featured_products(6);
?>

<!-- BANNER CHÍNH (HERO BANNER) -->
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-content">
            <h1 class="hero-title">
                Đồ Ăn Nóng Hổi,<br>
                <span>Giao Tận Nơi</span> Nhanh Chóng!
            </h1>
            <p class="hero-subtitle">
                Thực đơn phong phú từ burger hảo hạng, gà rán giòn rụm, pizza nóng hổi đến các món ăn kèm và đồ uống mát lạnh. Đặt món ngay để thưởng thức hương vị tươi ngon nhất!
            </p>
            <div class="hero-cta">
                <a href="<?= url('products.php') ?>" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-utensils me-2"></i> Khám Phá Thực Đơn
                </a>
                <a href="#featured-section" class="btn btn-secondary btn-lg">
                    <i class="fa-solid fa-star me-2"></i> Món Ăn Nổi Bật
                </a>
            </div>
            <div class="hero-features">
                <div class="hero-feature-item">
                    <div class="hero-feature-icon"><i class="fa-solid fa-motorcycle"></i></div>
                    <span>Giao hàng nhanh</span>
                </div>
                <div class="hero-feature-item">
                    <div class="hero-feature-icon"><i class="fa-solid fa-seedling"></i></div>
                    <span>Nguyên liệu tươi sạch</span>
                </div>
                <div class="hero-feature-item">
                    <div class="hero-feature-icon"><i class="fa-solid fa-shield-heart"></i></div>
                    <span>An toàn vệ sinh</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" alt="Món ăn ngon FoodHouse" class="hero-img-main">
        </div>
    </div>
</section>

<!-- KHỐI DANH MỤC MÓN ĂN -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Danh Mục Món Ăn</h2>
            <p class="section-desc">Chọn nhanh món ăn theo các thể loại bạn yêu thích.</p>
        </div>

        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= url('products.php?category=' . urlencode($cat['slug'])) ?>" class="category-card">
                    <div class="category-icon-box">
                        <i class="fa-solid <?= htmlspecialchars(!empty($cat['icon']) ? $cat['icon'] : 'fa-utensils') ?>"></i>
                    </div>
                    <h3 class="category-name"><?= htmlspecialchars($cat['name']) ?></h3>
                    <p class="category-subtext"><?= htmlspecialchars($cat['description'] ?? '') ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- KHỐI MÓN ĂN NỔI BẬT -->
<section class="section" id="featured-section" style="background: #ffffff;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Món Ăn Nổi Bật</h2>
            <p class="section-desc">Những món ăn ngon được nhiều khách hàng yêu thích và lựa chọn nhất.</p>
        </div>

        <div class="products-grid">
            <?php foreach ($featuredProducts as $item): ?>
                <div class="product-card">
                    <div class="product-thumb">
                        <a href="<?= url('product_detail.php?id=' . $item['id']) ?>">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img" loading="lazy">
                        </a>
                        <?php if (!empty($item['original_price']) && $item['original_price'] > $item['price']): ?>
                            <?php $discount = round((($item['original_price'] - $item['price']) / $item['original_price']) * 100); ?>
                            <div class="product-badge-group">
                                <span class="badge-tag badge-sale">-<?= $discount ?>%</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="product-body">
                        <a href="<?= url('products.php?category=' . urlencode($item['category_slug'])) ?>" class="product-category-link">
                            <?= htmlspecialchars($item['category_name']) ?>
                        </a>
                        <h3 class="product-title">
                            <a href="<?= url('product_detail.php?id=' . $item['id']) ?>">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </h3>
                        <p class="product-desc-short"><?= htmlspecialchars($item['description'] ?? '') ?></p>

                        <div class="product-price-row">
                            <div class="price-box">
                                <span class="current-price"><?= format_price($item['price']) ?></span>
                                <?php if (!empty($item['original_price']) && $item['original_price'] > $item['price']): ?>
                                    <span class="original-price"><?= format_price($item['original_price']) ?></span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="add-cart-icon-btn" data-id="<?= $item['id'] ?>" title="Thêm vào giỏ">
                                <i class="fa-solid fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 35px;">
            <a href="<?= url('products.php') ?>" class="btn btn-secondary btn-lg">
                Xem Thêm Món Khác <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
