<?php
/**
 * FOOTER CHUNG - FOODHOUSE
 */
$flash = get_flash();
?>
</div><!-- End .page-wrapper -->

<!-- MAIN FOOTER -->
<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Cột 1: Thông tin quán -->
            <div>
                <div class="footer-logo">
                    <i class="fa-solid fa-utensils" style="color: var(--primary);"></i> FoodHouse
                </div>
                <p class="footer-about">
                    Hệ thống đặt món ăn trực tuyến nhanh chóng, tiện lợi với hàng chục món ăn hấp dẫn chuẩn vị, giao nóng tận nơi trong 30 phút.
                </p>
            </div>

            <!-- Cột 2: Danh mục món ăn -->
            <div>
                <h4 class="footer-heading">Danh Mục Món</h4>
                <ul class="footer-links">
                    <li><a href="<?= url('products.php?category=burger') ?>">Burger</a></li>
                    <li><a href="<?= url('products.php?category=ga') ?>">Gà</a></li>
                    <li><a href="<?= url('products.php?category=an-kem') ?>">Ăn Kèm</a></li>
                    <li><a href="<?= url('products.php?category=pizza') ?>">Pizza</a></li>
                    <li><a href="<?= url('products.php?category=do-uong') ?>">Đồ Uống</a></li>
                </ul>
            </div>

            <!-- Cột 3: Liên kết nhanh -->
            <div>
                <h4 class="footer-heading">Liên Kết Nhanh</h4>
                <ul class="footer-links">
                    <li><a href="<?= url('index.php') ?>">Trang chủ</a></li>
                    <li><a href="<?= url('products.php') ?>">Thực đơn món ăn</a></li>
                    <li><a href="<?= url('cart.php') ?>">Xem giỏ hàng</a></li>
                    <li><a href="<?= url('orders.php') ?>">Tra cứu đơn hàng</a></li>
                    <li><a href="<?= url('login.php') ?>">Đăng nhập / Đăng ký</a></li>
                </ul>
            </div>

            <!-- Cột 4: Liên hệ & Giờ mở cửa -->
            <div>
                <h4 class="footer-heading">Liên Hệ & Đặt Hàng</h4>
                <p style="font-size: 0.9rem; margin-bottom: 8px;">
                    <i class="fa-solid fa-location-dot me-2" style="color: var(--primary);"></i> <?= APP_ADDRESS ?>
                </p>
                <p style="font-size: 0.9rem; margin-bottom: 8px;">
                    <i class="fa-solid fa-phone me-2" style="color: var(--primary);"></i> Hotline: <strong><?= APP_PHONE ?></strong>
                </p>
                <p style="font-size: 0.9rem; margin-bottom: 8px;">
                    <i class="fa-solid fa-envelope me-2" style="color: var(--primary);"></i> <?= APP_EMAIL ?>
                </p>
                <p style="font-size: 0.9rem; margin-bottom: 8px;">
                    <i class="fa-solid fa-clock me-2" style="color: var(--primary);"></i> Giờ mở cửa: 08:00 - 22:30
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <strong><?= APP_NAME ?></strong> - Đồ án website bán đồ ăn trực tuyến (PHP & MySQL).</p>
        </div>
    </div>
</footer>

<!-- TOAST CONTAINER -->
<div class="toast-container"></div>

<!-- JS Main Scripts -->
<script src="<?= asset('js/main.js') ?>?v=<?= time() ?>"></script>

<?php if ($flash): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast("<?= addslashes($flash['message']) ?>", "<?= $flash['type'] ?>");
    });
</script>
<?php endif; ?>

</body>
</html>
