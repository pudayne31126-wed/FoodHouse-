-- ====================================================================
-- CƠ SỞ DỮ LIỆU BÁN ĐỒ ĂN TRỰC TUYẾN - FOODHOUSE (BẢN CHUẨN ĐỒ ÁN)
-- Tên cơ sở dữ liệu: food_shop
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `food_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `food_shop`;

-- Xóa bảng cũ nếu đã tồn tại (theo thứ tự khóa ngoại)
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

-- 1. BẢNG NGƯỜI DÙNG (Tài khoản)
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'customer', -- 'admin' hoặc 'customer'
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. BẢNG DANH MỤC MÓN ĂN
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BẢNG MÓN ĂN (SẢN PHẨM)
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(170) NOT NULL,
  `price` DECIMAL(12, 0) NOT NULL,
  `original_price` DECIMAL(12, 0) DEFAULT NULL,
  `image` VARCHAR(500) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0, -- 1: Món nổi bật, 0: Bình thường
  `status` TINYINT(1) NOT NULL DEFAULT 1,      -- 1: Đang bán, 0: Tạm hết
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BẢNG ĐƠN HÀNG
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_code` VARCHAR(30) NOT NULL UNIQUE,
  `user_id` INT DEFAULT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `customer_email` VARCHAR(100) DEFAULT NULL,
  `customer_address` VARCHAR(255) NOT NULL,
  `note` TEXT DEFAULT NULL,
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'COD', -- COD hoặc Banking
  `shipping_fee` DECIMAL(12, 0) NOT NULL DEFAULT 15000,
  `total_amount` DECIMAL(12, 0) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Chờ xác nhận', -- Chờ xác nhận, Đang giao, Đã giao, Đã hủy
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. BẢNG CHI TIẾT ĐƠN HÀNG
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT DEFAULT NULL,
  `product_name` VARCHAR(150) NOT NULL,
  `product_image` VARCHAR(500) DEFAULT NULL,
  `price` DECIMAL(12, 0) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(12, 0) NOT NULL,
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- DỮ LIỆU MẪU (DỄ SỬ DỤNG VÀ TEST)
-- ====================================================================

-- 1. Tài khoản mẫu (Mật khẩu: admin123 / user123)
INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `phone`, `address`, `role`) VALUES
(1, 'Quản Trị Viên', 'admin@foodhouse.vn', '$2y$10$.lc7l8f7b/SRD/yD8GUIpeKSz/c7i4ws9J8Vnlt.sKa1cHNh8CWgW', '0901234567', 'Tòa nhà TechTower, Cầu Giấy, Hà Nội', 'admin'),
(2, 'Nguyễn Văn An', 'khachhang@gmail.com', '$2y$10$WDSjJDIuyQah1fh3mivJ8ODA/3QraROMPjM9Ps8HpLFd0cg99CWaG', '0988776655', 'Số 123 Đường Cầu Giấy, Hà Nội', 'customer');

