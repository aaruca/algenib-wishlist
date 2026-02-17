document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Wishlist State
    const Wishlist = {
        items: [], // Array of product IDs

        init: function() {
            // Load initial state from localized script
            if ( typeof AlgWishlistSettings !== 'undefined' ) {
                this.items = AlgWishlistSettings.initial_items.map(String);
            }
            
            // Render initial state
            this.updateUI();

            // Bind Events
            document.body.addEventListener('click', this.handleClick.bind(this));
            
            // Listen for Doofinder Render Events
            document.addEventListener('df:layer:render', this.handleDoofinderRender.bind(this));
        },

        handleClick: function(e) {
            // Check for .alg-add-to-wishlist button
            let btn = e.target.closest('.alg-add-to-wishlist');
            
            // Check for Doofinder button
            if (!btn) btn = e.target.closest('.wbw-doofinder-btn');

            if ( btn ) {
                e.preventDefault();
                const productId = btn.getAttribute('data-product-id');
                this.toggleItem(productId, btn);
            }
        },

        toggleItem: function(productId, btn) {
            // Optimistic UI Update
            const isAdded = this.items.includes(productId);
            
            if (isAdded) {
                this.items = this.items.filter(id => id !== productId);
            } else {
                this.items.push(productId);
            }
            this.updateUI(); // Reflect change immediately

            // Send AJAX
            const formData = new FormData();
            formData.append('action', 'alg_add_to_wishlist');
            formData.append('product_id', productId);
            formData.append('todo', isAdded ? 'remove' : 'add');
            // formData.append('nonce', AlgWishlistSettings.nonce);

            fetch(AlgWishlistSettings.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Sync server state just in case
                    this.items = data.data.items.map(String);
                    this.updateUI();
                } else {
                    // Revert on failure
                    console.error('Wishlist Action Failed', data);
                }
            });
        },

        updateUI: function() {
            // Update all buttons on page
            const buttons = document.querySelectorAll('.alg-add-to-wishlist, .wbw-doofinder-btn');
            buttons.forEach(btn => {
                const id = btn.getAttribute('data-product-id');
                if ( this.items.includes(id) ) {
                    btn.classList.add('active');
                    btn.setAttribute('aria-label', AlgWishlistSettings.i18n.removed);
                } else {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-label', AlgWishlistSettings.i18n.added);
                }
            });

            // Update Counters
            const counters = document.querySelectorAll('.alg-wishlist-count');
            counters.forEach(counter => {
                counter.innerText = this.items.length;
                counter.classList.toggle('hidden', this.items.length === 0);
            });
        },

        handleDoofinderRender: function(e) {
            // When Doofinder renders loaded results, we need to re-scan
            // and apply 'active' class to hearts that are in wishlist.
            this.updateUI();
        }
    };

    Wishlist.init();
});
