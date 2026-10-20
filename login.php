<?php
/**
 * TRANG ĐĂNG NHẬP - FOODHOUSE
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    header('Location: ' . url('index.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ Email và Mật khẩu!';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            login_user($user);
            set_flash('success', 'Đăng nhập thành công! Chào mừng ' . $user['fullname']);
            header('Location: ' . url('index.php'));
            exit;
        } else {
            $error = 'Email hoặc mật khẩu không chính xác!';
        }
    }
}

$pageTitle = 'Đăng Nhập';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 450px; margin: 50px auto 80px;">
    <div style="background: white; border-radius: var(--radius-sm); padding: 35px 30px; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.6rem; font-weight: 700; text-align: center; margin-bottom: 20px; color: #1e293b;">
            Đăng Nhập Tài Khoản
        </h2>

        <?php if (!empty($error)): ?>
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 10px 14px; border-radius: 4px; margin-bottom: 18px; color: #b91c1c; font-size: 0.9rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Email đăng nhập</label>
                <input type="email" name="email" class="form-control" required placeholder="nhapemail@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 10px;">
                Đăng Nhập
            </button>
        </form>

        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 0.9rem; color: #64748b;">
            Chưa có tài khoản? <a href="<?= url('register.php') ?>" style="color: var(--primary); font-weight: 600;">Đăng ký ngay</a>
        </div>

        <div style="margin-top: 15px; background: #f8fafc; padding: 10px; border-radius: 4px; font-size: 0.8rem; color: #64748b; line-height: 1.5;">
            <strong>Tài khoản mẫu:</strong><br>
            - Admin: admin@foodhouse.vn / mk: admin123<br>
            - Khách: khachhang@gmail.com / mk: user123
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
