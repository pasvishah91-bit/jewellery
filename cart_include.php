<?php
if (!defined('JEWELLERY_ACCESS')) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    define('JEWELLERY_ACCESS', true);
}

// This file provides shared JS for cart, wishlist, search, and custom requests
// It is included by footer.php on all pages
?>
<script>
// ============ TOAST NOTIFICATION ============
(function() {
    // Create toast container if not exists
    if (document.getElementById('cartToast')) return;
    
    const toast = document.createElement('div');
    toast.className = 'cart-toast';
    toast.id = 'cartToast';
    toast.innerHTML = `
        <span class="toast-icon">✓</span>
        <span class="toast-text">Added to cart!</span>
        <button class="toast-close">✕</button>
    `;
    document.body.appendChild(toast);
    
    const toastText = toast.querySelector('.toast-text');
    const toastClose = toast.querySelector('.toast-close');
    let toastTimer;

    window.showToast = function(message) {
        toastText.textContent = message;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 3000);
    };

    toastClose.addEventListener('click', function() {
        toast.classList.remove('show');
        clearTimeout(toastTimer);
    });
})();

// ============ SEARCH FUNCTIONALITY ============
(function() {
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    if (!searchInput || !searchDropdown) return;
    
    let searchTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const query = this.value.trim();
        
        if (query.length < 1) {
            searchDropdown.classList.remove('active');
            return;
        }

        searchTimer = setTimeout(() => {
            fetch('search_handler.php?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    let html = '';
                    data.results.forEach(item => {
                        html += `
                            <div class="search-result-item" onclick="window.location.href='product.php?id=${encodeURIComponent(item.id)}'">
                                <img src="${item.image}" alt="${item.name}">
                                <div>
                                    <div class="sr-name">${item.name}</div>
                                    <div class="sr-price">${item.price}</div>
                                    <div class="sr-category">${item.category} • ${item.weight}</div>
                                </div>
                            </div>
                        `;
                    });
                    searchDropdown.innerHTML = html;
                    searchDropdown.classList.add('active');
                } else {
                    searchDropdown.innerHTML = '<div class="search-no-results">No products found for "' + query + '"<br><br><button class="request-custom-btn" onclick="openCustomRequest(\'' + encodeURIComponent(query) + '\')">📦 Request Custom Order</button></div>';
                    searchDropdown.classList.add('active');
                }
            })
            .catch(err => {
                console.error('Search error:', err);
            });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.closest('.search-wrapper') && !e.target.closest('.search-dropdown')) {
            searchDropdown.classList.remove('active');
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchDropdown.classList.remove('active');
            this.blur();
        }
    });
})();

