<?php
/**
 * PHÂN QUYỀN & XÁC THỰC NGƯỜI DÙNG - FOODHOUSE
 */

require_once __DIR__ . '/../config/app.php';

function is_logged_in() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

function get_logged_in_user() {
    return $_SESSION['user'] ?? null;
}

function is_admin() {
    return is_logged_in() && (($_SESSION['user']['role'] ?? '') === 'admin');
}

function require_login($redirectUrl = 'login.php') {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
        set_flash('warning', 'Vui lòng đăng nhập để tiếp tục thao tác!');
        header('Location: ' . url($redirectUrl));
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        set_flash('error', 'Bạn không có quyền truy cập trang Quản Trị Viên!');
        header('Location: ' . url('login.php'));
        exit;
    }
}

function login_user($user) {
    unset($user['password']);
    $_SESSION['user'] = $user;
}

function logout_user() {
    unset($_SESSION['user']);
    unset($_SESSION['cart']);
    session_regenerate_id(true);
}
