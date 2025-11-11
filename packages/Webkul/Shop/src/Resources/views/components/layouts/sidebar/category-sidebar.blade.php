<!-- Enhanced Category Sidebar Component -->

<!-- Content Overlay for Sidebar Focus Effect -->
<div id="sidebar-content-overlay" class="hidden md:block fixed inset-0 bg-black opacity-0 pointer-events-none transition-opacity duration-300" style="z-index: 25;"></div>

<!-- Mobile Category Toggle Button (Top Bar) -->
<div class="category-mobile-toggle md:hidden fixed top-20 left-0 right-0 bg-white border-b border-gray-200 z-50 px-4 py-3">
    <button 
        id="mobile-category-toggle-btn"
        class="flex items-center gap-3 w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 font-semibold"
    >
        <i class="fas fa-bars text-lg"></i>
        <span>All Categories</span>
        <i class="fas fa-chevron-right ml-auto transition-transform duration-300" id="mobile-chevron"></i>
    </button>
</div>

<!-- Desktop Sidebar + Mobile Overlay -->
<div id="category-sidebar-wrapper">
    <!-- Desktop Sidebar (Always Visible) -->
    <div class="category-sidebar hidden md:block fixed left-0 top-0 bottom-0 w-16 bg-white border-r border-gray-200 shadow-lg transition-all duration-300 hover:w-80 group" style="z-index: 9999 !important;">
        <v-category-sidebar></v-category-sidebar>
        
        <!-- Fallback content (visible if Vue component fails) -->
        <div class="fallback-sidebar w-16 group-hover:w-80 h-full bg-white py-2 flex flex-col border-r border-gray-200 transition-all duration-300 overflow-hidden">
            <!-- Categories Header - Reduced top margin -->
            <div class="flex items-center justify-center group-hover:justify-start group-hover:px-6 pb-2 border-b border-gray-200 mb-2 transition-all duration-300 mt-2">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-th-large text-white text-sm"></i>
                </div>
                <span class="ml-3 text-sm font-bold text-gray-900 opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                    All Categories
                </span>
            </div>

            <!-- Categories List - Reduced spacing -->
            <div class="flex-1 overflow-y-auto px-2 group-hover:px-4 transition-all duration-300">
                <div class="space-y-0.5">
                    <!-- Database Categories -->
                    <div class="relative">
                        <a href="/categories/laptops" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-laptop text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Laptops
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/laptop-parts" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-cog text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Laptop Parts
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/laptop-accessories" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-shopping-bag text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Laptop Accessories
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/computer-components" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-microchip text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Computer Components
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/monitors-displays" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-tv text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Monitors & Displays
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/networking" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-wifi text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Networking
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/printers-scanners" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-print text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Printers & Scanners
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/peripherals" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-mouse text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Peripherals
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/software" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-code text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Software
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="relative">
                        <a href="/categories/mobile" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                    <i class="fas fa-mobile-alt text-lg text-center" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    Mobile
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Overlay Sidebar -->
    <div 
        id="mobile-category-overlay" 
        class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none transition-opacity duration-300"
    ></div>
    
    <div 
        id="mobile-category-sidebar" 
        class="md:hidden fixed top-0 left-0 h-full w-80 bg-white shadow-xl z-50 transform -translate-x-full transition-transform duration-300"
    >
        <!-- Mobile Sidebar Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-orange-100">
            <h2 class="text-lg font-bold text-orange-900">All Categories</h2>
            <button 
                id="mobile-sidebar-close-btn"
                class="p-2 rounded-full hover:bg-orange-200 transition-colors"
            >
                <i class="fas fa-times text-orange-600 text-lg"></i>
            </button>
        </div>
        
        <!-- Mobile Sidebar Content -->
        <div class="h-full overflow-y-auto pb-20">
            <v-mobile-category-sidebar></v-mobile-category-sidebar>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <!-- Desktop Category Sidebar Template -->
    <script type="text/x-template" id="v-category-sidebar-template">
        <div class="w-16 group-hover:w-80 h-full bg-white py-2 flex flex-col border-r border-gray-200 transition-all duration-300 overflow-hidden">
            
            <!-- Categories Header - Moved to top -->
            <div class="flex items-center justify-center group-hover:justify-start group-hover:px-6 pb-2 border-b border-gray-200 mb-2 transition-all duration-300 mt-2">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-th-large text-white text-sm"></i>
                </div>
                <span class="ml-3 text-sm font-bold text-gray-900 opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                    All Categories
                </span>
            </div>

            <!-- Categories List - Reduced spacing -->
            <div class="flex-1 overflow-y-auto px-2 group-hover:px-4 transition-all duration-300">
                <!-- Loading State -->
                <div v-if="isLoading" class="flex justify-center py-4">
                    <div class="flex flex-col items-center space-y-2">
                        <div class="h-6 w-6 animate-spin rounded-full border-2 border-orange-500 border-t-transparent"></div>
                        <span class="text-xs text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">Loading...</span>
                    </div>
                </div>

                <!-- Categories - Reduced spacing -->
                <div v-else class="space-y-0.5">
                    <div 
                        v-for="(category, index) in mainCategories" 
                        :key="category.id"
                        class="relative"
                    >
                        <!-- Main Category - Reduced padding -->
                        <div 
                            class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200"
                            :class="[
                                category.isActive ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-gray-50',
                                category.subcategories.length > 0 ? 'hover:bg-orange-50' : 'hover:bg-gray-50'
                            ]"
                            @click="toggleCategory(index)"
                        >
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0">
                                    <i :class="category.icon" class="text-lg" :class="category.isActive ? 'text-orange-600' : ''"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap" v-text="category.name">
                                </span>
                            </div>
                            <div 
                                v-if="category.subcategories.length > 0" 
                                class="opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            >
                                <i 
                                    class="fas fa-chevron-down text-xs transition-transform duration-200" 
                                    :class="{ 'transform rotate-180': category.expanded }"
                                ></i>
                            </div>
                        </div>

                        <!-- Subcategories (Desktop - Expanded) -->
                        <div 
                            v-if="category.subcategories.length > 0 && category.expanded" 
                            class="ml-8 opacity-0 group-hover:opacity-100 transition-opacity duration-300 space-y-1 mt-1"
                        >
                            <a 
                                v-for="(subcategory, subIndex) in category.subcategories" 
                                :key="subIndex"
                                :href="subcategory.url"
                                class="block px-4 py-2 text-xs rounded-lg transition-colors duration-200"
                                :class="subcategory.isActive ? 'bg-orange-50 text-orange-600 font-medium' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-25'"
                                v-text="subcategory.name"
                            >
                            </a>
                        </div>

                        <!-- Desktop Hover Dropdown (when collapsed) -->
                        <div 
                            v-if="category.subcategories.length > 0 && !category.expanded" 
                            class="absolute left-full top-0 ml-2 w-72 bg-white border border-gray-200 rounded-lg shadow-xl py-3 z-50 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 transform translate-x-2 group-hover:translate-x-0"
                        >
                            <!-- Dropdown Header -->
                            <div class="px-4 py-2 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-orange-100">
                                <h4 class="font-semibold text-orange-900 text-sm flex items-center">
                                    <i :class="category.icon" class="text-orange-600 mr-2"></i>
                                    <span v-text="category.name"></span>
                                </h4>
                            </div>
                            
                            <!-- Subcategories Grid -->
                            <div class="py-2 max-h-80 overflow-y-auto">
                                <div class="grid grid-cols-1 gap-1 px-2">
                                    <a 
                                        v-for="(subcategory, subIndex) in category.subcategories" 
                                        :key="subIndex"
                                        :href="subcategory.url"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200"
                                        :class="subcategory.isActive ? 'bg-orange-100 text-orange-700 font-medium' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600'"
                                    >
                                        <i class="fas fa-angle-right text-xs text-gray-400 mr-2"></i>
                                        <span v-text="subcategory.name"></span>
                                    </a>
                                </div>
                                
                                <!-- View All Link -->
                                <div class="border-t border-gray-100 mt-2 pt-2 px-2">
                                    <a 
                                        :href="category.url"
                                        class="flex items-center justify-center w-full px-3 py-2 text-sm text-orange-600 hover:bg-orange-50 font-medium rounded-lg transition-colors"
                                    >
                                        <span>View All <span v-text="category.name"></span></span>
                                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <!-- Mobile Category Sidebar Template -->
    <script type="text/x-template" id="v-mobile-category-sidebar-template">
        <div class="py-4">
            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center py-8">
                <div class="flex flex-col items-center space-y-2">
                    <div class="h-6 w-6 animate-spin rounded-full border-2 border-orange-500 border-t-transparent"></div>
                    <span class="text-sm text-gray-500">Loading categories...</span>
                </div>
            </div>

            <!-- Categories -->
            <div v-else class="space-y-2">
                <div 
                    v-for="(category, index) in mainCategories" 
                    :key="category.id"
                    class="border-b border-gray-100 last:border-b-0"
                >
                    <!-- Main Category -->
                    <div 
                        class="flex items-center justify-between p-4 cursor-pointer transition-all duration-200"
                        :class="category.isActive ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
                        @click="toggleCategory(index)"
                    >
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                                <i :class="category.icon" class="text-white text-sm"></i>
                            </div>
                            <span class="ml-4 font-medium" v-text="category.name"></span>
                        </div>
                        <div v-if="category.subcategories.length > 0">
                            <i 
                                class="fas fa-chevron-down text-sm transition-transform duration-200" 
                                :class="{ 'transform rotate-180': category.expanded }"
                            ></i>
                        </div>
                    </div>

                    <!-- Subcategories (Mobile - Accordion) -->
                    <div 
                        v-if="category.subcategories.length > 0 && category.expanded" 
                        class="bg-gray-50 pb-2"
                    >
                        <a 
                            v-for="(subcategory, subIndex) in category.subcategories" 
                            :key="subIndex"
                            :href="subcategory.url"
                            class="flex items-center px-8 py-3 text-sm transition-colors duration-200"
                            :class="subcategory.isActive ? 'bg-orange-50 text-orange-600 font-medium' : 'text-gray-600 hover:text-orange-600 hover:bg-white'"
                            @click="closeMobileSidebar()"
                        >
                            <i class="fas fa-angle-right text-xs text-gray-400 mr-3"></i>
                            <span v-text="subcategory.name"></span>
                        </a>
                        
                        <!-- View All Link -->
                        <a 
                            :href="category.url"
                            class="flex items-center justify-center mx-6 mt-2 px-4 py-2 text-sm text-orange-600 bg-white border border-orange-200 rounded-lg font-medium transition-colors hover:bg-orange-50"
                            @click="closeMobileSidebar()"
                        >
                            <span>View All <span v-text="category.name"></span></span>
                            <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        // Safe category sidebar initialization that won't conflict with main Vue app
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Category sidebar: DOM loaded, initializing...');
            
            // Check if FontAwesome is loaded
            setTimeout(() => {
                const testIcon = document.querySelector('.fas');
                if (testIcon && window.getComputedStyle(testIcon).fontFamily.includes('Font Awesome')) {
                    console.log('Category sidebar: FontAwesome loaded successfully');
                } else {
                    console.log('Category sidebar: FontAwesome may not be loaded');
                }
            }, 1000);
            
            // Mobile sidebar toggle functionality
            function initMobileSidebar() {
                const toggleBtn = document.getElementById('mobile-category-toggle-btn');
                const closeBtn = document.getElementById('mobile-sidebar-close-btn');
                const overlay = document.getElementById('mobile-category-overlay');
                const sidebar = document.getElementById('mobile-category-sidebar');
                const chevron = document.getElementById('mobile-chevron');

                function toggleSidebar() {
                    if (!overlay || !sidebar || !chevron) return;
                    
                    if (overlay.classList.contains('opacity-0')) {
                        // Open
                        overlay.classList.remove('opacity-0', 'pointer-events-none');
                        overlay.classList.add('opacity-100');
                        sidebar.classList.remove('-translate-x-full');
                        chevron.classList.add('rotate-180');
                        document.body.style.overflow = 'hidden';
                    } else {
                        // Close
                        overlay.classList.add('opacity-0', 'pointer-events-none');
                        overlay.classList.remove('opacity-100');
                        sidebar.classList.add('-translate-x-full');
                        chevron.classList.remove('rotate-180');
                        document.body.style.overflow = 'auto';
                    }
                }

                // Add event listeners safely
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', toggleSidebar);
                }
                if (closeBtn) {
                    closeBtn.addEventListener('click', toggleSidebar);
                }
                if (overlay) {
                    overlay.addEventListener('click', toggleSidebar);
                }
            }

            // Initialize mobile sidebar
            initMobileSidebar();
            
            // Initialize sidebar hover overlay effect
            initSidebarOverlay();
        });

        // Sidebar overlay effect for desktop
        function initSidebarOverlay() {
            const sidebar = document.querySelector('.category-sidebar');
            const overlay = document.getElementById('sidebar-content-overlay');
            
            if (!sidebar || !overlay) {
                console.log('Sidebar overlay: Elements not found', { sidebar: !!sidebar, overlay: !!overlay });
                return;
            }
            
            console.log('Sidebar overlay: Initialized successfully');
            
            // Show overlay when sidebar is hovered
            sidebar.addEventListener('mouseenter', function() {
                console.log('Sidebar overlay: Mouse enter - showing overlay');
                overlay.style.opacity = '0.4';
                overlay.style.pointerEvents = 'auto';
            });
            
            // Hide overlay when sidebar hover ends
            sidebar.addEventListener('mouseleave', function() {
                console.log('Sidebar overlay: Mouse leave - hiding overlay');
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
            });
            
            // Also hide overlay if user clicks on it
            overlay.addEventListener('click', function() {
                console.log('Sidebar overlay: Click dismiss - hiding overlay');
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
            });
        }

        // Wait for Vue app to be ready before registering components
        window.addEventListener('load', function() {
            console.log('Category sidebar: Window loaded, checking for Vue app...');
            
            // Check if Vue app exists
            if (typeof app !== 'undefined') {
                console.log('Category sidebar: Vue app found, registering components...');
                
                // Desktop Category Sidebar Component
                app.component('v-category-sidebar', {
                    template: '#v-category-sidebar-template',

                    data() {
                        console.log('Category sidebar: Desktop component data initialized');
                        return {
                            isLoading: true,
                            categories: [],
                            mainCategories: []
                        };
                    },

                    mounted() {
                        console.log('Category sidebar: Desktop component mounted');
                        this.fetchCategories();
                    },

                    methods: {
                        fetchCategories() {
                            this.isLoading = true;
                            console.log('Category sidebar: Fetching categories from API...');

                            // Try to use axios if available, otherwise use fetch
                            const makeRequest = () => {
                                if (this.$axios) {
                                    return this.$axios.get("{{ route('shop.api.categories.tree') }}")
                                        .then(response => response.data);
                                } else {
                                    return fetch("{{ route('shop.api.categories.tree') }}")
                                        .then(response => response.json());
                                }
                            };

                            makeRequest()
                                .then(data => {
                                    console.log('Category sidebar: Categories fetched successfully', data);
                                    this.categories = data.data || data;
                                    this.processCategories();
                                    this.setActiveCategories();
                                    this.isLoading = false;
                                })
                                .catch(error => {
                                    console.log('Category sidebar: Error fetching categories, using fallback', error);
                                    this.useFallbackCategories();
                                    this.isLoading = false;
                                });
                        },

                        processCategories() {
                            // Filter only main categories (parent categories) and limit to 8 for clean display
                            this.mainCategories = this.categories
                                .filter(category => !category.parent_id || category.parent_id === null)
                                .slice(0, 8)
                                .map(category => {
                                    return {
                                        ...category,
                                        isActive: false,
                                        expanded: false,
                                        icon: this.getCategoryIcon(category),
                                        url: this.getCategoryUrl(category),
                                        subcategories: (category.children || []).map(child => ({
                                            ...child,
                                            isActive: false,
                                            url: this.getCategoryUrl(child)
                                        }))
                                    };
                                });
                            
                            console.log('Category sidebar: Processed categories', this.mainCategories);
                        },

                        getCategoryIcon(category) {
                            const name = (category.name || '').toLowerCase();
                            const slug = (category.slug || '').toLowerCase();
                            const description = (category.description || '').toLowerCase();
                            
                            // Combine all text for better matching
                            const searchText = `${name} ${slug} ${description}`;
                            
                            // Exact category matches for your database categories
                            if (name === 'laptops' || this.matchesAny(searchText, ['laptop', 'notebook'])) {
                                return 'fas fa-laptop';
                            }
                            // Laptop Parts
                            else if (name === 'laptop parts' || this.matchesAny(searchText, ['laptop parts', 'laptop part', 'parts'])) {
                                return 'fas fa-cog';
                            }
                            // Laptop Accessories
                            else if (name === 'laptop accessories' || this.matchesAny(searchText, ['laptop accessories', 'laptop accessory'])) {
                                return 'fas fa-shopping-bag';
                            }
                            // Computer Components
                            else if (name === 'computer components' || this.matchesAny(searchText, ['computer components', 'component', 'hardware', 'ssd', 'hdd', 'ram', 'memory', 'motherboard', 'graphics', 'gpu', 'cpu', 'processor'])) {
                                return 'fas fa-microchip';
                            }
                            // Monitors & Displays
                            else if (name === 'monitors & displays' || this.matchesAny(searchText, ['monitor', 'display', 'screen', 'tv', 'led', 'lcd'])) {
                                return 'fas fa-tv';
                            }
                            // Networking
                            else if (name === 'networking' || this.matchesAny(searchText, ['network', 'router', 'wifi', 'ethernet', 'cable', 'internet', 'modem'])) {
                                return 'fas fa-wifi';
                            }
                            // Printers & Scanners
                            else if (name === 'printers & scanners' || this.matchesAny(searchText, ['printer', 'scanner', 'print', 'scan', 'laser', 'ink'])) {
                                return 'fas fa-print';
                            }
                            // Peripherals
                            else if (name === 'peripherals' || this.matchesAny(searchText, ['peripheral', 'mouse', 'keyboard', 'webcam', 'speaker', 'headphone', 'microphone', 'usb', 'hub'])) {
                                return 'fas fa-mouse';
                            }
                            // Software
                            else if (name === 'software' || this.matchesAny(searchText, ['software', 'program', 'app', 'operating', 'system', 'antivirus', 'office'])) {
                                return 'fas fa-code';
                            }
                            // Mobile
                            else if (name === 'mobile' || this.matchesAny(searchText, ['mobile', 'phone', 'smartphone', 'cell', 'tablet'])) {
                                return 'fas fa-mobile-alt';
                            }
                            // Default category icon for any other categories
                            else {
                                return 'fas fa-th-large';
                            }
                        },

                        matchesAny(text, keywords) {
                            return keywords.some(keyword => text.includes(keyword));
                        },

                        getCategoryUrl(category) {
                            // Use Bagisto's category URL structure
                            if (category.url && category.url !== '#' && category.url.length > 1) {
                                return category.url;
                            }
                            
                            // Build URL from slug
                            if (category.slug) {
                                return `{{ url('/') }}/${category.slug}`;
                            }
                            
                            // Fallback to category ID
                            return `{{ route('shop.search.index') }}?category_id=${category.id}`;
                        },

                        toggleCategory(index) {
                            if (this.mainCategories[index]) {
                                this.mainCategories[index].expanded = !this.mainCategories[index].expanded;
                            }
                        },

                        setActiveCategories() {
                            try {
                                const currentPath = window.location.pathname;
                                
                                this.mainCategories.forEach(category => {
                                    // Check if main category is active
                                    category.isActive = currentPath.includes(category.slug) || 
                                                      currentPath.includes(`category_id=${category.id}`);
                                    
                                    // Check subcategories
                                    category.subcategories.forEach(subcategory => {
                                        subcategory.isActive = currentPath.includes(subcategory.slug) || 
                                                              currentPath.includes(`category_id=${subcategory.id}`);
                                        if (subcategory.isActive) {
                                            category.expanded = true; // Auto-expand if subcategory is active
                                        }
                                    });
                                });
                            } catch (error) {
                                console.log('Error setting active categories:', error);
                            }
                        },

                        useFallbackCategories() {
                            // Fallback categories in case API fails
                            this.mainCategories = [
                                {
                                    id: 'fallback-1',
                                    name: 'Electronics',
                                    icon: 'fas fa-laptop',
                                    url: '/categories/electronics',
                                    isActive: false,
                                    expanded: false,
                                    subcategories: []
                                },
                                {
                                    id: 'fallback-2',
                                    name: 'Accessories',
                                    icon: 'fas fa-shopping-bag',
                                    url: '/categories/accessories',
                                    isActive: false,
                                    expanded: false,
                                    subcategories: []
                                }
                            ];
                        }
                    }
                });

                // Mobile Category Sidebar Component
                app.component('v-mobile-category-sidebar', {
                    template: '#v-mobile-category-sidebar-template',

                    data() {
                        return {
                            isLoading: true,
                            categories: [],
                            mainCategories: []
                        };
                    },

                    mounted() {
                        this.fetchCategories();
                    },

                    methods: {
                        fetchCategories() {
                            this.isLoading = true;

                            // Try to use axios if available, otherwise use fetch
                            const makeRequest = () => {
                                if (this.$axios) {
                                    return this.$axios.get("{{ route('shop.api.categories.tree') }}")
                                        .then(response => response.data);
                                } else {
                                    return fetch("{{ route('shop.api.categories.tree') }}")
                                        .then(response => response.json());
                                }
                            };

                            makeRequest()
                                .then(data => {
                                    this.categories = data.data || data;
                                    this.processCategories();
                                    this.setActiveCategories();
                                    this.isLoading = false;
                                })
                                .catch(error => {
                                    console.log('Mobile sidebar: Error fetching categories, using fallback', error);
                                    this.useFallbackCategories();
                                    this.isLoading = false;
                                });
                        },

                        processCategories() {
                            // Same processing logic as desktop
                            this.mainCategories = this.categories
                                .filter(category => !category.parent_id || category.parent_id === null)
                                .slice(0, 8)
                                .map(category => {
                                    return {
                                        ...category,
                                        isActive: false,
                                        expanded: false,
                                        icon: this.getCategoryIcon(category),
                                        url: this.getCategoryUrl(category),
                                        subcategories: (category.children || []).map(child => ({
                                            ...child,
                                            isActive: false,
                                            url: this.getCategoryUrl(child)
                                        }))
                                    };
                                });
                        },

                        getCategoryIcon(category) {
                            // Same icon logic as desktop version
                            const name = (category.name || '').toLowerCase();
                            const slug = (category.slug || '').toLowerCase();
                            const description = (category.description || '').toLowerCase();
                            
                            const searchText = `${name} ${slug} ${description}`;
                            
                            // Exact category matches for your database categories
                            if (name === 'laptops' || this.matchesAny(searchText, ['laptop', 'notebook'])) {
                                return 'fas fa-laptop';
                            } else if (name === 'laptop parts' || this.matchesAny(searchText, ['laptop parts', 'laptop part', 'parts'])) {
                                return 'fas fa-cog';
                            } else if (name === 'laptop accessories' || this.matchesAny(searchText, ['laptop accessories', 'laptop accessory'])) {
                                return 'fas fa-shopping-bag';
                            } else if (name === 'computer components' || this.matchesAny(searchText, ['computer components', 'component', 'hardware'])) {
                                return 'fas fa-microchip';
                            } else if (name === 'monitors & displays' || this.matchesAny(searchText, ['monitor', 'display', 'screen', 'tv'])) {
                                return 'fas fa-tv';
                            } else if (name === 'networking' || this.matchesAny(searchText, ['network', 'router', 'wifi'])) {
                                return 'fas fa-wifi';
                            } else if (name === 'printers & scanners' || this.matchesAny(searchText, ['printer', 'scanner', 'print'])) {
                                return 'fas fa-print';
                            } else if (name === 'peripherals' || this.matchesAny(searchText, ['peripheral', 'mouse', 'keyboard', 'speaker'])) {
                                return 'fas fa-mouse';
                            } else if (name === 'software' || this.matchesAny(searchText, ['software', 'program', 'app'])) {
                                return 'fas fa-code';
                            } else if (name === 'mobile' || this.matchesAny(searchText, ['mobile', 'phone', 'smartphone'])) {
                                return 'fas fa-mobile-alt';
                            } else {
                                return 'fas fa-th-large';
                            }
                        },

                        matchesAny(text, keywords) {
                            return keywords.some(keyword => text.includes(keyword));
                        },

                        getCategoryUrl(category) {
                            if (category.url && category.url !== '#' && category.url.length > 1) {
                                return category.url;
                            }
                            
                            if (category.slug) {
                                return `{{ url('/') }}/${category.slug}`;
                            }
                            
                            return `{{ route('shop.search.index') }}?category_id=${category.id}`;
                        },

                        toggleCategory(index) {
                            if (this.mainCategories[index]) {
                                this.mainCategories[index].expanded = !this.mainCategories[index].expanded;
                            }
                        },

                        closeMobileSidebar() {
                            const toggleBtn = document.getElementById('mobile-category-toggle-btn');
                            if (toggleBtn) {
                                toggleBtn.click();
                            }
                        },

                        setActiveCategories() {
                            try {
                                const currentPath = window.location.pathname;
                                
                                this.mainCategories.forEach(category => {
                                    category.isActive = currentPath.includes(category.slug) || 
                                                      currentPath.includes(`category_id=${category.id}`);
                                    
                                    category.subcategories.forEach(subcategory => {
                                        subcategory.isActive = currentPath.includes(subcategory.slug) || 
                                                              currentPath.includes(`category_id=${subcategory.id}`);
                                        if (subcategory.isActive) {
                                            category.expanded = true;
                                        }
                                    });
                                });
                            } catch (error) {
                                console.log('Mobile sidebar: Error setting active categories:', error);
                            }
                        },

                        useFallbackCategories() {
                            this.mainCategories = [
                                {
                                    id: 'fallback-1',
                                    name: 'Electronics',
                                    icon: 'fas fa-laptop',
                                    url: '/categories/electronics',
                                    isActive: false,
                                    expanded: false,
                                    subcategories: []
                                },
                                {
                                    id: 'fallback-2',
                                    name: 'Accessories',
                                    icon: 'fas fa-shopping-bag',
                                    url: '/categories/accessories',
                                    isActive: false,
                                    expanded: false,
                                    subcategories: []
                                }
                            ];
                        }
                    }
                });
            } else {
                console.log('Category sidebar: Vue app not found, using fallback');
                // Initialize fallback sidebar without Vue
                initFallbackSidebar();
            }
        });

        // Fallback sidebar functionality when Vue is not available
        function initFallbackSidebar() {
            console.log('Category sidebar: Initializing fallback mode without Vue');
            
            setTimeout(() => {
                // Fetch categories using plain JavaScript
                fetch("{{ route('shop.api.categories.tree') }}")
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fallback sidebar: Categories fetched', data);
                        const categories = data.data || data;
                        populateFallbackSidebar(categories);
                    })
                    .catch(error => {
                        console.log('Fallback sidebar: Error fetching categories', error);
                        populateHardcodedCategories();
                    });
            }, 1000);
        }

        function populateFallbackSidebar(categories) {
            const fallbackContent = document.querySelector('.fallback-sidebar .space-y-1');
            if (!fallbackContent) return;

            // Clear existing content
            fallbackContent.innerHTML = '';

            // Filter main categories and limit to 8
            const mainCategories = categories
                .filter(category => !category.parent_id || category.parent_id === null)
                .slice(0, 8);

            mainCategories.forEach(category => {
                const categoryDiv = document.createElement('div');
                categoryDiv.className = 'relative';
                
                const categoryIcon = getCategoryIconFallback(category);
                const categoryUrl = getCategoryUrlFallback(category);
                
                categoryDiv.innerHTML = `
                    <a href="${categoryUrl}" class="flex items-center justify-between p-3 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0">
                                <i class="${categoryIcon} text-lg"></i>
                            </div>
                            <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                ${category.name}
                            </span>
                        </div>
                    </a>
                `;
                
                fallbackContent.appendChild(categoryDiv);
            });
        }

        function populateHardcodedCategories() {
            const fallbackContent = document.querySelector('.fallback-sidebar .space-y-0\\.5');
            if (!fallbackContent) return;

            fallbackContent.innerHTML = `
                <div class="relative">
                    <a href="/categories/laptops" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-laptop text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Laptops
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/laptop-parts" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-cog text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Laptop Parts
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/laptop-accessories" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-shopping-bag text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Laptop Accessories
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/computer-components" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-microchip text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Computer Components
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/monitors-displays" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-tv text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Monitors & Displays
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/networking" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-wifi text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Networking
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/printers-scanners" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-print text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Printers & Scanners
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/peripherals" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-mouse text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Peripherals
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/software" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-code text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Software
                            </span>
                        </div>
                    </a>
                </div>
                <div class="relative">
                    <a href="/categories/mobile" class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-200 text-gray-700 hover:bg-orange-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 bg-gray-100 rounded-lg">
                                <i class="fas fa-mobile-alt text-lg"></i>
                            </div>
                            <span class="ml-3 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                Mobile
                            </span>
                        </div>
                    </a>
                </div>
            `;
        }

        function getCategoryIconFallback(category) {
            const name = (category.name || '').toLowerCase();
            const slug = (category.slug || '').toLowerCase();
            const searchText = `${name} ${slug}`;
            
            // Exact matches for your database categories
            if (name === 'laptops' || searchText.includes('laptop')) return 'fas fa-laptop';
            if (name === 'laptop parts' || searchText.includes('laptop parts')) return 'fas fa-cog';
            if (name === 'laptop accessories' || searchText.includes('laptop accessories')) return 'fas fa-shopping-bag';
            if (name === 'computer components' || searchText.includes('computer components')) return 'fas fa-microchip';
            if (name === 'monitors & displays' || searchText.includes('monitor') || searchText.includes('display')) return 'fas fa-tv';
            if (name === 'networking' || searchText.includes('network')) return 'fas fa-wifi';
            if (name === 'printers & scanners' || searchText.includes('printer') || searchText.includes('scanner')) return 'fas fa-print';
            if (name === 'peripherals' || searchText.includes('peripheral')) return 'fas fa-mouse';
            if (name === 'software' || searchText.includes('software')) return 'fas fa-code';
            if (name === 'mobile' || searchText.includes('mobile')) return 'fas fa-mobile-alt';
            
            // Fallback patterns
            if (searchText.includes('accessor') || searchText.includes('bag')) return 'fas fa-shopping-bag';
            if (searchText.includes('component') || searchText.includes('hardware')) return 'fas fa-microchip';
            if (searchText.includes('mouse') || searchText.includes('keyboard')) return 'fas fa-mouse';
            
            return 'fas fa-th-large';
        }

        function getCategoryUrlFallback(category) {
            if (category.url && category.url !== '#' && category.url.length > 1) {
                return category.url;
            }
            if (category.slug) {
                return `{{ url('/') }}/${category.slug}`;
            }
            return `{{ route('shop.search.index') }}?category_id=${category.id}`;
        }
        });
    </script>
