<!-- Category Sidebar Component -->
@php
    $categories = app('Webkul\Category\Repositories\CategoryRepository')
        ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
    $maxItems = 6; // Limit to 6 categories to fit nicely in sidebar
@endphp

<div class="category-sidebar fixed left-0 top-40 bottom-20 w-12 bg-white border-r border-gray-200 shadow-lg z-40">
    <v-category-sidebar 
        :categories='@json($categories->take($maxItems)->values())'
    ></v-category-sidebar>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-category-sidebar-template">
        <div class="w-12 h-full bg-white py-3 flex flex-col border-r border-gray-200">
            
            <!-- Categories Icon Header -->
            <div class="flex justify-center pb-2 border-b border-gray-200 mb-2 px-2">
                <div class="w-8 h-8 bg-gradient-to-br from-[#e85805] to-[#d14805] rounded-lg flex items-center justify-center">
                    <i class="fas fa-th-large text-white text-sm"></i>
                </div>
            </div>

            <!-- Categories List - Icons Only with Hover Dropdowns -->
            <div class="flex-1 flex flex-col justify-between px-2 py-2">
                <div class="flex flex-col justify-between h-full">
                    <!-- Dynamic Category Icons - Distributed in remaining space -->
                    <div class="flex flex-col justify-evenly flex-1 py-2">
                        <div 
                            v-for="(category, index) in categories" 
                            :key="category.id" 
                            class="relative group"
                            @mouseenter="hoveredCategory = category.id"
                            @mouseleave="hoveredCategory = null"
                        >
                            <!-- Category Icon -->
                            <a 
                                :href="category.url"
                                class="group flex items-center justify-center p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-[#e85805] transition-colors"
                                :title="category.name"
                            >
                                <i :class="getCategoryIcon(category, index)" class="text-lg"></i>
                            </a>
                            
                            <!-- Hover Dropdown for Subcategories -->
                            <div v-if="category.children && category.children.length > 0 && hoveredCategory === category.id" 
                                 class="dropdown-menu absolute left-full top-0 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50 min-w-[200px]"
                                 @mouseenter="hoveredCategory = category.id"
                                 @mouseleave="hoveredCategory = null">
                                
                                <!-- Dropdown Header -->
                                <div class="px-3 py-2 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-orange-100">
                                    <h4 class="font-semibold text-[#e85805] text-sm" v-text="category.name"></h4>
                                </div>
                                
                                <!-- Subcategories List -->
                                <div class="py-1 max-h-64 overflow-y-auto">
                                    <a 
                                        v-for="child in category.children.slice(0, 8)" 
                                        :key="child.id"
                                        :href="child.url"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#e85805] transition-colors"
                                    >
                                        <span v-text="child.name"></span>
                                    </a>
                                    
                                    <!-- View All Link -->
                                    <a 
                                        :href="category.url"
                                        class="flex items-center px-3 py-2 text-sm text-[#e85805] hover:bg-orange-50 font-medium transition-colors"
                                    >
                                        <span>View All</span>
                                        <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- No Categories Message -->
                        <template v-if="categories.length === 0">
                            <div class="flex justify-center p-2">
                                <i class="fas fa-exclamation-circle text-gray-400 text-lg" title="No categories found"></i>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-category-sidebar', {
            template: '#v-category-sidebar-template',
            
            props: ['categories'],

            data() {
                return {
                    hoveredCategory: null
                };
            },

            mounted() {
                console.log('Loaded categories from header:', this.categories.map(c => ({ name: c.name, url: c.url })));
            },

            methods: {
                getCategoryIcon(category, index) {
                    const categoryName = category.name ? category.name.toLowerCase() : '';
                    
                    // Enhanced icon mapping with more categories
                    const iconMap = {
                        // Electronics & Technology
                        'electronics': 'fas fa-laptop',
                        'computers': 'fas fa-desktop',
                        'mobile': 'fas fa-mobile-alt',
                        'phones': 'fas fa-mobile-alt',
                        'tablets': 'fas fa-tablet-alt',
                        'gaming': 'fas fa-gamepad',
                        'cameras': 'fas fa-camera',
                        'audio': 'fas fa-headphones',
                        'headphones': 'fas fa-headphones',
                        'speakers': 'fas fa-volume-up',
                        'tech': 'fas fa-laptop',
                        'digital': 'fas fa-laptop',
                        'desktop': 'fas fa-tv',          // Added desktop -> TV mapping
                        'monitor': 'fas fa-tv',          // Added monitor -> TV mapping
                        'screen': 'fas fa-tv',           // Added screen -> TV mapping
                        'display': 'fas fa-tv',          // Added display -> TV mapping
                        'printer': 'fas fa-print',       // Added printer mapping
                        'printers': 'fas fa-print',      // Added printers mapping
                        'printing': 'fas fa-print',      // Added printing mapping
                        
                        // Fashion & Apparel
                        'fashion': 'fas fa-tshirt',
                        'clothing': 'fas fa-tshirt',
                        'apparel': 'fas fa-tshirt',
                        'shoes': 'fas fa-shoe-prints',
                        'footwear': 'fas fa-shoe-prints',
                        'accessories': 'fas fa-ring',
                        'jewelry': 'fas fa-gem',
                        'watches': 'fas fa-clock',
                        'bags': 'fas fa-shopping-bag',
                        'handbags': 'fas fa-shopping-bag',
                        'wear': 'fas fa-tshirt',
                        'dress': 'fas fa-tshirt',
                        
                        // Home & Living
                        'home': 'fas fa-home',
                        'furniture': 'fas fa-couch',
                        'decor': 'fas fa-palette',
                        'kitchen': 'fas fa-utensils',
                        'appliances': 'fas fa-blender',
                        'garden': 'fas fa-leaf',
                        'tools': 'fas fa-tools',
                        'lighting': 'fas fa-lightbulb',
                        'house': 'fas fa-home',
                        'living': 'fas fa-home',
                        
                        // Health & Beauty
                        'health': 'fas fa-heartbeat',
                        'beauty': 'fas fa-spa',
                        'cosmetics': 'fas fa-paint-brush',
                        'skincare': 'fas fa-hand-sparkles',
                        'personal': 'fas fa-user',
                        'wellness': 'fas fa-leaf',
                        'fitness': 'fas fa-dumbbell',
                        'care': 'fas fa-heart',
                        
                        // Sports & Recreation
                        'sports': 'fas fa-running',
                        'outdoor': 'fas fa-mountain',
                        'recreation': 'fas fa-baseball-ball',
                        'travel': 'fas fa-suitcase',
                        'camping': 'fas fa-campground',
                        'cycling': 'fas fa-bicycle',
                        'gym': 'fas fa-dumbbell',
                        'exercise': 'fas fa-dumbbell',
                        
                        // Books & Media
                        'books': 'fas fa-book',
                        'media': 'fas fa-compact-disc',
                        'movies': 'fas fa-film',
                        'music': 'fas fa-music',
                        'education': 'fas fa-graduation-cap',
                        'literature': 'fas fa-book',
                        'magazine': 'fas fa-book',
                        
                        // Food & Beverages
                        'food': 'fas fa-utensils',
                        'beverages': 'fas fa-coffee',
                        'grocery': 'fas fa-shopping-cart',
                        'snacks': 'fas fa-cookie-bite',
                        'restaurant': 'fas fa-utensils',
                        'drink': 'fas fa-coffee',
                        
                        // Automotive
                        'automotive': 'fas fa-car',
                        'auto': 'fas fa-car',
                        'parts': 'fas fa-cog',
                        'car': 'fas fa-car',
                        'vehicle': 'fas fa-car',
                        'motor': 'fas fa-car',
                        
                        // Baby & Kids
                        'baby': 'fas fa-baby',
                        'kids': 'fas fa-child',
                        'toys': 'fas fa-cube',
                        'children': 'fas fa-child',
                        'child': 'fas fa-child',
                        'toy': 'fas fa-cube',
                        
                        // Office & Business
                        'office': 'fas fa-briefcase',
                        'business': 'fas fa-building',
                        'stationery': 'fas fa-pen',
                        'work': 'fas fa-briefcase',
                        'professional': 'fas fa-briefcase'
                    };
                    
                    // Try to find matching icon
                    for (const [keyword, icon] of Object.entries(iconMap)) {
                        if (categoryName.includes(keyword)) {
                            return icon;
                        }
                    }
                    
                    // Default icons based on position if no match found
                    const defaultIcons = [
                        'fas fa-laptop',      // Electronics
                        'fas fa-tv',          // Replaced tshirt with TV
                        'fas fa-home',        // Home
                        'fas fa-print',       // Replaced book with printer
                        'fas fa-dumbbell',    // Sports
                        'fas fa-heart'        // Health
                    ];
                    
                    return defaultIcons[index] || 'fas fa-th-large';
                }
            }
        });
    </script>
