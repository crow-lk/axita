@props([
    'banner' => null,
])

@if ($banner)
    <div class="popup-banner-wrapper">
        <style>
            .popup-banner-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }
            
            .popup-banner-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .popup-banner-content {
                position: relative;
                max-width: 1600px;
                width: 95%;
                margin: 20px;
            }
            
            .popup-banner-close {
                position: absolute;
                top: -15px;
                right: -15px;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: white;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                z-index: 10000;
            }
            
            .popup-banner-close:hover {
                background: #f3f4f6;
            }
            
            .popup-banner-image {
                width: 100%;
                max-height: 675px;
                object-fit: contain;
                border-radius: 8px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
        </style>
        
        <div class="popup-banner-overlay" id="popupBanner">
            <div class="popup-banner-content">
                <button class="popup-banner-close" onclick="closePopupBanner()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                
                @if ($banner->link)
                    <a href="{{ $banner->link }}" target="_blank" onclick="closePopupBanner()">
                        <img 
                            src="{{ asset('storage/' . $banner->image_path) }}" 
                            alt="{{ $banner->title ?? 'Popup Banner' }}"
                            class="popup-banner-image"
                        >
                    </a>
                @else
                    <img 
                        src="{{ asset('storage/' . $banner->image_path) }}" 
                        alt="{{ $banner->title ?? 'Popup Banner' }}"
                        class="popup-banner-image"
                    >
                @endif
            </div>
        </div>
        
        <script>
            function closePopupBanner() {
                document.getElementById('popupBanner').classList.remove('active');
                sessionStorage.setItem('popup_banner_shown', 'true');
            }
            
            document.addEventListener('DOMContentLoaded', function() {
                // Check if popup was already shown in this session
                if (!sessionStorage.getItem('popup_banner_shown')) {
                    setTimeout(function() {
                        document.getElementById('popupBanner').classList.add('active');
                    }, 500);
                }
            });
        </script>
    </div>
@endif
