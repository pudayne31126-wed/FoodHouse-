<?php
/**
 * THANH ĐIỀU HƯỚNG NAVBAR - FOODHOUSE
 */
$user = get_logged_in_user();
$cartCount = cart_count();
$currentFile = basename($_SERVER['PHP_SELF']);
?>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-left">
            <span><i class="fa-solid fa-phone me-1"></i> Hotline: <strong><?= APP_PHONE ?></strong></span>
            <span><i class="fa-solid fa-clock me-1"></i> Giờ mở cửa: <strong>08:00 - 22:30</strong></span>
        </div>
        <div class="top-bar-right">
            <span><i class="fa-solid fa-motorcycle me-1"></i> Giao hàng tận nơi trong 30 phút</span>
        </div>
    </div>
</div>

<!-- HEADER CHÍNH -->
<header class="main-header">
    <div class="container navbar-container">
        <!-- LOGO -->
        <a href="<?= url('index.php') ?>" class="logo-wrapper">
            <div class="logo-icon">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <div>Food<span>House</span></div>
        </a>

        <!-- MENU LIÊN KẾT -->
        <nav class="nav-links">
            <a href="<?= url('index.php') ?>" class="nav-link <?= $currentFile === 'index.php' ? 'active' : '' ?>">Trang Chủ</a>
            <a href="<?= url('products.php') ?>" class="nav-link <?= in_array($currentFile, ['products.php', 'product_detail.php']) ? 'active' : '' ?>">Thực Đơn</a>
            <a href="<?= url('orders.php') ?>" class="nav-link <?= $currentFile === 'orders.php' ? 'active' : '' ?>">Đơn Hàng</a>
        </nav>

        <!-- Ô TÌM KIẾM -->
        <form action="<?= url('products.php') ?>" method="GET" class="nav-search-form">
            <i class="fa-solid fa-magnifying-glass nav-search-icon"></i>
            <input type="text" name="search" placeholder="Tìm burger, gà rán, pizza, đồ uống..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>

        <!-- NÚT GIỎ HÀNG & TÀI KHOẢN -->
        <div class="header-actions">
            <!-- NÚT GIỎ HÀNG -->
            <a href="<?= url('cart.php') ?>" class="cart-nav-btn" title="Xem giỏ hàng">
                <i class="fa-solid fa-bag-shopping"></i>
                <span class="cart-badge" id="cart-badge-count" style="<?= $cartCount > 0 ? '' : 'display: none;' ?>">
                    <?= $cartCount ?>
                </span>
            </a>

            <!-- TÀI KHOẢN -->
            <?php if ($user): ?>
                <div class="user-nav-dropdown">
                    <button type="button" class="user-avatar-btn">
                        <div class="user-avatar-icon"><i class="fa-solid fa-user"></i></div>
                        <span><?= htmlspecialchars($user['fullname']) ?></span>
                        <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem; color: #64748b;"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="<?= url('orders.php') ?>"><i class="fa-solid fa-receipt me-1"></i> Lịch sử đơn hàng</a>
                        <hr>
                        <a href="<?= url('logout.php') ?>" style="color: #ef4444;"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= url('login.php') ?>" class="btn btn-outline btn-sm">Đăng Nhập</a>
                <a href="<?= url('register.php') ?>" class="btn btn-primary btn-sm">Đăng Ký</a>
            <?php endif; ?>
        </div>
    </div>
</header>
