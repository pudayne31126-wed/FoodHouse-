<?php
/**
 * KẾT NỐI CƠ SỞ DỮ LIỆU MYSQL - FOODHOUSE
 * Sử dụng PDO chuẩn, dễ hiểu và dễ kết nối trong XAMPP
 */

$db_host = 'localhost';
$db_name = 'food_shop';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // Nếu chưa có database food_shop, tự động tạo và nạp database.sql
    if ($e->getCode() == 1049 || str_contains($e->getMessage(), 'Unknown database')) {
        try {
            $pdoRoot = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $sqlFile = dirname(__DIR__) . '/database.sql';
            if (file_exists($sqlFile)) {
                $pdoRoot->exec("USE `$db_name`");
                $pdoRoot->exec(file_get_contents($sqlFile));
            }
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (Exception $ex) {
            die("<div style='font-family: sans-serif; padding: 20px; background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; border-radius: 8px; margin: 30px auto; max-width: 600px;'><h3>Lỗi kết nối cơ sở dữ liệu</h3><p>" . htmlspecialchars($e->getMessage()) . "</p><p>Vui lòng kiểm tra MySQL trong XAMPP Control Panel!</p></div>");
        }
    } else {
        // Nếu không kết nối được MySQL, chuyển sang SQLite để đồ án luôn chạy mượt mà
        $sqliteFile = dirname(__DIR__) . '/food_shop.sqlite';
        if (file_exists($sqliteFile)) {
            $pdo = new PDO("sqlite:" . $sqliteFile, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } else {
            die("<div style='font-family: sans-serif; padding: 20px; background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; border-radius: 8px; margin: 30px auto; max-width: 600px;'><h3>Lỗi kết nối cơ sở dữ liệu</h3><p>" . htmlspecialchars($e->getMessage()) . "</p><p>Vui lòng kiểm tra MySQL trong XAMPP Control Panel!</p></div>");
        }
    }
}

