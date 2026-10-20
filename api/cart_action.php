<?php
/**
 * API XỬ LÝ GIỎ HÀNG AJAX
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Món ăn không hợp lệ!']);
            exit;
        }
        $res = cart_add($productId, $quantity);
        echo json_encode($res);
        exit;

    case 'update':
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        $res = cart_update($productId, $quantity);
        echo json_encode($res);
        exit;

    case 'remove':
        $productId = (int)($_POST['product_id'] ?? 0);
        $res = cart_remove($productId);
        echo json_encode($res);
        exit;

    case 'count':
        echo json_encode(['cart_count' => cart_count()]);
        exit;

    default:
        echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ!']);
        exit;
}
