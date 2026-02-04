/* ════════════════════════════════════════════════════════════════════════════════
   COFFEE SHOP - PROFESSIONAL JAVASCRIPT v2.0
   Enhanced Functionality & Error Handling
   ════════════════════════════════════════════════════════════════════════════════ */

// Configuration
const APP_CONFIG = {
    baseUrl: document.querySelector('meta[name="base-url"]')?.content || '/',
    cartStorageKey: 'coffee_shop_cart',
    sessionTimeout: 3600000, // 1 hour in milliseconds
    alertDuration: 5000 // Auto-hide alerts after 5 seconds
};

// ============================================================================
// CART MANAGER - Enhanced with localStorage
// ============================================================================

class CartManager {
    constructor() {
        this.cart = this.loadCart();
        this.initEventListeners();
    }

    loadCart() {
        try {
            const stored = localStorage.getItem(APP_CONFIG.cartStorageKey);
            return stored ? JSON.parse(stored) : [];
        } catch (error) {
            console.error('Error loading cart:', error);
            return [];
        }
    }

    saveCart() {
        try {
            localStorage.setItem(APP_CONFIG.cartStorageKey, JSON.stringify(this.cart));
            this.updateCartUI();
            this.dispatchEvent('cart-updated', { cart: this.cart });
        } catch (error) {
            console.error('Error saving cart:', error);
            this.showAlert('Error saving cart', 'error');
        }
    }

    addItem(id, name, price, quantity = 1) {
        if (!id || !name || price === undefined) {
            console.error('Invalid item parameters');
            return false;
        }

        const existingItem = this.cart.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.quantity += parseInt(quantity);
        } else {
            this.cart.push({
                id,
                name,
                price: parseFloat(price),
                quantity: parseInt(quantity),
                addedAt: new Date().getTime()
            });
        }

        this.saveCart();
        return true;
    }

    removeItem(id) {
        this.cart = this.cart.filter(item => item.id !== id);
        this.saveCart();
        return true;
    }

    updateQuantity(id, quantity) {
        const item = this.cart.find(item => item.id === id);
        if (item) {
            const q = parseInt(quantity);
            if (q <= 0) {
                this.removeItem(id);
            } else {
                item.quantity = q;
                this.saveCart();
            }
            return true;
        }
        return false;
    }

    clearCart() {
        this.cart = [];
        this.saveCart();
        return true;
    }

    getCart() {
        return [...this.cart];
    }

    getTotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    }

    getTotalItems() {
        return this.cart.reduce((sum, item) => sum + item.quantity, 0);
    }

    updateCartUI() {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = this.getTotalItems();
            badge.style.display = this.getTotalItems() > 0 ? 'flex' : 'none';
        }
    }

    dispatchEvent(eventName, detail = {}) {
        window.dispatchEvent(new CustomEvent(eventName, { detail }));
    }

    initEventListeners() {
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-add-to-cart')) {
                const productId = e.target.dataset.productId;
                const productName = e.target.dataset.productName;
                const productPrice = e.target.dataset.productPrice;
                const quantity = e.target.dataset.quantity || 1;

                if (this.addItem(productId, productName, productPrice, quantity)) {
                    this.showAlert(`${productName} ditambahkan ke keranjang`, 'success');
                }
            }
        });
    }

    showAlert(message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} animate-slide-right`;
        alert.innerHTML = `<span>${message}</span>`;
        
        document.body.insertBefore(alert, document.body.firstChild);
        
        setTimeout(() => {
            alert.remove();
        }, APP_CONFIG.alertDuration);
    }
}

// ============================================================================
// FORM VALIDATION
// ============================================================================

class FormValidator {
    static validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    static validatePassword(password) {
        return password && password.length >= 6;
    }

    static validatePhone(phone) {
        const re = /^[\d\s\-\+\(\)]{10,}$/;
        return re.test(phone.replace(/\s/g, ''));
    }

    static validateForm(formElement) {
        const errors = [];
        const inputs = formElement.querySelectorAll('[required]');

        inputs.forEach(input => {
            if (!input.value.trim()) {
                errors.push(`${input.previousElementSibling?.textContent || input.name} harus diisi`);
            }

            // Email validation
            if (input.type === 'email' && input.value && !this.validateEmail(input.value)) {
                errors.push('Email tidak valid');
            }

            // Password validation
            if (input.type === 'password' && input.value && !this.validatePassword(input.value)) {
                errors.push('Password minimal 6 karakter');
            }

            // Phone validation
            if (input.type === 'tel' && input.value && !this.validatePhone(input.value)) {
                errors.push('Nomor telepon tidak valid');
            }
        });

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    static displayErrors(formElement, errors) {
        // Clear previous errors
        const existingErrors = formElement.querySelectorAll('.field-error');
        existingErrors.forEach(el => el.remove());

        // Display new errors
        errors.forEach(error => {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error alert alert-danger';
            errorDiv.textContent = error;
            formElement.insertBefore(errorDiv, formElement.firstChild);
        });
    }
}

// ============================================================================
// NOTIFICATION SYSTEM
// ============================================================================

class NotificationManager {
    static show(message, type = 'info', duration = APP_CONFIG.alertDuration) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} animate-fade`;
        
        const iconMap = {
            success: '✓',
            error: '✕',
            warning: '!',
            info: 'ℹ'
        };

        alert.innerHTML = `
            <span class="alert-icon">${iconMap[type]}</span>
            <span>${message}</span>
        `;

        document.body.insertBefore(alert, document.body.firstChild);

        setTimeout(() => {
            alert.classList.remove('animate-fade');
            setTimeout(() => alert.remove(), 300);
        }, duration);
    }

    static success(message) {
        this.show(message, 'success');
    }

    static error(message) {
        this.show(message, 'error');
    }

    static warning(message) {
        this.show(message, 'warning');
    }

    static info(message) {
        this.show(message, 'info');
    }
}

