<?php
/**
 * ĐĂNG XUẤT - FOODHOUSE
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

logout_user();
set_flash('info', 'Bạn đã đăng xuất tài khoản thành công!');
header('Location: ' . url('index.php'));
exit;