// ============ CUSTOM REQUEST MODAL ============
(function() {
    // Create modal if not exists
    if (document.getElementById('customRequestModal')) return;
    
    const modalHTML = `
    <div class="custom-modal-overlay" id="customRequestModal">
    <div class="custom-modal">
    <div class="modal-header">
    <h2>📦 Request Custom Order</h2>
    <button class="modal-close" id="customModalClose">✕</button>
    </div>
    <div class="form-row">
    <label>What product are you looking for? <span class="required">*</span></label>
    <input type="text" name="custom_product_name" id="custom_product_name" placeholder="e.g., Gold Diamond Necklace, Pearl Earrings..." required>
    <div class="form-error" id="custom_product_name_error">Please describe what you're looking for</div>
    </div>
    <div class="form-row">
    <label>Describe your requirements <span class="required">*</span></label>
    <textarea name="custom_description" id="custom_description" placeholder="Describe the design, metal type, stone preference, approximate weight, occasion, etc." required></textarea>
    <div class="form-error" id="custom_description_error">Please describe your requirements</div>
    </div>
    <div class="form-row-half">
    <div class="form-row">
    <label>Budget Range</label>
    <select name="custom_budget" id="custom_budget">
    <option value="">Select budget range</option>
    <option value="Under ₹25,000">Under ₹25,000</option>
    <option value="₹25,000 - ₹50,000">₹25,000 - ₹50,000</option>
    <option value="₹50,000 - ₹1,00,000">₹50,000 - ₹1,00,000</option>
    <option value="₹1,00,000 - ₹2,50,000">₹1,00,000 - ₹2,50,000</option>
    <option value="Above ₹2,50,000">Above ₹2,50,000</option>
    </select>
    </div>
    <div class="form-row">
    <label>Your Name</label>
    <input type="text" name="custom_name" id="custom_name" placeholder="Your name">
    </div>
    </div>
    <div class="form-row-half">
    <div class="form-row">
    <label>Phone Number</label>
    <input type="tel" name="custom_phone" id="custom_phone" placeholder="Your phone number">
    </div>
    <div class="form-row">
    <label>Email</label>
    <input type="email" name="custom_email" id="custom_email" placeholder="Your email">
    </div>
    </div>
    <button type="submit" class="modal-submit-btn" id="customRequestBtn">SUBMIT REQUEST</button>
    <div class="modal-note">Our team will get back to you within 24-48 hours with custom design options and pricing.</div>
    </div>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = document.getElementById('customRequestModal');
    const modalClose = document.getElementById('customModalClose');
    const requestBtn = document.getElementById('customRequestBtn');

    window.openCustomRequest = function(searchQuery) {
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            const productField = document.getElementById('custom_product_name');
            if (productField && searchQuery) {
                productField.value = decodeURIComponent(searchQuery);
            }
            const dd = document.getElementById('searchDropdown');
            if (dd) dd.classList.remove('active');
        }
    };

    window.closeCustomRequest = function() {
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    if (modalClose) {
        modalClose.addEventListener('click', window.closeCustomRequest);
    }
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeCustomRequest();
            }
        });
    }
    if (requestBtn) {
        requestBtn.addEventListener('click', function() {
            const productName = document.getElementById('custom_product_name').value.trim();
            const description = document.getElementById('custom_description').value.trim();

            document.querySelectorAll('#customRequestModal .form-error').forEach(el => el.classList.remove('show'));

            let valid = true;
            if (!productName) {
                document.getElementById('custom_product_name_error').classList.add('show');
                valid = false;
            }
            if (!description) {
                document.getElementById('custom_description_error').classList.add('show');
                valid = false;
            }
            if (!valid) return;

            const budget = document.getElementById('custom_budget').value;
            const name = document.getElementById('custom_name').value.trim();
            const phone = document.getElementById('custom_phone').value.trim();
            const email = document.getElementById('custom_email').value.trim();

            this.disabled = true;
            this.textContent = 'SUBMITTING...';

            const formData = new FormData();
            formData.append('action', 'custom_request');
            formData.append('product_name', productName);
            formData.append('description', description);
            formData.append('budget', budget);
            formData.append('name', name);
            formData.append('phone', phone);
            formData.append('email', email);

            fetch('search_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.closeCustomRequest();
                    if (window.showToast) {
                        window.showToast('✅ Custom request submitted! Our team will contact you soon.');
                    } else {
                        alert('✅ Custom request submitted! Our team will contact you soon.');
                    }
                    document.getElementById('custom_product_name').value = '';
                    document.getElementById('custom_description').value = '';
                    document.getElementById('custom_budget').value = '';
                    document.getElementById('custom_name').value = '';
                    document.getElementById('custom_phone').value = '';
                    document.getElementById('custom_email').value = '';
                } else {
                    if (window.showToast) {
                        window.showToast('Error: ' + (data.message || 'Could not submit request'));
                    } else {
                        alert('Error: ' + (data.message || 'Could not submit request'));
                    }
                }
                requestBtn.disabled = false;
                requestBtn.textContent = 'SUBMIT REQUEST';
            })
            .catch(err => {
                requestBtn.disabled = false;
                requestBtn.textContent = 'SUBMIT REQUEST';
                if (window.showToast) {
                    window.showToast('Error submitting request. Please try again.');
                } else {
                    alert('Error submitting request. Please try again.');
                }
                console.error('Custom request error:', err);
            });
        });
    }
})();

// ============ ADD TO CART (shared) ============
(function() {
    document.querySelectorAll('.add-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const card = this.closest('.product-card');
            if (!card) return;

            const id = card.dataset.id;
            const name = card.dataset.name;
            const price = card.dataset.price;
            const image = card.dataset.image;
            const weight = card.dataset.weight || '';

            if (!id || !name || !price) {
                if (window.showToast) window.showToast('Error: Product data missing');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'add_to_cart');
            formData.append('id', id);
            formData.append('name', name);
            formData.append('price', price);
            formData.append('image', image);
            formData.append('weight', weight);

            fetch('cart_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (window.showToast) window.showToast('✓ ' + name + ' added to cart!');
                    // Update cart badge
                    let badge = document.querySelector('.cart-badge');
                    if (badge) {
                        badge.textContent = data.cart_count;
                    } else {
                        const cartLink = document.querySelector('.cart-link');
                        if (cartLink) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'cart-badge';
                            newBadge.textContent = data.cart_count;
                            cartLink.appendChild(newBadge);
                        }
                    }
                } else {
                    if (window.showToast) window.showToast('Error: ' + (data.message || 'Could not add to cart'));
                }
            })
            .catch(err => {
                if (window.showToast) window.showToast('Error adding to cart');
                console.error('Cart error:', err);
            });
        });
    });
})();

// ============ PRODUCT CARD CLICK - NAVIGATION ============
(function() {
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.add-cart') || e.target.closest('.wishlist') || e.target.closest('.wishlist-btn') || e.target.closest('button')) {
                return;
            }
            const id = this.dataset.id;
            if (id) {
                window.location.href = 'product.php?id=' + encodeURIComponent(id);
            }
        });
    });
})();
</script>