// ============================================================================
// TABLE UTILITIES
// ============================================================================

class TableManager {
    static sortTable(columnIndex, ascending = true) {
        const table = document.querySelector('table');
        if (!table) return;

        const rows = Array.from(table.querySelectorAll('tbody tr'));
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();

            // Try numeric comparison
            const aNum = parseFloat(aValue);
            const bNum = parseFloat(bValue);

            if (!isNaN(aNum) && !isNaN(bNum)) {
                return ascending ? aNum - bNum : bNum - aNum;
            }

            // String comparison
            return ascending
                ? aValue.localeCompare(bValue)
                : bValue.localeCompare(aValue);
        });

        const tbody = table.querySelector('tbody');
        rows.forEach(row => tbody.appendChild(row));
    }

    static filterTable(searchTerm) {
        const table = document.querySelector('table');
        if (!table) return;

        const rows = table.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(searchTerm.toLowerCase());
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        return visibleCount;
    }

    static exportCSV() {
        const table = document.querySelector('table');
        if (!table) return;

        let csv = [];
        table.querySelectorAll('tr').forEach(row => {
            const cells = [];
            row.querySelectorAll('td, th').forEach(cell => {
                cells.push('"' + cell.textContent.trim().replace(/"/g, '""') + '"');
            });
            csv.push(cells.join(','));
        });

        const csvContent = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
        const link = document.createElement('a');
        link.setAttribute('href', csvContent);
        link.setAttribute('download', `table-${new Date().getTime()}.csv`);
        link.click();
    }
}

// ============================================================================
// API REQUEST HELPER
// ============================================================================

class ApiClient {
    static async request(url, options = {}) {
        try {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                },
                ...options
            };

            const response = await fetch(url, defaultOptions);

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const data = await response.json();
            return { success: true, data };
        } catch (error) {
            console.error('Request error:', error);
            return { success: false, error: error.message };
        }
    }

    static async get(url) {
        return this.request(url, { method: 'GET' });
    }

    static async post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    static async put(url, data) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    static async delete(url) {
        return this.request(url, { method: 'DELETE' });
    }
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Format date
function formatDate(dateString) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ============================================================================
// CONFIRMATION DIALOGS
// ============================================================================

function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

function confirmAction(message) {
    return confirm(message);
}

// ============================================================================
// INITIALIZATION ON PAGE LOAD
// ============================================================================

document.addEventListener('DOMContentLoaded', () => {
    // Initialize cart manager
    window.cartManager = new CartManager();
    window.cartManager.updateCartUI();

    // Initialize form validations
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const validation = FormValidator.validateForm(form);
            if (!validation.isValid) {
                e.preventDefault();
                FormValidator.displayErrors(form, validation.errors);
                return false;
            }
        });
    });

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.remove();
        }, APP_CONFIG.alertDuration);
    });

    // Add loading state to forms
    document.querySelectorAll('form').forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            form.addEventListener('submit', () => {
                submitBtn.disabled = true;
                submitBtn.classList.add('loading');
                submitBtn.textContent = 'Loading...';
            });
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    console.log('Coffee Shop App initialized successfully');
});

// ============================================================================
// GLOBAL ERROR HANDLER
// ============================================================================

window.addEventListener('error', (event) => {
    console.error('Global error:', event.error);
    NotificationManager.error('Terjadi kesalahan. Silakan coba lagi.');
});

window.addEventListener('unhandledrejection', (event) => {
    console.error('Unhandled rejection:', event.reason);
    NotificationManager.error('Terjadi kesalahan. Silakan coba lagi.');
});

// ============================================================================
// END OF JAVASCRIPT
// ============================================================================
