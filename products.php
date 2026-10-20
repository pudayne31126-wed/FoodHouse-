<?php
/**
 * TRANG THỰC ĐƠN CHỌN MÓN - FOODHOUSE
 */
$pageTitle = 'Thực Đơn Món Ăn - Tha Hồ Chọn Món';
require_once __DIR__ . '/includes/header.php';

$categories = get_all_categories();

// Nhận tham số lọc
$currentCategorySlug = clean($_GET['category'] ?? '');
$searchKeyword = clean($_GET['search'] ?? '');
$priceFilter = clean($_GET['price'] ?? '');
$sortBy = clean($_GET['sort'] ?? 'newest');

// Xây dựng điều kiện truy vấn
$whereClauses = ["p.status = 1"];
$params = [];

if (!empty($currentCategorySlug)) {
    $whereClauses[] = "c.slug = ?";
    $params[] = $currentCategorySlug;
}

if (!empty($searchKeyword)) {
    $whereClauses[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = '%' . $searchKeyword . '%';
    $params[] = '%' . $searchKeyword . '%';
}

if ($priceFilter === 'under_40') {
    $whereClauses[] = "p.price < 40000";
} elseif ($priceFilter === '40_60') {
    $whereClauses[] = "p.price >= 40000 AND p.price <= 60000";
} elseif ($priceFilter === 'over_60') {
    $whereClauses[] = "p.price > 60000";
}

$orderBy = "p.id DESC";
if ($sortBy === 'price_asc') {
    $orderBy = "p.price ASC";
} elseif ($sortBy === 'price_desc') {
    $orderBy = "p.price DESC";
}

$whereSql = implode(' AND ', $whereClauses);
$sql = "
    SELECT p.*, c.name AS category_name, c.slug AS category_slug 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE $whereSql 
    ORDER BY $orderBy
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!-- TIÊU ĐỀ TRANG -->
<div class="menu-page-header">
    <div class="container" style="text-align: center;">
        <h1 class="section-title">Thực Đơn Món Ăn</h1>
        <p class="section-desc">Cơm tấm, phở bò gia truyền, gà rán giòn rụm và các loại đồ uống thanh mát.</p>
    </div>
</div>

<div class="container" style="margin-bottom: 60px;">
    <!-- THANH BỘ LỌC CHỌN MÓN -->
    <div class="filter-bar">
        <!-- Danh mục dạng nút bấm -->
        <div class="category-pills">
            <a href="<?= url('products.php') ?>" class="pill-btn <?= empty($currentCategorySlug) ? 'active' : '' ?>">
                Tất Cả Món
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= url('products.php?category=' . urlencode($cat['slug']) . (!empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : '')) ?>" 
                   class="pill-btn <?= $currentCategorySlug === $cat['slug'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Lọc giá & Sắp xếp -->
        <form method="GET" action="<?= url('products.php') ?>" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <?php if (!empty($currentCategorySlug)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($currentCategorySlug) ?>">
            <?php endif; ?>
            <?php if (!empty($searchKeyword)): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($searchKeyword) ?>">
            <?php endif; ?>

            <select name="price" class="filter-select" onchange="this.form.submit()">
                <option value="">-- Mức giá --</option>
                <option value="under_40" <?= $priceFilter === 'under_40' ? 'selected' : '' ?>>Dưới 40.000 đ</option>
                <option value="40_60" <?= $priceFilter === '40_60' ? 'selected' : '' ?>>40.000 - 60.000 đ</option>
                <option value="over_60" <?= $priceFilter === 'over_60' ? 'selected' : '' ?>>Trên 60.000 đ</option>
            </select>

            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="newest" <?= $sortBy === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                <option value="price_asc" <?= $sortBy === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                <option value="price_desc" <?= $sortBy === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
            </select>
        </form>
    </div>

    <!-- SỐ LƯỢNG MÓN TÌM THẤY -->
    <div style="margin-bottom: 20px;">
        <p style="font-size: 0.95rem; color: #64748b;">
            Tìm thấy <strong><?= count($products) ?></strong> món ăn
            <?php if (!empty($searchKeyword)): ?>
                với từ khóa "<strong><?= htmlspecialchars($searchKeyword) ?></strong>"
                <a href="<?= url('products.php') ?>" style="color: var(--primary); margin-left: 6px;">(Xóa tìm kiếm)</a>
            <?php endif; ?>
        </p>
    </div>

    <!-- DANH SÁCH MÓN ĂN -->
    <?php if (count($products) > 0): ?>
        <div class="products-grid">
            <?php foreach ($products as $item): ?>
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
    <?php else: ?>
        <div style="background: white; border-radius: var(--radius-sm); padding: 50px 20px; text-align: center; border: 1px dashed var(--border-color); max-width: 500px; margin: 0 auto;">
            <div style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"><i class="fa-solid fa-bowl-food"></i></div>
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 8px;">Không tìm thấy món ăn phù hợp</h3>
            <p style="color: #64748b; margin-bottom: 20px; font-size: 0.9rem;">Hãy thử chọn danh mục khác hoặc tìm kiếm với từ khóa khác nhé!</p>
            <a href="<?= url('products.php') ?>" class="btn btn-primary">Xem Toàn Bộ Thực Đơn</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
