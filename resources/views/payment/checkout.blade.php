<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 min-h-screen">
    <nav class="bg-black/40 backdrop-blur-lg border-b border-gray-800">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('booking.index') }}"><h1 class="text-2xl font-bold text-white">🎬 Cinema</h1></a>
                
                <!-- Desktop Menu (hidden on mobile) -->
                <div class="hidden md:flex gap-4">
                    <a href="{{ route('booking.index') }}" class="text-gray-300 hover:text-white font-medium">Đặt Vé</a>
                    <a href="{{ route('my.bookings') }}" class="text-gray-300 hover:text-white font-medium">Vé Của Tôi</a>
                    <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white font-medium">Cài Đặt</a>
                </div>

                <!-- Hamburger Button (visible on mobile) -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-800 transition-colors" aria-label="Menu">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Dropdown Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-700 pt-4">
                <div class="flex flex-col gap-3">
                    <a href="{{ route('booking.index') }}" class="text-gray-300 hover:text-white font-medium py-2">Đặt Vé</a>
                    <a href="{{ route('my.bookings') }}" class="text-gray-300 hover:text-white font-medium py-2">Vé Của Tôi</a>
                    <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white font-medium py-2">Cài Đặt</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 rounded-lg p-4 mb-6">
                    <p class="text-red-200">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-black/40 backdrop-blur-lg rounded-xl border border-gray-800 p-4 md:p-8 text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4 md:mb-6">Thanh toán</h2>
                
                @php
                    $bankId = env('VIETQR_BANK_ID', '970422');
                    $accountNo = env('VIETQR_ACCOUNT_NO', '0378366953');
                    $accountName = env('VIETQR_ACCOUNT_NAME', 'NGUYEN LUU BAO KHANG');
                    $orderId = 'ORDER' . time();
                    
                    // Sử dụng SePay QR
                    $qrUrl = "https://qr.sepay.vn/img?acc={$accountNo}&bank={$bankId}&amount={$totalAmount}&des=" . urlencode($orderId);
                @endphp

                <div class="bg-white p-3 md:p-4 rounded-xl inline-block mb-4 md:mb-6">
                    <img src="{{ $qrUrl }}" alt="QR Code" class="w-[200px] h-[200px] md:w-64 md:h-64 object-contain min-w-[200px] min-h-[200px]">
                </div>

                <div class="mb-4 md:mb-6">
                    <p class="text-3xl md:text-5xl font-bold text-white mb-2">{{ number_format($totalAmount, 0, ',', '.') }} đ</p>
                    <p class="text-gray-300 text-base md:text-lg mb-1">{{ $showtime->movie->title }}</p>
                    <p class="text-gray-400 text-sm">Nội dung CK: {{ $orderId }}</p>
                </div>

                <div class="bg-gray-800/50 rounded-lg p-3 md:p-4 mb-4 md:mb-6 text-left">
                    <p class="text-gray-300 text-sm mb-2">📱 <strong>Ngân hàng:</strong> MB Bank ({{ $bankId }})</p>
                    <p class="text-gray-300 text-sm mb-2">💳 <strong>Số TK:</strong> {{ $accountNo }}</p>
                    <p class="text-gray-300 text-sm">👤 <strong>Tên TK:</strong> {{ $accountName }}</p>
                </div>

                <form method="POST" action="{{ route('payment.confirm') }}">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $orderId }}">
                    <input type="hidden" name="amount" value="3000000 vnd">
                    
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-4 rounded-lg font-bold text-lg shadow-lg hover:shadow-xl transition-all min-h-[48px]">
                        ✓ Đã thanh toán
                    </button>
                    
                    <a href="{{ route('booking.index') }}" 
                       class="flex items-center justify-center w-full mt-3 bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-lg font-medium transition-all min-h-[48px]">
                        ← Quay lại
                    </a>
                </form>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                // Toggle menu on button click
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
                
                // Close menu when clicking on a menu link
                mobileMenu.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
        });
    </script>
</body>
</html>