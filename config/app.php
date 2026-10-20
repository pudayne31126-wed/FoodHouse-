<?php
/**
 * CẤU HÌNH HỆ THỐNG - FOODHOUSE
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thông tin cửa hàng
define('APP_NAME', 'FoodHouse');
define('APP_TAGLINE', 'Thiên Đường Đặt Món Ăn Nhanh & Tươi Ngon');
define('APP_PHONE', '0901 234 567');
define('APP_EMAIL', 'foodhouse@gmail.com');
define('APP_ADDRESS', '123 Đường Cầu Giấy, Hà Nội');
define('DEFAULT_SHIPPING_FEE', 15000);

// Tự động nhận diện đường dẫn gốc (BASE_URL)
$projectDir = str_replace('\\', '/', dirname(__DIR__));
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';
$subDir = (!empty($docRoot) && strpos($projectDir, $docRoot) === 0) ? substr($projectDir, strlen($docRoot)) : '';
define('BASE_URL', rtrim($subDir, '/'));

// Hàm tạo link trang web
function url($path = '') {
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}

// Hàm lấy đường dẫn file tĩnh (css, js, ảnh)
function asset($path = '') {
    return url('assets/' . ltrim($path, '/'));
}

// Định dạng tiền tệ VNĐ
function format_price($number) {
    return number_format((float)$number, 0, ',', '.') . ' đ';
}

// Định dạng ngày giờ hiển thị
function format_date($datetime) {
    if (!$datetime) return '';
    return date('d/m/Y H:i', strtotime($datetime));
}

// Thông báo tạm (Flash message)
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