@endPushOnce

@pushOnce('styles')
    <!-- Force styles to load in head for immediate application -->
    <style type="text/css">
        /* Enhanced Category Sidebar Styles - Beautiful Glass Effect */
        .category-sidebar {
            /* Modern glassmorphism effect */
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.9) 0%, 
                rgba(255, 255, 255, 0.7) 50%, 
                rgba(255, 255, 255, 0.95) 100%) !important;
            
            /* Apply blur effects */
            backdrop-filter: blur(25px) saturate(150%) !important;
            -webkit-backdrop-filter: blur(25px) saturate(150%) !important;
            
            /* Enhanced borders and shadows */
            border-right: 2px solid rgba(255, 255, 255, 0.8) !important;
            border-left: 1px solid rgba(255, 255, 255, 0.6) !important;
            
            /* Beautiful layered shadows for depth */
            box-shadow: 
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 8px 32px rgba(0, 0, 0, 0.12),
                0 4px 16px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.8),
                inset -1px 0 0 rgba(255, 255, 255, 0.4),
                inset 1px 0 0 rgba(255, 255, 255, 0.6) !important;
            
            height: 100vh !important;
            z-index: 9999 !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: fixed !important;
            top: 0 !important;
        }

        /* Content overlay for sidebar focus effect */
        #sidebar-content-overlay {
            transition: opacity 0.3s ease-in-out !important;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            background-color: rgba(0, 0, 0, 0.4) !important;
            z-index: 9998 !important;
        }

        /* Ensure sidebar has highest z-index above all headers */
        .category-sidebar {
            z-index: 9999 !important;
        }

        /* Enhanced hover effect */
        .category-sidebar:hover {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.95) 0%, 
                rgba(255, 255, 255, 0.8) 50%, 
                rgba(255, 255, 255, 0.98) 100%) !important;
            backdrop-filter: blur(30px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(30px) saturate(180%) !important;
            box-shadow: 
                0 0 0 1px rgba(255, 255, 255, 0.2),
                0 12px 40px rgba(0, 0, 0, 0.15),
                0 6px 24px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9),
                inset -1px 0 0 rgba(255, 255, 255, 0.6),
                inset 1px 0 0 rgba(255, 255, 255, 0.8) !important;
        }

        /* Add subtle light pattern overlay */
        .category-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(45deg, 
                    transparent 40%, 
                    rgba(255, 255, 255, 0.2) 50%, 
                    transparent 60%),
                linear-gradient(-45deg, 
                    transparent 40%, 
                    rgba(255, 255, 255, 0.1) 50%, 
                    transparent 60%);
            background-size: 60px 60px, 40px 40px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.6;
        }

        /* Ensure content is above the pattern */
        .category-sidebar > * {
            position: relative !important;
            z-index: 2 !important;
        }
        
        /* Fallback sidebar styling */
        .fallback-sidebar {
            display: block;
            /* Apply same glass effect to fallback */
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.7) 0%, 
                rgba(255, 255, 255, 0.3) 50%, 
                rgba(255, 255, 255, 0.8) 100%);
        }
        
        /* Hide fallback when Vue component is active */
        .category-sidebar:has(v-category-sidebar:not(:empty)) .fallback-sidebar {
            display: none;
        }
        
        /* Ensure FontAwesome is loaded */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

        /* Add background texture to the body when sidebar is present */
        body {
            background-image: 
                radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.2) 2px, transparent 0),
                radial-gradient(circle at 75px 75px, rgba(0, 0, 0, 0.1) 2px, transparent 0);
            background-size: 100px 100px, 100px 100px;
            background-position: 0 0, 50px 50px;
        }

        /* Enhanced Category Sidebar Styles - Simplified for compatibility */
        .category-sidebar {
            /* Fallback solid background */
            background: #ffffff;
            
            /* Modern glassmorphism effect (where supported) */
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.9) 0%, 
                rgba(255, 255, 255, 0.7) 50%, 
                rgba(255, 255, 255, 0.95) 100%);
            
            /* Apply blur effects */
            backdrop-filter: blur(20px) saturate(150%);
            -webkit-backdrop-filter: blur(20px) saturate(150%);
            
            /* Enhanced borders and shadows */
            border-right: 2px solid rgba(255, 255, 255, 0.8);
            border-left: 1px solid rgba(255, 255, 255, 0.6);
            
            /* Multiple layered shadows for depth */
            box-shadow: 
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 8px 32px rgba(0, 0, 0, 0.12),
                0 4px 16px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.8),
                inset -1px 0 0 rgba(255, 255, 255, 0.4),
                inset 1px 0 0 rgba(255, 255, 255, 0.6);
            
            height: 100vh;
            z-index: 30;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            
            /* Force a subtle opacity to ensure transparency is visible */
            opacity: 0.98;
        }

        /* Test styles to ensure CSS is loading */
        .category-sidebar {
            border-left: 4px solid #f97316 !important; /* Orange test border */
        }

        /* Add a subtle pattern overlay for more glass effect */
        .category-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(45deg, 
                    transparent 40%, 
                    rgba(255, 255, 255, 0.3) 50%, 
                    transparent 60%),
                linear-gradient(-45deg, 
                    transparent 40%, 
                    rgba(255, 255, 255, 0.2) 50%, 
                    transparent 60%);
            background-size: 40px 40px, 60px 60px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.8;
        }

        /* Ensure content is above the pattern */
        .category-sidebar > * {
            position: relative;
            z-index: 2;
        }

        /* Ensure header stays below sidebar */
        header, .header, nav, .navbar, .top-header, .secondary-header, .navigation {
            z-index: 9990 !important;
        }

        /* Ensure main content has proper spacing to accommodate sidebar */
        @media (min-width: 768px) {
            /* Reset any existing body padding */
            body {
                padding-left: 0 !important;
            }
            
            /* Adjust main content containers - closer to sidebar for more content space */
            #main-content {
                margin-left: 32px !important; /* Reduced from 48px - closer to sidebar */
                margin-right: 2rem !important; /* Strong right margin */
                max-width: calc(100vw - 104px) !important; /* Adjusted for new left margin */
                padding-left: 1rem !important; /* Left padding */
                padding-right: 1rem !important; /* Right padding */
                width: auto !important; /* Override any full-width settings */
            }
            
            
            /* Responsive padding adjustments for the specific class */
            @media (min-width: 640px) {
                #main-content {
                    padding-left: 1.5rem !important; /* sm:px-6 */
                    padding-right: 1.5rem !important; /* sm:px-6 */
                    margin-right: 2.5rem !important; /* More right margin on small+ screens */
                    max-width: calc(100vw - 112px) !important; /* Adjusted for reduced left margin */
                }
            }
            
            @media (min-width: 1024px) {
                #main-content {
                    padding-left: 2rem !important; /* lg:px-8 */
                    padding-right: 2rem !important; /* lg:px-8 */
                    margin-right: 3rem !important; /* Strong right margin on large screens */
                    max-width: calc(100vw - 128px) !important; /* Adjusted for reduced left margin */
                }
            }
                    padding-left: 2rem !important; /* lg:px-8 */
                    padding-right: 2rem !important; /* lg:px-8 */
                }
            }
        }

        /* Mobile adjustments */
        @media (max-width: 767px) {
            #main-content {
                margin-left: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        /* Mobile category toggle top bar */
        .category-mobile-toggle {
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 4px 20px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        /* Custom scrollbar for sidebar */
        .category-sidebar .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }

        .category-sidebar .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .category-sidebar .overflow-y-auto::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #f97316, #ea580c);
            border-radius: 2px;
        }

        .category-sidebar .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #ea580c, #dc2626);
        }

        /* Force FontAwesome icons to show properly and be centered */
        .category-sidebar i[class*="fa"],
        #mobile-category-sidebar i[class*="fa"],
        .category-mobile-toggle i[class*="fa"],
        .fallback-sidebar i[class*="fa"] {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-family: "Font Awesome 5 Free", "FontAwesome" !important;
            font-weight: 900 !important;
            font-style: normal !important;
            line-height: 1 !important;
            speak: none !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
            text-align: center !important;
            vertical-align: middle !important;
            width: 100% !important;
            height: 100% !important;
        }

        /* Ensure icon containers are properly centered */
        .category-sidebar .w-8.h-8,
        .category-sidebar .w-10.h-10,
        #mobile-category-sidebar .w-10.h-10,
        .fallback-sidebar .w-8.h-8,
        .fallback-sidebar .w-10.h-10 {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            position: relative !important;
        }

        /* Additional centering for specific fallback icons */
        .fallback-sidebar i.fas {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important; /* Back to center for collapsed state */
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
        }

        /* Move all sidebar icons slightly to the left only when expanded */
        .category-sidebar:hover i[class*="fa"],
        .fallback-sidebar:hover i[class*="fa"] {
            margin-left: -2px !important; /* Shift icons 2px to the left when expanded */
        }

        /* Default centering for collapsed state */
        .category-sidebar i[class*="fa"],
        .fallback-sidebar i[class*="fa"] {
            margin-left: -1px !important; /* Slight left adjustment for collapsed state */
        }

        /* Adjust icon containers based on sidebar state */
        .category-sidebar .w-8.h-8,
        .fallback-sidebar .w-8.h-8 {
            padding-left: 0px !important; /* No padding in collapsed state */
        }

        /* When sidebar is hovered/expanded, adjust container padding */
        .category-sidebar:hover .w-8.h-8,
        .fallback-sidebar:hover .w-8.h-8 {
            padding-left: 1px !important; /* Slight left padding when expanded */
        }

        /* Special positioning for collapsed sidebar icons */
        .category-sidebar:not(:hover) i[class*="fa"],
        .fallback-sidebar:not(:hover) i[class*="fa"] {
            position: relative !important;
            left: -1px !important; /* Move icons 1px left in collapsed state */
        }

        /* Enhanced hover effects for desktop sidebar */
        .category-sidebar:hover {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.8) 0%, 
                rgba(255, 255, 255, 0.4) 50%, 
                rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(35px) saturate(200%);
            -webkit-backdrop-filter: blur(35px) saturate(200%);
            box-shadow: 
                0 16px 48px rgba(0, 0, 0, 0.18),
                0 4px 24px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.8),
                inset -1px 0 0 rgba(255, 255, 255, 0.6);
            transform: translateZ(0);
        }

        /* Add reflection effect on hover */
        .category-sidebar:hover::before {
            background: 
                linear-gradient(45deg, 
                    transparent 20%, 
                    rgba(255, 255, 255, 0.2) 50%, 
                    transparent 80%),
                linear-gradient(-45deg, 
                    transparent 20%, 
                    rgba(255, 255, 255, 0.1) 50%, 
                    transparent 80%);
            background-size: 80px 80px, 60px 60px;
        }

        /* Active category highlighting */
        .category-active {
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
            border-left: 4px solid #f97316;
        }

        /* Smooth transitions */
        .category-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mobile sidebar animations */
        @media (max-width: 768px) {
            /* Ensure mobile sidebar is above everything */
            #mobile-category-sidebar {
                z-index: 9999 !important;
                /* Glassmorphism for mobile sidebar */
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                background: rgba(255, 255, 255, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 
                    0 8px 32px rgba(0, 0, 0, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
            }
            
            #mobile-category-overlay {
                z-index: 9998 !important;
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
            }
            
            /* Mobile body lock when sidebar is open */
            body.sidebar-open {
                overflow: hidden !important;
                position: fixed !important;
                width: 100% !important;
            }
            
            /* Mobile category button styling */
            .category-mobile-toggle button:active {
                transform: scale(0.98);
            }
        }

        /* Desktop hover dropdown positioning */
        @media (min-width: 769px) {
            .category-sidebar .group:hover .absolute {
                display: block;
                animation: slideInFromLeft 0.3s ease-out;
                /* Glassmorphism for dropdowns */
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                background: rgba(255, 255, 255, 0.9) !important;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 
                    0 8px 32px rgba(0, 0, 0, 0.15),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4);
            }
        }

        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Enhanced category item hover effects */
        .category-sidebar .hover\\:bg-orange-50:hover,
        .category-sidebar .hover\\:bg-gray-50:hover {
            background: rgba(255, 247, 237, 0.4) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 4px 16px rgba(249, 115, 22, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }

        .category-sidebar .bg-orange-100 {
            background: linear-gradient(135deg, 
                rgba(255, 247, 237, 0.6) 0%, 
                rgba(254, 215, 170, 0.4) 100%) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(249, 115, 22, 0.2);
            box-shadow: 
                0 4px 16px rgba(249, 115, 22, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        /* Add shimmer effect to icons */
        .category-sidebar i[class*="fa"] {
            position: relative;
            overflow: hidden;
        }

        .category-sidebar i[class*="fa"]::before {
            position: relative;
            z-index: 1;
        }

        .category-sidebar i[class*="fa"]::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.4), 
                transparent);
            transition: left 0.6s ease-in-out;
        }

        .category-sidebar .hover\\:bg-orange-50:hover i[class*="fa"]::after,
        .category-sidebar .hover\\:bg-gray-50:hover i[class*="fa"]::after {
            left: 100%;
        }

        /* Bagisto integration - ensure it blends with existing theme */
        .category-sidebar {
            font-family: 'Poppins', sans-serif; /* Match Bagisto's font */
        }

        /* Responsive adjustments for product listing pages */
        @media (min-width: 769px) {
            /* Remove the main content margin adjustment since we're using body padding */
        }

        /* Ensure proper z-indexing - sidebar above all headers */
        .category-sidebar {
            z-index: 9999 !important;
        }

        /* Better mobile touch targets */
        @media (max-width: 768px) {
            #mobile-category-sidebar button,
            #mobile-category-sidebar a {
                min-height: 44px; /* iOS recommended touch target size */
                display: flex;
                align-items: center;
            }
        }

        /* Loading state styling */
        .category-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Enhanced focus states for accessibility */
        .category-sidebar a:focus,
        .category-sidebar button:focus,
        #mobile-category-sidebar a:focus,
        #mobile-category-sidebar button:focus {
            outline: 2px solid #f97316;
            outline-offset: 2px;
        }

        /* Custom orange color for better brand consistency */
        .text-orange-custom {
            color: #f97316;
        }

        .bg-orange-custom {
            background-color: #f97316;
        }

        .border-orange-custom {
            border-color: #f97316;
        }

        /* Gradient backgrounds for visual appeal */
        .bg-gradient-orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .bg-gradient-orange-light {
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
        }
    </style>
@endPushOnce