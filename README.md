# 🍔 FoodHouse - Website Đặt Món Ăn Nhanh Trực Tuyến

> **Đồ án**: Phân tích và thiết kế hệ thống Website bán đồ ăn nhanh trực tuyến FoodHouse  
> **Sinh viên thực hiện**: Nguyễn Công Phú  
> **Mã sinh viên**: 2924105628  
> **Lớp**: TH29.26  
> **Trường**: Đại học Kinh doanh và Công nghệ Hà Nội  
> **Khoa**: Công nghệ Thông tin  

---

## 📖 Giới thiệu dự án
**FoodHouse** là hệ thống website thương mại điện tử chuyên cung cấp dịch vụ đặt món ăn nhanh (Fast Food) trực tuyến. Website được thiết kế với giao diện hiện đại, tối ưu trải nghiệm người dùng (UX/UI), hỗ trợ đầy đủ quy trình từ duyệt thực đơn, tùy chọn số lượng, quản lý giỏ hàng, đặt hàng thanh toán và tra cứu tình trạng đơn hàng thời gian thực.

---

## ✨ Tính năng nổi bật
- 🍕 **Trang chủ & Banner**: Giới thiệu thương hiệu, món ăn bán chạy (Best Seller), chương trình khuyến mãi.
- 📋 **Thực đơn đa dạng**: Phân loại theo Burger, Pizza, Gà rán, Đồ uống, Tráng miệng; lọc món ăn theo danh mục.
- 🔍 **Chi tiết món ăn**: Xem hình ảnh chất lượng cao, mô tả nguyên liệu, giá thành và chọn số lượng đặt món.
- 🛒 **Giỏ hàng trực quan**: Thêm/sửa/xóa số lượng món ăn, tự động tính tổng tiền và phí giao hàng.
- 💳 **Thanh toán & Đặt hàng**: Nhập thông tin người nhận, ghi chú giao hàng, lựa chọn phương thức thanh toán (COD hoặc Chuyển khoản QR).
- 📦 **Theo dõi đơn hàng**: Tra cứu trạng thái đơn hàng (Chờ xác nhận, Đang chuẩn bị, Đang giao, Đã giao) theo mã đơn hàng.
- 👤 **Tài khoản người dùng**: Đăng ký, đăng nhập bảo mật và quản lý thông tin tài khoản.
- ⚡ **Hỗ trợ đa CSDL**: Hoạt động mượt mà với MySQL trên XAMPP hoặc tự động chuyển sang SQLite mà không cần cấu hình phức tạp.

---

## 🛠️ Công nghệ sử dụng
- **Backend**: PHP 8.x (lập trình hướng thủ tục kết hợp mô-đun hóa, PDO Database Layer).
- **Cơ sở dữ liệu**: MySQL / MariaDB (đi kèm file `database.sql`) + SQLite Fallback (`food_shop.sqlite`).
- **Frontend**: HTML5, Vanilla CSS3 (Custom Design System, Flexbox, CSS Grid, Micro-animations), JavaScript ES6.
- **Môi trường triển khai**: XAMPP / Apache / PHP Built-in Server.

---

## 📁 Cấu trúc thư mục
```plaintext
do an/
├── api/                    # Xử lý các request AJAX (giỏ hàng, cập nhật món)
│   └── cart_action.php
├── assets/                 # Tài nguyên tĩnh
│   ├── css/                # File CSS tùy chỉnh giao diện
│   └── js/                 # Javascript xử lý tương tác
├── config/                 # Cấu hình hệ thống
│   ├── app.php             # Hằng số, URL, session
│   └── database.php        # Kết nối CSDL PDO (MySQL & SQLite fallback)
├── includes/               # Các component dùng chung
│   ├── auth.php            # Middleware kiểm tra đăng nhập
│   ├── footer.php          # Chân trang
│   ├── functions.php       # Hàm tiện ích (tiền tệ, validate, cart)
│   ├── header.php          # Thẻ head, link css
│   └── navbar.php          # Thanh điều hướng header
├── cart.php                # Giao diện giỏ hàng
├── checkout.php            # Giao diện & xử lý đặt hàng
├── database.sql            # File script tạo bảng & dữ liệu mẫu MySQL
├── food_shop.sqlite        # CSDL SQLite dự phòng
├── index.php               # Trang chủ
├── login.php               # Đăng nhập
├── logout.php              # Đăng xuất
├── order_success.php       # Thông báo đặt hàng thành công & hóa đơn
├── orders.php              # Tra cứu và theo dõi đơn hàng
├── product_detail.php      # Chi tiết món ăn
├── products.php            # Danh sách thực đơn / tìm kiếm món
├── register.php            # Đăng ký tài khoản
└── README.md               # Tài liệu hướng dẫn đồ án
```

---

## 🚀 Hướng dẫn cài đặt & Chạy website

### Cách 1: Chạy qua XAMPP (Khuyến nghị cho chấm bài)
1. Sao chép toàn bộ thư mục `do an` vào thư mục: `C:\xampp\htdocs\do an`.
2. Mở **XAMPP Control Panel**, nhấn **Start** cho cả **Apache** và **MySQL**.
3. Mở trình duyệt web, truy cập `http://localhost/phpmyadmin/`.
4. Tạo một cơ sở dữ liệu mới có tên: `food_shop` (chọn Collation `utf8mb4_unicode_ci`).
5. Chọn tab **Import** (Nhập), chọn file [database.sql](file:///c:/xampp/htdocs/do%20an/database.sql) trong thư mục dự án và bấm **Import**.
6. Truy cập website tại địa chỉ:
   ```
   http://localhost/do an/
   ```

### Cách 2: Chạy tức thì qua PHP Built-in Server (Không cần cài đặt CSDL)
Nếu máy chấm không tiện cài đặt MySQL, hệ thống đã tích hợp sẵn CSDL SQLite:
1. Mở Terminal / PowerShell tại thư mục dự án:
   ```powershell
   php -S localhost:8000
   ```
2. Mở trình duyệt và truy cập:
   ```
   http://localhost:8000/
   ```

---

## 👨‍💻 Tác giả
- **Sinh viên**: Nguyễn Công Phú
- **Lớp**: TH29.26
- **Khoa**: Công nghệ Thông tin - Trường Đại học Kinh doanh và Công nghệ Hà Nội
