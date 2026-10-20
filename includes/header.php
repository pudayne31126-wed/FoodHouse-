<?php
/**
 * HEADER GIAO DIỆN KHÁCH HÀNG - FOODHOUSE
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = $pageTitle ?? 'Thiên Đường Đặt Món Ăn Nhanh & Tươi Ngon';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= defined('APP_NAME') ? APP_NAME : 'FoodHouse' ?></title>
    <meta name="description" content="<?= defined('APP_TAGLINE') ? APP_TAGLINE : 'Thiên Đường Đặt Món Ăn Nhanh & Tươi Ngon' ?>. Thực đơn đa dạng, nguyên liệu tươi sạch mỗi ngày.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Style -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=<?= time() ?>">
    
    <script>
        window.APP_URL = "<?= url() ?>/";
    </script>
</head>
<body>
<?php include_once __DIR__ . '/navbar.php'; ?>
<div class="page-wrapper">
