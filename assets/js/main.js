/**
 * JAVASCRIPT XỬ LÝ GIAO DIỆN & GIỎ HÀNG AJAX - FOODHOUSE
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Toast Notification
    window.showToast = function (message, type = 'success') {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const iconMap = {
            success: 'fa-circle-check',
            error: 'fa-circle-xmark',
            warning: 'fa-triangle-exclamation',
            info: 'fa-circle-info'
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fa-solid ${iconMap[type] || 'fa-bell'}" style="font-size: 1.2rem;"></i>
            <span>${message}</span>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    };

    // 2. Thêm vào giỏ hàng bằng AJAX
    document.querySelectorAll('.add-cart-btn, .add-cart-icon-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.id;
            let qty = 1;
            
            const qtyInput = document.getElementById('product-qty');
            if (qtyInput) {
                qty = parseInt(qtyInput.value) || 1;
            }

            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            this.disabled = true;

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', qty);

            const apiUrl = (window.APP_URL || '') + 'api/cart_action.php?action=add';

            fetch(apiUrl, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.innerHTML = originalHtml;
                this.disabled = false;

                if (data.success) {
                    showToast(data.message || 'Đã thêm món vào giỏ hàng!', 'success');
                    const badge = document.getElementById('cart-badge-count');
                    if (badge) {
                        badge.textContent = data.cart_count;
                        badge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                    }
                } else {
                    showToast(data.message || 'Có lỗi xảy ra!', 'error');
                }
            })
            .catch(() => {
                this.innerHTML = originalHtml;
                this.disabled = false;
                showToast('Lỗi kết nối máy chủ!', 'error');
            });
        });
    });

    // 3. Xử lý tăng giảm số lượng (+ / -)
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('.qty-input');
            if (!input) return;

            let val = parseInt(input.value) || 1;
            if (this.dataset.action === 'plus') {
                val++;
            } else if (this.dataset.action === 'minus') {
                val = Math.max(1, val - 1);
            }
            input.value = val;

            const productId = this.dataset.id;
            if (productId) {
                updateCartQuantity(productId, val);
            }
        });
    });

    window.updateCartQuantity = function (productId, quantity) {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        const apiUrl = (window.APP_URL || '') + 'api/cart_action.php?action=update';

        fetch(apiUrl, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                showToast(data.message || 'Lỗi cập nhật giỏ hàng!', 'error');
            }
        });
    };

    // 4. Xóa món khỏi giỏ hàng
    document.querySelectorAll('.btn-remove-cart').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!confirm('Bạn có chắc chắn muốn bỏ món này khỏi giỏ hàng?')) return;

            const productId = this.dataset.id;
            const formData = new FormData();
            formData.append('product_id', productId);

            const apiUrl = (window.APP_URL || '') + 'api/cart_action.php?action=remove';

            fetch(apiUrl, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        });
    });



    // 6. Phương thức thanh toán
    const paymentBoxes = document.querySelectorAll('.payment-radio-box');
    paymentBoxes.forEach(box => {
        box.addEventListener('click', function () {
            paymentBoxes.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            const qrInfoBox = document.getElementById('bank-qr-info');
            if (qrInfoBox) {
                if (radio && radio.value === 'BANK_QR') {
                    qrInfoBox.style.display = 'block';
                } else {
                    qrInfoBox.style.display = 'none';
                }
            }
        });
    });

    // 7. Điền thông tin Demo
    window.fillDemoLogin = function (email, pass) {
        const emailInput = document.getElementById('login-email');
        const passInput = document.getElementById('login-password');
        if (emailInput && passInput) {
            emailInput.value = email;
            passInput.value = pass;
            showToast('Đã điền thông tin tài khoản demo!', 'info');
        }
    };
});
