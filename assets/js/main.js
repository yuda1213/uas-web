/* JavaScript untuk interaksi frontend */

// Konfirmasi delete
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Format currency
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

// Validasi form
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        return form.checkValidity();
    }
    return true;
}

// Tambah ke keranjang (akan diimplementasi di cart.js)
function addToCart(productId, productName, price) {
    // Ambil keranjang dari localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Cek apakah produk sudah ada di keranjang
    let existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    showToast('Produk ditambahkan ke keranjang!', 'success');
}

// Toast notification
function showToast(message, type = 'info') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    container.style.left = '50%';
    container.style.right = 'auto';
    container.style.transform = 'translateX(-50%)';
    container.style.alignItems = 'center';

    const toast = document.createElement('div');
    toast.className = `alert alert-${type} toast`;

    const icon = document.createElement('div');
    icon.className = 'toast-icon';
    icon.textContent = type === 'success' ? '✓' : type === 'danger' ? '!' : type === 'warning' ? '⚠' : 'i';

    const text = document.createElement('div');
    text.className = 'toast-message';
    text.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => toast.remove());

    toast.appendChild(icon);
    toast.appendChild(text);
    toast.appendChild(closeBtn);
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-6px)';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

// Close alert
document.addEventListener('DOMContentLoaded', function() {
    // Auto-close alert after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
});

// Backdrop-filter support detection and dynamic CSS injection to avoid linter warnings
(function() {
    try {
        if (typeof CSS !== 'undefined' && (CSS.supports('backdrop-filter','blur(12px)') || CSS.supports('-webkit-backdrop-filter','blur(12px)'))) {
            const style = document.createElement('style');
            style.type = 'text/css';
            style.appendChild(document.createTextNode(`
                .toast {
                    -webkit-backdrop-filter: blur(12px);
                    backdrop-filter: blur(12px);
                    background: rgba(255, 255, 255, 0.80);
                }
            `));
            document.head.appendChild(style);
        }
    } catch (e) {
        // Graceful fallback if CSS.supports not available or any error occurs
        console.warn('Backdrop filter injection skipped:', e);
    }
})();
