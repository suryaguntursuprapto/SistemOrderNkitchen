@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="rounded-2xl shadow-lg p-8 mb-10 mt-2" style="background: linear-gradient(to right, #ea580c, #ef4444);">
        <h1 class="text-4xl font-extrabold text-white mb-2">
            Menu Pempek N-Kitchen
        </h1>
        <p class="text-lg text-white/90">Pilih menu favorit Anda dan masukkan ke keranjang untuk proses *checkout*.</p>
    </div>

    <!-- Category Filter Tabs -->
    @if($categories->count() > 0)
    <div class="mb-8">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('customer.order.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-full font-medium transition-all duration-200 
                      {{ !request('category') ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-700 border border-gray-200 hover:bg-orange-50 hover:border-orange-300' }}">
                🍽️ Semua Menu
            </a>
            @foreach($categories as $category)
            <a href="{{ route('customer.order.index', ['category' => $category->id]) }}" 
               class="inline-flex items-center px-4 py-2 rounded-full font-medium transition-all duration-200
                      {{ request('category') == $category->id ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-700 border border-gray-200 hover:bg-orange-50 hover:border-orange-300' }}">
                {{ $category->icon }} {{ $category->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(to right, #3b82f6, #6366f1);">
                    <h3 class="text-xl font-bold text-white">Daftar Menu Tersedia</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($menus as $menu)
                        <div class="bg-white border border-gray-100 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <div class="flex">
                                @if($menu->image)
                                <img src="{{ Storage::url($menu->image) }}"
                                     alt="{{ $menu->name }}"
                                     class="w-32 h-32 object-cover rounded-l-2xl flex-shrink-0">
                                @else
                                <div class="w-32 h-32 bg-gray-200 rounded-l-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                                
                                <div class="p-4 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-900">{{ $menu->name }}</h4>
                                        <p class="text-sm text-gray-500 line-clamp-2 mb-2">{{ $menu->description }}</p>
                                    </div>
                                    
                                    <p class="text-xl font-extrabold text-orange-600 mb-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                @if($menu->is_available)
                                <div class="flex items-center space-x-3">
                                    <select class="quantity-select w-20 px-2 py-2 border border-gray-300 rounded-xl text-base font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 shadow-inner"
                                            data-menu-id="{{ $menu->id }}"
                                            data-menu-name="{{ $menu->name }}"
                                            data-menu-price="{{ $menu->price }}"
                                            data-menu-image="{{ $menu->image ? Storage::url($menu->image) : '' }}">
                                        <option value="0">0</option>
                                        @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    
                                    <button class="add-to-cart-btn flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2.5 rounded-xl text-base font-semibold transition-all duration-300 shadow-md disabled:bg-gray-300 disabled:cursor-not-allowed transform hover:scale-[1.02]"
                                            data-menu-id="{{ $menu->id }}"
                                            disabled>
                                        <span class="original-text">Tambah</span>
                                        <span class="success-text hidden">✔ Ditambahkan!</span>
                                    </button>
                                </div>
                                @else
                                <button class="w-full bg-gray-300 text-gray-500 px-4 py-2.5 rounded-xl text-base font-medium cursor-not-allowed" disabled>
                                    Tidak Tersedia
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($menus->hasPages())
                    <div class="mt-8">
                        {{ $menus->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden sticky top-6 border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(to right, #10b981, #0f766e);">
                    <h3 class="text-xl font-bold text-white">Keranjang Belanja</h3>
                    <p class="text-sm text-white/90">
                        <span id="cart-count">0</span> item dipilih
                    </p>
                </div>
                <div class="p-6">
                    <div id="cart-items" class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">
                        </div>

                    <div id="empty-cart" class="text-center py-10">
                        <svg class="mx-auto h-14 w-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 21"></path>
                        </svg>
                        <p class="mt-3 text-lg font-medium text-gray-500">Keranjang masih kosong</p>
                    </div>

                    <div id="cart-summary" class="hidden">
                        <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-100 shadow-inner">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-700">Subtotal Pesanan:</span>
                                <span class="text-xl font-extrabold text-orange-600" id="cart-subtotal">Rp 0</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Biaya pengiriman akan dihitung saat checkout.</p>
                        </div>

                        <form id="checkout-form" action="{{ route('customer.order.create') }}" method="GET">
                            <input type="hidden" name="cart_data" id="cart-data">
                            <button type="submit" id="checkout-btn" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3.5 rounded-xl font-bold text-lg transition-all duration-300 shadow-xl transform hover:-translate-y-0.5">
                                Lanjut ke Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global cart object
let cart = {};
// Hapus deliveryFee hardcode

// Initialize cart functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart initialized');
    
    // Check local storage for persistent cart (optional, but good practice)
    // const storedCart = localStorage.getItem('nkitchen_cart');
    // if (storedCart) {
    //     try {
    //         cart = JSON.parse(storedCart);
    //         updateCartDisplay();
    //     } catch (e) {
    //         console.error('Failed to load cart from storage', e);
    //         cart = {};
    //     }
    // }

    // Quantity select change handler
    document.querySelectorAll('.quantity-select').forEach(select => {
        select.addEventListener('change', function() {
            const btn = this.parentElement.querySelector('.add-to-cart-btn');
            btn.disabled = this.value === '0';
            console.log('Quantity changed:', this.value);
        });
    });

    // Add to cart button handler
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const menuId = this.dataset.menuId;
            const select = this.parentElement.querySelector('.quantity-select');
            const quantity = parseInt(select.value);
            
            console.log('Adding to cart:', {menuId, quantity, data: select.dataset});
            
            if (quantity > 0) {
                addToCart(menuId, quantity, select.dataset);
                select.value = '0';
                this.disabled = true;
                
                // Show success feedback
                const originalText = this.querySelector('.original-text');
                const successText = this.querySelector('.success-text');
                
                if (originalText && successText) {
                    originalText.classList.add('hidden');
                    successText.classList.remove('hidden');
                } else {
                    this.textContent = 'Ditambahkan!';
                }
                
                this.classList.remove('bg-orange-600', 'hover:bg-orange-700');
                this.classList.add('bg-green-600');
                
                setTimeout(() => {
                    if (originalText && successText) {
                        originalText.classList.remove('hidden');
                        successText.classList.add('hidden');
                    }
                    this.classList.remove('bg-green-600');
                    this.classList.add('bg-orange-600', 'hover:bg-orange-700');
                }, 1000);
            }
        });
    });

    // Checkout form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        if (Object.keys(cart).length === 0) {
            e.preventDefault();
            alert('Keranjang masih kosong!');
            return;
        }
        
        // Set cart data to hidden input
        document.getElementById('cart-data').value = JSON.stringify(Object.values(cart));
        // Save cart to local storage (optional)
        // localStorage.setItem('nkitchen_cart', JSON.stringify(cart)); 
        console.log('Checkout data:', Object.values(cart));
    });
    
    // Initial display update
    updateCartDisplay();
});

// Add item to cart
function addToCart(menuId, quantity, menuData) {
    if (cart[menuId]) {
        cart[menuId].quantity += quantity;
    } else {
        cart[menuId] = {
            id: parseInt(menuId),
            name: menuData.menuName,
            price: parseInt(menuData.menuPrice),
            quantity: quantity,
            image: menuData.menuImage
        };
    }
    updateCartDisplay();
}

// Remove item from cart
function removeFromCart(menuId) {
    delete cart[menuId];
    updateCartDisplay();
}

// Update item quantity in cart
function updateCartQuantity(menuId, quantity) {
    if (quantity <= 0) {
        removeFromCart(menuId);
    } else {
        if (cart[menuId]) {
            cart[menuId].quantity = quantity;
            updateCartDisplay();
        }
    }
}

// Update cart display
function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartSummary = document.getElementById('cart-summary');
    const emptyCart = document.getElementById('empty-cart');
    
    const totalItems = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    const subtotal = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
    // Total hanya subtotal menu
    const total = subtotal; 
    
    // Update item count
    cartCount.textContent = totalItems;
    
    if (totalItems === 0) {
        // Show empty cart
        emptyCart.classList.remove('hidden');
        cartSummary.classList.add('hidden');
        cartItemsContainer.innerHTML = '';
    } else {
        // Hide empty cart, show items and summary
        emptyCart.classList.add('hidden');
        cartSummary.classList.remove('hidden');
        
        // Generate cart items HTML
        const cartItemsHTML = Object.values(cart).map(item => {
            return `
                <div class="flex items-center space-x-3 bg-white rounded-xl shadow-md p-3 border border-gray-100 transition-all duration-200 hover:bg-gray-50">
                    ${item.image ? 
                        `<img src="${item.image}" alt="${item.name}" class="w-14 h-14 rounded-lg object-cover flex-shrink-0 shadow-sm">` :
                        `<div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-orange-500 font-bold">${item.name.substring(0, 1)}</span>
                        </div>`
                    }
                    <div class="flex-1 min-w-0">
                        <h4 class="text-base font-medium text-gray-900 truncate">${item.name}</h4>
                        <p class="text-sm text-gray-500">Rp ${item.price.toLocaleString('id-ID')}</p>
                    </div>
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <button class="decrease-btn w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 transition-colors" 
                                data-menu-id="${item.id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="w-6 text-center text-base font-medium">${item.quantity}</span>
                        <button class="increase-btn w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 transition-colors"
                                data-menu-id="${item.id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
        
        cartItemsContainer.innerHTML = cartItemsHTML;
        
        // Add event listeners to quantity buttons
        cartItemsContainer.querySelectorAll('.decrease-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const menuId = this.dataset.menuId;
                const currentQuantity = cart[menuId].quantity;
                updateCartQuantity(menuId, currentQuantity - 1);
            });
        });
        
        cartItemsContainer.querySelectorAll('.increase-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const menuId = this.dataset.menuId;
                const currentQuantity = cart[menuId].quantity;
                updateCartQuantity(menuId, currentQuantity + 1);
            });
        });
        
        // Update pricing
        document.getElementById('cart-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}
</script>

@endsection