@endPushOnce

@pushOnce('styles')
    <style>
        .category-sidebar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .category-sidebar .flex-1 {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }

        .category-sidebar .flex-1::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        /* Force FontAwesome icons to show - highest priority */
        .category-sidebar i[class*="fa"] {
            display: inline-block !important;
            font-family: "Font Awesome 5 Free", "Font Awesome 5 Pro", "FontAwesome" !important;
            font-weight: 900 !important;
            font-style: normal !important;
            line-height: 1 !important;
            speak: none !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
        }

        /* Dropdown hover effects */
        .dropdown-menu {
            pointer-events: auto;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
<script
    type="text/x-template"
    id="v-category-sidebar-template"
>
    <!-- Compact Icon-Only Sidebar with Hover Dropdowns -->
    <div class="w-12 h-full bg-white py-3 flex flex-col border-r border-gray-200">
        
        <!-- Categories Icon Header -->
        <div class="flex justify-center pb-2 border-b border-gray-200 mb-2 px-2">
            <div class="w-8 h-8 bg-gradient-to-br from-[#e85805] to-[#d14805] rounded-lg flex items-center justify-center">
                <!-- Test both FontAwesome and HTML entity -->
                <i class="fas fa-bars text-white text-sm"></i>
                <span style="display:none;" class="text-white text-xs">≡</span>
            </div>
        </div>

        <!-- Categories List - Icons Only with Hover Dropdowns -->
        <div class="flex-1 flex flex-col justify-between px-2 py-2">
            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center py-4">
                <div class="h-4 w-4 animate-spin rounded-full border-2 border-[#e85805] border-t-transparent"></div>
            </div>
            
            <div v-else class="flex flex-col justify-between h-full">
                <!-- All Products Icon - Always at top -->
                <div class="flex flex-col space-y-2">
                    <div class="relative group">
                        <a 
                            href="{{ route('shop.home.index') }}"
                            class="group flex items-center justify-center p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-[#e85805] transition-colors"
                            title="All Products"
                        >
                            <i class="fas fa-home text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Dynamic Category Icons - Distributed in remaining space -->
                <div class="flex flex-col justify-evenly flex-1 py-2">
                    <div 
                        v-for="category in mainCategories" 
                        :key="category.id" 
                        class="relative group"
                    >
                        <!-- Category Icon -->
                        <a 
                            :href="getCategoryUrl(category)"
                            class="group flex items-center justify-center p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-[#e85805] transition-colors"
                            :title="category.name"
                            :class="{ 'bg-orange-50 text-[#e85805]': isActive(category) }"
                        >
                            <!-- Dynamic FontAwesome category icons based on category name -->
                            <i :class="getCategoryIcon(category)" class="text-lg"></i>
                            
                            <!-- Active indicator dot -->
                            <span v-if="isActive(category)" 
                                  class="absolute -top-1 -right-1 w-2 h-2 bg-[#e85805] rounded-full">
                            </span>
                        </a>
                        
                        <!-- Hover Dropdown for Subcategories -->
                        <div v-if="category.children && category.children.length > 0" 
                             class="dropdown-menu absolute left-full top-0 ml-1 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50 transform translate-x-0 opacity-0 scale-95 transition-all duration-200 ease-out group-hover:opacity-100 group-hover:scale-100 min-w-[200px]"
                             style="pointer-events: none;"
                             @mouseenter="$el.style.pointerEvents = 'auto'"
                             @mouseleave="$el.style.pointerEvents = 'none'">
                            
                            <!-- Dropdown Header -->
                            <div class="px-3 py-2 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-orange-100">
                                <h4 class="font-semibold text-[#e85805] text-sm" v-text="category.name"></h4>
                            </div>
                            
                            <!-- Subcategories List -->
                            <div class="py-1 max-h-64 overflow-y-auto">
                                <a 
                                    v-for="child in category.children" 
                                    :key="child.id"
                                    :href="getCategoryUrl(child)"
                                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#e85805] transition-colors"
                                >
                                    <span v-text="child.name"></span>
                                </a>
                                
                                <!-- View All Link -->
                                <a 
                                    :href="getCategoryUrl(category)"
                                    class="flex items-center px-3 py-2 text-sm text-[#e85805] hover:bg-orange-50 font-medium transition-colors"
                                >
                                    <span>View All</span>
                                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script>
    app.component('v-category-sidebar', {
        template: '#v-category-sidebar-template',

        data() {
            return {
                isLoading: false,
                categories: [],
                mainCategories: [], // Only top-level categories for sidebar
            }
        },

        mounted() {
            this.getCategories();
        },

        methods: {
            getCategories() {
                this.isLoading = true;

                this.$axios.get("{{ route('shop.api.categories.tree') }}")
                    .then(response => {
                        this.categories = response.data.data;
                        
                        // Filter only main categories (parent categories) for sidebar
                        // Limit to first 8 main categories to keep sidebar compact
                        this.mainCategories = this.categories
                            .filter(category => category.parent_id === null || !category.parent_id)
                            .slice(0, 8);
                        
                        // Process categories to ensure they have proper URLs
                        this.mainCategories = this.mainCategories.map(category => {
                            // Construct proper category URL if not available
                            if (!category.url || category.url === '#') {
                                category.url = this.buildCategoryUrl(category);
                            }
                            
                            // Process children URLs too
                            if (category.children && category.children.length > 0) {
                                category.children = category.children.map(child => {
                                    if (!child.url || child.url === '#') {
                                        child.url = this.buildCategoryUrl(child);
                                    }
                                    return child;
                                });
                            }
                            
                            return category;
                        });
                            
                        this.isLoading = false;
                        
                        console.log('Loaded main categories:', this.mainCategories.map(c => ({
                            name: c.name, 
                            slug: c.slug, 
                            url: c.url,
                            id: c.id
                        })));
                    })
                    .catch(error => {
                        console.error('Error loading categories:', error);
                        this.isLoading = false;
                    });
            },

            buildCategoryUrl(category) {
                // Build category URL based on available data
                const baseUrl = window.location.origin;
                
                if (category.slug) {
                    // Try different URL patterns that might work with Bagisto
                    return `${baseUrl}/categories/${category.slug}`;
                } else if (category.id) {
                    return `{{ route('shop.home.index') }}?category_id=${category.id}`;
                }
                
                return '{{ route('shop.home.index') }}';
            },

            isActive(category) {
                const params = new URLSearchParams(window.location.search);
                return params.get('category') === category.slug;
            },

            getCategoryIcon(category) {
                const name = category.name.toLowerCase();
                const slug = category.slug ? category.slug.toLowerCase() : '';
                const description = category.description ? category.description.toLowerCase() : '';
                
                // Combine all text for better matching
                const searchText = `${name} ${slug} ${description}`;
                
                // Electronics & Technology
                if (this.matchesAny(searchText, ['electronic', 'tech', 'computer', 'mobile', 'phone', 'gadget', 'laptop', 'tablet', 'smart', 'digital', 'device'])) {
                    return 'fas fa-laptop';
                }
                // Clothing & Fashion
                else if (this.matchesAny(searchText, ['cloth', 'fashion', 'apparel', 'wear', 'dress', 'shirt', 'pant', 'jean', 'style', 'outfit', 'garment'])) {
                    return 'fas fa-tshirt';
                }
                // Books & Education
                else if (this.matchesAny(searchText, ['book', 'education', 'learn', 'study', 'literature', 'novel', 'magazine', 'academic', 'school'])) {
                    return 'fas fa-book';
                }
                // Food & Beverages
                else if (this.matchesAny(searchText, ['food', 'drink', 'beverage', 'restaurant', 'cafe', 'grocery', 'snack', 'meal', 'kitchen', 'cooking'])) {
                    return 'fas fa-utensils';
                }
                // Sports & Fitness
                else if (this.matchesAny(searchText, ['sport', 'fitness', 'gym', 'exercise', 'outdoor', 'athletic', 'game', 'ball', 'run', 'workout'])) {
                    return 'fas fa-dumbbell';
                }
                // Health & Beauty
                else if (this.matchesAny(searchText, ['health', 'beauty', 'cosmetic', 'care', 'medical', 'wellness', 'skincare', 'makeup', 'personal'])) {
                    return 'fas fa-heart';
                }
                // Home & Garden
                else if (this.matchesAny(searchText, ['home', 'garden', 'furniture', 'decor', 'house', 'living', 'kitchen', 'bedroom', 'bathroom'])) {
                    return 'fas fa-home';
                }
                // Automotive
                else if (this.matchesAny(searchText, ['car', 'auto', 'vehicle', 'motor', 'bike', 'motorcycle', 'truck', 'transport', 'wheel'])) {
                    return 'fas fa-car';
                }
                // Toys & Games
                else if (this.matchesAny(searchText, ['toy', 'game', 'play', 'kid', 'children', 'baby', 'child', 'fun', 'entertainment'])) {
                    return 'fas fa-gamepad';
                }
                // Jewelry & Accessories
                else if (this.matchesAny(searchText, ['jewelry', 'jewellery', 'watch', 'accessory', 'ring', 'necklace', 'bracelet', 'earring'])) {
                    return 'fas fa-gem';
                }
                // Music & Entertainment
                else if (this.matchesAny(searchText, ['music', 'entertainment', 'audio', 'sound', 'instrument', 'headphone', 'speaker', 'media'])) {
                    return 'fas fa-music';
                }
                // Travel & Luggage
                else if (this.matchesAny(searchText, ['travel', 'luggage', 'bag', 'vacation', 'trip', 'suitcase', 'backpack', 'journey'])) {
                    return 'fas fa-suitcase';
                }
                // Pet Supplies
                else if (this.matchesAny(searchText, ['pet', 'animal', 'dog', 'cat', 'bird', 'fish', 'puppy', 'kitten'])) {
                    return 'fas fa-paw';
                }
                // Office & Business
                else if (this.matchesAny(searchText, ['office', 'business', 'work', 'professional', 'stationery', 'desk', 'corporate'])) {
                    return 'fas fa-briefcase';
                }
                // Tools & Hardware
                else if (this.matchesAny(searchText, ['tool', 'hardware', 'repair', 'construction', 'build', 'diy', 'equipment'])) {
                    return 'fas fa-tools';
                }
                // Art & Craft
                else if (this.matchesAny(searchText, ['art', 'craft', 'paint', 'draw', 'creative', 'design', 'handmade'])) {
                    return 'fas fa-palette';
                }
                // Default category icon
                else {
                    return 'fas fa-th-large';
                }
            },

            matchesAny(text, keywords) {
                return keywords.some(keyword => text.includes(keyword));
            },

            getCategoryUrl(category) {
                // First try to use the category URL if available
                if (category.url && category.url !== '#' && category.url.length > 1) {
                    return category.url;
                }
                
                // Fallback to constructing URL with slug
                if (category.slug) {
                    // Try using Laravel route helper if available
                    try {
                        return `{{ route('shop.search.index') }}?category=${encodeURIComponent(category.slug)}`;
                    } catch (e) {
                        // Fallback to manual URL construction
                        return `/categories/${encodeURIComponent(category.slug)}`;
                    }
                }
                
                // Final fallback to home with category filter
                return `{{ route('shop.home.index') }}${category.id ? '?category_id=' + category.id : ''}`;
            },

            getCategoryEmoji(category) {
                const name = category.name.toLowerCase();
                
                // Electronics & Technology
                if (name.includes('electronic') || name.includes('tech') || name.includes('computer') || name.includes('mobile') || name.includes('gadget')) {
                    return '💻';
                }
                // Clothing & Fashion
                else if (name.includes('cloth') || name.includes('fashion') || name.includes('apparel') || name.includes('wear') || name.includes('dress')) {
                    return '👕';
                }
                // Books & Education
                else if (name.includes('book') || name.includes('education') || name.includes('learn') || name.includes('study')) {
                    return '📚';
                }
                // Food & Beverages
                else if (name.includes('food') || name.includes('drink') || name.includes('beverage') || name.includes('restaurant') || name.includes('cafe')) {
                    return '🍽️';
                }
                // Sports & Fitness
                else if (name.includes('sport') || name.includes('fitness') || name.includes('gym') || name.includes('exercise') || name.includes('outdoor')) {
                    return '🏋️';
                }
                // Health & Beauty
                else if (name.includes('health') || name.includes('beauty') || name.includes('cosmetic') || name.includes('care') || name.includes('medical')) {
                    return '💄';
                }
                // Home & Garden
                else if (name.includes('home') || name.includes('garden') || name.includes('furniture') || name.includes('decor') || name.includes('house')) {
                    return '🏠';
                }
                // Automotive
                else if (name.includes('car') || name.includes('auto') || name.includes('vehicle') || name.includes('motor') || name.includes('bike')) {
                    return '🚗';
                }
                // Toys & Games
                else if (name.includes('toy') || name.includes('game') || name.includes('play') || name.includes('kid') || name.includes('children')) {
                    return '🎮';
                }
                // Jewelry & Accessories
                else if (name.includes('jewelry') || name.includes('jewellery') || name.includes('watch') || name.includes('accessory') || name.includes('ring')) {
                    return '💎';
                }
                // Music & Entertainment
                else if (name.includes('music') || name.includes('entertainment') || name.includes('audio') || name.includes('sound') || name.includes('instrument')) {
                    return '🎵';
                }
                // Travel & Luggage
                else if (name.includes('travel') || name.includes('luggage') || name.includes('bag') || name.includes('vacation') || name.includes('trip')) {
                    return '🧳';
                }
                // Pet Supplies
                else if (name.includes('pet') || name.includes('animal') || name.includes('dog') || name.includes('cat') || name.includes('bird')) {
                    return '🐾';
                }
                // Office & Business
                else if (name.includes('office') || name.includes('business') || name.includes('work') || name.includes('professional') || name.includes('stationery')) {
                    return '💼';
                }
                // Default category icon
                else {
                    return '📦';
                }
            }
        }
    });
</script>
@endPushOnce