<?php
// Wishlist include file - provides frontend JS for heart toggle functionality
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}
?>
<style>
/* Wishlist Toast */
.wishlist-toast {
    position: fixed;
    bottom: 90px;
    right: 30px;
    background: linear-gradient(135deg, #2d0d10, #641820);
    color: #fff;
    padding: 16px 28px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 500;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.4s ease;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 4px solid #ff6b81;
    pointer-events: none;
}

.wishlist-toast.show {
    transform: translateY(0);
    opacity: 1;
}

.wishlist-toast .toast-icon {
    font-size: 24px;
}
</style>

<div class="wishlist-toast" id="wishlistToast">
    <span class="toast-icon">❤️</span>
    <span class="toast-text">Added to Wishlist!</span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wishlistToast = document.getElementById('wishlistToast');
    if (!wishlistToast) return;
    
    const wishlistToastText = wishlistToast.querySelector('.toast-text');
    let wishlistTimer;

    function showWishlistToast(message) {
        wishlistToastText.textContent = message;
        wishlistToast.classList.add('show');
        clearTimeout(wishlistTimer);
        wishlistTimer = setTimeout(() => wishlistToast.classList.remove('show'), 2500);
    }

    function updateWishlistBadge(count) {
        // Update header wishlist badge
        const link = document.querySelector('.wishlist-link');
        if (!link) return;
        let badge = link.querySelector('.wishlist-badge');
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'wishlist-badge';
                link.appendChild(badge);
            }
            badge.textContent = count;
        } else {
            if (badge) badge.remove();
        }
    }

    // Handle wishlist button clicks
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const card = this.closest('.product-card');
            if (!card) return;

            const id = card.dataset.id;
            const name = card.dataset.name;
            const price = card.dataset.price;
            const image = card.dataset.image;
            const weight = card.dataset.weight || '';

            if (!id) return;

            const isFilled = this.classList.contains('filled');
            
            const formData = new FormData();
            if (isFilled) {
                formData.append('action', 'remove_from_wishlist');
                formData.append('id', id);
            } else {
                formData.append('action', 'add_to_wishlist');
                formData.append('id', id);
                formData.append('name', name);
                formData.append('price', price);
                formData.append('image', image);
                formData.append('weight', weight);
            }

            fetch('cart_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (isFilled) {
                        this.classList.remove('filled');
                        this.textContent = '♡';
                        showWishlistToast('Removed from wishlist');
                    } else {
                        this.classList.add('filled');
                        this.textContent = '❤️';
                        showWishlistToast('✓ ' + name + ' added to wishlist!');
                    }
                    updateWishlistBadge(data.wishlist_count);
                } else {
                    showWishlistToast('Error: ' + (data.message || 'Could not update wishlist'));
                }
            })
            .catch(err => {
                showWishlistToast('Error updating wishlist');
                console.error('Wishlist error:', err);
            });
        });
    });

    // Set initial wishlist state on page load - mark products that are already in wishlist
    <?php if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])): 
        $wishlist_ids = array_keys($_SESSION['wishlist']);
    ?>
    const wishlistIds = <?php echo json_encode($wishlist_ids); ?>;
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        const card = btn.closest('.product-card');
        if (card) {
            const id = card.dataset.id;
            if (wishlistIds.includes(id)) {
                btn.classList.add('filled');
                btn.textContent = '❤️';
            }
        }
    });
    <?php endif; ?>
});
</script>
