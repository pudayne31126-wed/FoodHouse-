<?php
/**
 * HÀM TIỆN ÍCH DÙNG CHUNG - FOODHOUSE
 * Cung cấp các hàm xử lý dữ liệu và giỏ hàng cơ bản, dễ hiểu
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';

// Hàm làm sạch dữ liệu đầu vào chống XSS
function clean($data) {
    if (is_array($data)) {
        return array_map('clean', $data);
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

// Hàm tạo mã đơn hàng ngắn gọn dễ nhớ
function generate_order_code() {
    return 'DH' . date('ymd') . rand(100, 999);
}

/* ================= QUẢN LÝ GIỎ HÀNG (DÙNG SESSION) ================= */

// Lấy danh sách sản phẩm trong giỏ
function cart_get() {
    return $_SESSION['cart'] ?? [];
}

// Đếm tổng số lượng món ăn trong giỏ
function cart_count() {
    $cart = cart_get();
    $total = 0;
    foreach ($cart as $item) {
        $total += (int)($item['quantity'] ?? 1);
    }
    return $total;
}

// Tính tiền hàng (chưa bao gồm phí ship)
function cart_subtotal() {
    $cart = cart_get();
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += ($item['price'] * $item['quantity']);
    }
    return $subtotal;
}

// Tính tổng tiền thanh toán (tiền hàng + phí ship)
function cart_total() {
    $subtotal = cart_subtotal();
    if ($subtotal <= 0) return 0;
    return $subtotal + DEFAULT_SHIPPING_FEE;
}

// Thêm món vào giỏ hàng
function cart_add($productId, $quantity = 1) {
    global $pdo;
    $quantity = max(1, (int)$quantity);

    $stmt = $pdo->prepare("SELECT id, name, slug, price, original_price, image, status FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product || $product['status'] != 1) {
        return ['success' => false, 'message' => 'Món ăn này hiện đang tạm hết!'];
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'slug' => $product['slug'],
            'price' => (float)$product['price'],
            'original_price' => (float)$product['original_price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }

    return [
        'success' => true,
        'message' => 'Đã thêm món vào giỏ hàng thành công!',
        'cart_count' => cart_count(),
        'cart_subtotal' => cart_subtotal(),
        'formatted_subtotal' => format_price(cart_subtotal())
    ];
}

// Cập nhật số lượng món trong giỏ
function cart_update($productId, $quantity) {
    $quantity = (int)$quantity;
    if (!isset($_SESSION['cart'][$productId])) {
        return ['success' => false, 'message' => 'Món ăn không tồn tại trong giỏ!'];
    }

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
    } else {
        $_SESSION['cart'][$productId]['quantity'] = min(99, $quantity);
    }

    return [
        'success' => true,
        'cart_count' => cart_count(),
        'cart_subtotal' => cart_subtotal(),
        'cart_total' => cart_total(),
        'formatted_total' => format_price(cart_total())
    ];
}

// Xóa món khỏi giỏ
function cart_remove($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    return [
        'success' => true,
        'cart_count' => cart_count(),
        'cart_subtotal' => cart_subtotal(),
        'cart_total' => cart_total()
    ];
}

// Xóa sạch giỏ hàng
function cart_clear() {
    $_SESSION['cart'] = [];
}

/* ================= CÁC HÀM TRUY VẤN CƠ SỞ DỮ LIỆU ================= */

// Lấy toàn bộ danh mục món ăn
function get_all_categories() {
    global $pdo;
    return $pdo->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
}

// Lấy danh sách món ăn nổi bật trang chủ
function get_featured_products($limit = 6) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.is_featured = 1 AND p.status = 1 
        ORDER BY p.id DESC 
        LIMIT ?
    ");
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Lấy chi tiết 1 món ăn theo ID
function get_product_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Lấy các món ăn cùng danh mục liên quan
function get_related_products($categoryId, $excludeId, $limit = 4) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.category_id = ? AND p.id != ? AND p.status = 1 
        ORDER BY p.id DESC 
        LIMIT ?
    ");
    $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(2, $excludeId, PDO::PARAM_INT);
    $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Hiển thị nhãn trạng thái đơn hàng
function render_status_badge($status) {
    $colors = [
        'Chờ xác nhận' => 'badge-warning',
        'Đã xác nhận'  => 'badge-info',
        'Đang giao'     => 'badge-primary',
        'Đã giao'       => 'badge-success',
        'Đã hủy'        => 'badge-danger',
    ];
    $cls = $colors[$status] ?? 'badge-secondary';
    return '<span class="status-badge ' . $cls . '">' . htmlspecialchars($status) . '</span>';
}
