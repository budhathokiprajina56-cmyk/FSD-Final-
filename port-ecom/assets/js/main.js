

document.addEventListener('DOMContentLoaded', function() {



    const authModal = document.getElementById('authModal');
    const openAuthBtn = document.getElementById('openAuthModal');
    const closeAuthBtn = document.getElementById('closeAuthModal');
    const modalTabs = document.querySelectorAll('.modal-tab');
    const modalForms = document.querySelectorAll('.modal-form');

    if (openAuthBtn) {
        openAuthBtn.addEventListener('click', function() {
            authModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeAuthBtn) {
        closeAuthBtn.addEventListener('click', closeModal);
    }

    if (authModal) {
        authModal.addEventListener('click', function(e) {
            if (e.target === authModal) {
                closeModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && authModal && authModal.classList.contains('active')) {
            closeModal();
        }
    });
    
    function closeModal() {
        if (authModal) {
            authModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    modalTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            modalTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            modalForms.forEach(form => {
                form.classList.remove('active');
                if (form.dataset.tab === targetTab) {
                    form.classList.add('active');
                }
            });
        });
    });



    const loginForm = document.getElementById('loginForm');
    const loginMessage = document.getElementById('loginMessage');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/port-ecom/public/ajax/login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                loginMessage.textContent = data.message;
                loginMessage.className = 'form-message ' + (data.success ? 'success' : 'error');
                
                if (data.success) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                loginMessage.textContent = 'An error occurred. Please try again.';
                loginMessage.className = 'form-message error';
            }
        });
    }



    const registerForm = document.getElementById('registerForm');
    const registerMessage = document.getElementById('registerMessage');
    
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/port-ecom/public/ajax/register.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                registerMessage.textContent = data.message;
                registerMessage.className = 'form-message ' + (data.success ? 'success' : 'error');
                
                if (data.success) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                registerMessage.textContent = 'An error occurred. Please try again.';
                registerMessage.className = 'form-message error';
            }
        });
    }



    const searchToggle = document.getElementById('searchToggle');
    const searchForm = document.getElementById('searchForm');
    
    if (searchToggle && searchForm) {
        searchToggle.addEventListener('click', function() {
            searchForm.classList.toggle('active');
            if (searchForm.classList.contains('active')) {
                searchForm.querySelector('input').focus();
            }
        });

        document.addEventListener('click', function(e) {
            if (!searchToggle.contains(e.target) && !searchForm.contains(e.target)) {
                searchForm.classList.remove('active');
            }
        });
    }



    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            addToCart(productId, 1, this);
        });
    });



    const addToCartDetail = document.querySelector('.btn-add-cart-detail');
    const productQtyInput = document.getElementById('productQty');
    
    if (addToCartDetail) {
        addToCartDetail.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = productQtyInput ? parseInt(productQtyInput.value) : 1;
            addToCart(productId, quantity, this);
        });
    }

    document.querySelectorAll('.qty-minus, .qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const max = parseInt(input.max) || 99;
            let value = parseInt(input.value) || 1;
            
            if (this.classList.contains('qty-minus')) {
                value = Math.max(1, value - 1);
            } else {
                value = Math.min(max, value + 1);
            }
            
            input.value = value;
        });
    });




    document.querySelectorAll('.cart-qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const quantity = parseInt(this.value) || 1;
            updateCart(productId, quantity);
        });
    });

    document.querySelectorAll('.cart-item .qty-minus, .cart-item .qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const input = cartItem.querySelector('.cart-qty-input');
            const productId = input.dataset.productId;
            let value = parseInt(input.value) || 1;
            
            if (this.classList.contains('qty-minus')) {
                value = Math.max(1, value - 1);
            } else {
                value++;
            }
            
            input.value = value;
            updateCart(productId, value);
        });
    });

    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeFromCart(productId);
        });
    });



    async function addToCart(productId, quantity, button) {
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        
        try {
            const response = await fetch('/port-ecom/public/ajax/cart_actions.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.requireLogin) {

                if (authModal) {
                    authModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
                return;
            }
            
            if (data.success) {

                updateCartCount(data.cartCount);

                if (button) {
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '✓';
                    button.style.background = '#22c55e';
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.style.background = '';
                    }, 1500);
                }
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    }
    
    async function updateCart(productId, quantity) {
        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        
        try {
            const response = await fetch('/port-ecom/public/ajax/cart_actions.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                updateCartCount(data.cartCount);

                window.location.reload();
            }
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    }
    
    async function removeFromCart(productId) {
        const formData = new FormData();
        formData.append('action', 'remove');
        formData.append('product_id', productId);
        
        try {
            const response = await fetch('/port-ecom/public/ajax/cart_actions.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                updateCartCount(data.cartCount);

                const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                if (cartItem) {
                    cartItem.style.opacity = '0';
                    cartItem.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        cartItem.remove();

                        const remainingItems = document.querySelectorAll('.cart-item');
                        if (remainingItems.length === 0) {
                            window.location.reload();
                        }
                    }, 300);
                }
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    }
    
    function updateCartCount(count) {
        const cartCountEl = document.getElementById('cartCount');
        if (cartCountEl) {
            cartCountEl.textContent = count;

            cartCountEl.style.transform = 'scale(1.3)';
            setTimeout(() => {
                cartCountEl.style.transform = '';
            }, 200);
        }
    }



    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });



    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.product-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    document.querySelectorAll('.category-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        observer.observe(card);
    });
    
});