-- 2. 5 Danh mục món ăn
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `image`, `description`, `display_order`) VALUES
(1, 'Burger', 'burger', 'fa-burger', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80', 'Burger bò Úc, burger gà giòn rụm và burger ngập tràn phô mai thơm béo', 1),
(2, 'Gà', 'ga', 'fa-drumstick-bite', 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=600&q=80', 'Gà rán giòn tan, cánh gà chiên thơm phức và gà sốt BBQ đậm đà chuẩn vị', 2),
(3, 'Ăn kèm', 'an-kem', 'fa-cookie-bite', 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=600&q=80', 'Khoai tây chiên vàng giòn, xúc xích nướng, phô mai que và gà viên giòn rụm', 3),
(4, 'Pizza', 'pizza', 'fa-pizza-slice', 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=600&q=80', 'Pizza bò nướng xốt thơm, pizza xúc xích Pepperoni và pizza hải sản phô mai kéo sợi', 4),
(5, 'Đồ uống', 'do-uong', 'fa-glass-water', 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80', 'Nước giải khát có ga Coca-Cola, Pepsi mát lạnh, trà đào thanh mát và nước ép tươi', 5);

-- 3. 18 Món ăn mới
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `price`, `original_price`, `image`, `description`, `ingredients`, `prep_time`, `is_featured`, `is_bestseller`, `status`) VALUES
-- Burger
(1, 1, 'Burger bò', 'burger-bo', 55000, 65000, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80', 'Burger bò nướng than hoa thơm lừng với thịt bò Úc xay mọng nước, phô mai Cheddar và rau xà lách tươi sạch.', 'Thịt bò Úc xay, sốt tiêu đen, phô mai Cheddar, xà lách, cà chua, bánh mì mè', '8-12 phút', 1, 1, 1),
(2, 1, 'Burger gà', 'burger-ga', 49000, 59000, 'https://images.unsplash.com/photo-1625813506062-0aeb1d7a094b?auto=format&fit=crop&w=800&q=80', 'Burger phi lê gà chiên xù giòn rụm bên ngoài mọng nước bên trong kèm sốt mayonnaise béo thơm và dưa chuột muối.', 'Phi lê ức gà tươi chiên giòn, sốt mayonnaise, bắp cải thái sợi, bánh burger', '8-12 phút', 1, 0, 1),
(3, 1, 'Burger phô mai', 'burger-pho-mai', 59000, 69000, 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80', 'Burger gấp đôi lớp phô mai béo ngậy tan chảy, miếng thịt bò đậm vị nướng xì xèo cùng hành tây caramel thơm ngọt.', 'Thịt bò hảo hạng, phô mai Mozzarella & Cheddar đôi, sốt phô mai đặc biệt, hành tây', '10-15 phút', 1, 1, 1),
-- Gà
(4, 2, 'Gà rán', 'ga-ran', 42000, 50000, 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=800&q=80', 'Miếng gà rán tẩm bột gia vị bí truyền chiên vàng óng, lớp da siêu giòn rụm, thịt bên trong mềm ngọt nóng hổi.', 'Đùi gà góc tư tươi, bột chiên giòn đặc biệt, sốt ớt chua ngọt', '10-15 phút', 1, 1, 1),
(5, 2, 'Cánh gà', 'canh-ga', 45000, 52000, 'https://images.unsplash.com/photo-1527477378406-444a9742ddb8?auto=format&fit=crop&w=800&q=80', 'Phần cánh gà chiên giòn tan, áo lớp gia vị thảo mộc nhẹ nhàng thơm phức, chấm tương ớt hoặc sốt mayonnaise.', 'Cánh gà khúc giữa, gia vị tỏi ớt, bột bắp, sốt chấm', '10-12 phút', 0, 0, 1),
(6, 2, 'Gà sốt BBQ', 'ga-sot-bbq', 48000, 58000, 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?auto=format&fit=crop&w=800&q=80', 'Gà chiên vàng giòn quyện đều sốt BBQ cay ngọt đậm đà hương khói nướng đặc trưng, rắc mè rang thơm lừng.', 'Thịt gà tươi, sốt BBQ nhập khẩu, mè rang, ớt bột paprika', '12-15 phút', 1, 1, 1),
-- Ăn kèm
(7, 3, 'Khoai tây chiên', 'khoai-tay-chien', 29000, 35000, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=800&q=80', 'Từng thanh khoai tây chiên vàng ruộm, bên ngoài giòn tan bên trong bùi mềm, rắc chút muối tiêu hoặc bột phô mai thơm phức.', 'Khoai tây củ tươi chọn lọc, muối tinh, bột phô mai lắc', '5-8 phút', 1, 1, 1),
(8, 3, 'Xúc xích', 'xuc-xich', 20000, 25000, 'https://images.unsplash.com/photo-1541544741938-0af808871cc0?auto=format&fit=crop&w=800&q=80', 'Xúc xích nướng than hoa thơm nức mũi, lớp vỏ căng bóng giòn dai sần sật, chấm tương cà hoặc tương ớt.', 'Thịt heo xông khói, vỏ collagen, gia vị tiêu tỏi', '5-7 phút', 0, 0, 1),
(9, 3, 'Phô mai que', 'pho-mai-que', 32000, 38000, 'https://images.unsplash.com/photo-1531749668029-2db88e4276c7?auto=format&fit=crop&w=800&q=80', 'Que phô mai Mozzarella lăn bột xù chiên vàng ruộm, kéo sợi dài dai ngậy, ăn nóng hổi vô cùng hấp dẫn.', 'Phô mai Mozzarella khối, bột chiên xù, trứng gà', '6-8 phút', 1, 0, 1),
(10, 3, 'Gà viên', 'ga-vien', 35000, 42000, 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=800&q=80', 'Gà viên Popcorn chiên giòn tan cỡ vừa miệng, thịt ức gà mềm ngọt tẩm ướp tiêu đen thơm ngon.', 'Ức gà tươi thái viên, bột bắp, tiêu sọ, sốt tương cà', '6-10 phút', 0, 0, 1),
-- Pizza
(11, 4, 'Pizza bò', 'pizza-bo', 99000, 119000, 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=800&q=80', 'Pizza đế bánh nướng giòn xốp truyền thống, phủ thịt bò băm xào thảo mộc, sốt cà chua Ý và đẫm phô mai Mozzarella béo ngậy.', 'Bột mì Ý, thịt bò tươi băm, sốt Marinara, phô mai Mozzarella, ngô ngọt, ớt chuông', '15-20 phút', 1, 1, 1),
(12, 4, 'Pizza xúc xích', 'pizza-xuc-xich', 89000, 105000, 'https://images.unsplash.com/photo-1628840042765-356cda07504e?auto=format&fit=crop&w=800&q=80', 'Pizza xúc xích Pepperoni cay nhẹ đặc trưng, ngập tràn phô mai nướng vàng xém thơm phức trên nền sốt cà chua đậm đà.', 'Xúc xích Pepperoni thái lát, sốt cà chua nướng, phô mai Mozzarella, lá oregano', '15-18 phút', 1, 1, 1),
(13, 4, 'Pizza hải sản', 'pizza-hai-san', 115000, 135000, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=800&q=80', 'Pizza kết hợp tôm sú giòn ngọt, mực ống tươi thái khoanh cùng ớt chuông xanh đỏ và lớp phô mai béo ngậy tan chảy.', 'Tôm tươi bóc vỏ, mực ống, phô mai Mozzarella, hành tây, ớt chuông, sốt tiêu đen', '15-20 phút', 1, 0, 1),
-- Đồ uống
(14, 5, 'Coca-Cola', 'coca-cola', 15000, 18000, 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=800&q=80', 'Lon Coca-Cola 330ml ướp lạnh sảng khoái với bọt ga mát rượi, bùng nổ hương vị khi kết hợp cùng pizza và burger.', 'Nước ngọt có ga Coca-Cola chính hãng lon 330ml', '1-2 phút', 0, 0, 1),
(15, 5, 'Pepsi', 'pepsi', 15000, 18000, 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=800&q=80', 'Lon Pepsi vị ngọt dịu ướp lạnh, giải khát cực đã trong những bữa tiệc đồ ăn nhanh.', 'Nước ngọt có ga Pepsi chính hãng lon 330ml', '1-2 phút', 0, 0, 1),
(16, 5, 'Trà đào', 'tra-dao', 28000, 35000, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80', 'Trà lài thanh tao hòa cùng vị ngọt thơm từ siro đào tự nhiên, kèm 3 lát đào ngâm giòn sần sật mát lạnh.', 'Trà túi lọc thơm, đào ngâm tươi, sả chanh, đá viên', '3-5 phút', 1, 1, 1),
(17, 5, 'Nước cam', 'nuoc-cam', 25000, 30000, 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?auto=format&fit=crop&w=800&q=80', 'Nước ép từ 100% cam tươi mọng nước, giàu vitamin C tự nhiên giúp thanh nhiệt cơ thể và tốt cho sức khỏe.', 'Cam sành tươi vắt lấy nước, mật ong nhẹ, đá lạnh', '3-5 phút', 0, 0, 1),
(18, 5, 'Nước suối', 'nuoc-suoi', 10000, 12000, 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?auto=format&fit=crop&w=800&q=80', 'Chai nước khoáng tinh khiết 500ml ướp lạnh, thanh khiết và tiện lợi.', 'Nước khoáng tinh khiết đóng chai 500ml', '1-2 phút', 0, 0, 1);
