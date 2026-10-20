<?php
/**
 * TRANG ĐĂNG KÝ - FOODHOUSE
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    header('Location: ' . url('index.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = clean($_POST['fullname'] ?? '');
    $email    = clean($_POST['email'] ?? '');
    $phone    = clean($_POST['phone'] ?? '');
    $address  = clean($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $repass   = $_POST['repass'] ?? '';

    if (empty($fullname) || empty($email) || empty($password)) {
        $error = 'Vui lòng điền các thông tin bắt buộc!';
    } elseif ($password !== $repass) {
        $error = 'Mật khẩu nhập lại không trùng khớp!';
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = 'Email này đã được sử dụng! Vui lòng chọn email khác.';
        } else {
            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (fullname, email, password, phone, address, role)
                VALUES (?, ?, ?, ?, ?, 'customer')
            ");
            $stmt->execute([$fullname, $email, $hashedPass, $phone, $address]);

            set_flash('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
            header('Location: ' . url('login.php'));
            exit;
        }
    }
}

$pageTitle = 'Đăng Ký Tài Khoản';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 480px; margin: 40px auto 80px;">
    <div style="background: white; border-radius: var(--radius-sm); padding: 35px 30px; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.6rem; font-weight: 700; text-align: center; margin-bottom: 20px; color: #1e293b;">
            Đăng Ký Tài Khoản
        </h2>

        <?php if (!empty($error)): ?>
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 10px 14px; border-radius: 4px; margin-bottom: 18px; color: #b91c1c; font-size: 0.9rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Họ và tên <span class="req">*</span></label>
                <input type="text" name="fullname" class="form-control" required placeholder="Nguyễn Văn A" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Email đăng nhập <span class="req">*</span></label>
                <input type="email" name="email" class="form-control" required placeholder="email@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Số điện thoại</label>
                <input type="tel" name="phone" class="form-control" placeholder="0988xxxxxx" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường..." value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu <span class="req">*</span></label>
                <input type="password" name="password" class="form-control" required placeholder="Tối thiểu 6 ký tự">
            </div>

            <div class="form-group">
                <label class="form-label">Nhập lại mật khẩu <span class="req">*</span></label>
                <input type="password" name="repass" class="form-control" required placeholder="Nhập lại mật khẩu trên">
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 10px;">
                Đăng Ký Tài Khoản
            </button>
        </form>

        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 0.9rem; color: #64748b;">
            Đã có tài khoản? <a href="<?= url('login.php') ?>" style="color: var(--primary); font-weight: 600;">Đăng nhập ngay</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
