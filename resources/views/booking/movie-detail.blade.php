<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie->title }} - Cinema Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('booking.index') }}" class="text-gray-500 hover:text-purple-600 text-2xl transition-colors">←</a>
                    <div class="flex items-center gap-2">
                        <span class="text-3xl">🎬</span>
                        <h1 class="text-2xl font-bold text-gray-900">Galaxy Cinema</h1>
                    </div>
                </div>
                
                <!-- Desktop Menu (hidden on mobile) -->
                <div class="hidden md:flex gap-4 items-center">
                    <a href="{{ route('booking.index') }}" class="text-gray-600 hover:text-purple-600 font-medium">Đặt Vé</a>
                    <a href="{{ route('my.bookings') }}" class="text-gray-600 hover:text-purple-600 font-medium">Vé Của Tôi</a>
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-purple-600 font-medium">Cài Đặt</a>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-700 font-medium">{{ auth()->user()->full_name }}</span>
                    <form action="{{ route('auth.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Đăng xuất</button>
                    </form>
                </div>

                <!-- Hamburger Button (visible on mobile) -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Menu">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Dropdown Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-200 pt-4">
                <div class="flex flex-col gap-3">
                    <a href="{{ route('booking.index') }}" class="text-gray-600 hover:text-purple-600 font-medium py-2">Đặt Vé</a>
                    <a href="{{ route('my.bookings') }}" class="text-gray-600 hover:text-purple-600 font-medium py-2">Vé Của Tôi</a>
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-purple-600 font-medium py-2">Cài Đặt</a>
                    <div class="border-t border-gray-200 pt-3 mt-2">
                        <span class="text-gray-700 font-medium block mb-3">{{ auth()->user()->full_name }}</span>
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg transition-all shadow-md hover:shadow-lg min-h-[48px]">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-4 md:py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Movie Hero Section -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 p-4 md:p-8">
                    <!-- Poster -->
                    <div class="col-span-1">
                        @if($movie->poster)
                            <img src="{{ $movie->poster }}" 
                                 alt="{{ $movie->title }}" 
                                 class="w-full rounded-xl shadow-md">
                        @else
                            <div class="w-full aspect-[2/3] bg-gray-200 rounded-xl flex items-center justify-center">
                                <span class="text-gray-400 text-8xl">🎬</span>
                            </div>
                        @endif
                    </div>

                    <!-- Movie Details -->
                    <div class="col-span-1 md:col-span-2">
                        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4">{{ $movie->title }}</h1>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            @if($movie->genre)
                                <div>
                                    <p class="text-gray-500 text-sm mb-1">Thể loại</p>
                                    <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full inline-block font-medium">
                                        {{ $movie->genre }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($movie->duration_min)
                                <div>
                                    <p class="text-gray-500 text-sm mb-1">Thời lượng</p>
                                    <p class="text-gray-900 font-semibold text-lg flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $movie->duration_min }} phút
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Showtimes -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 md:p-8 shadow-md">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6">⏰ Chọn Suất Chiếu</h2>

                @if($showtimesByDate->count() > 0)
                    <div class="space-y-6 md:space-y-8">
                        @foreach($showtimesByDate as $date => $showtimes)
                            <div>
                                <h3 class="text-lg md:text-xl font-semibold text-purple-700 mb-3 md:mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                                    <span>📅</span>
                                    <span>
                                        {{ \Carbon\Carbon::parse($date)->locale('vi')->isoFormat('dddd, DD/MM/YYYY') }}
                                    </span>
                                </h3>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                                    @foreach($showtimes as $showtime)
                                        <a href="{{ route('booking.seat-map', $showtime->id) }}" 
                                           class="bg-white hover:bg-purple-50 border border-gray-200 hover:border-purple-500 rounded-xl p-3 md:p-4 text-center transition-all group shadow-sm hover:shadow-md min-h-[88px] flex flex-col justify-center">
                                            <p class="text-2xl md:text-3xl font-bold text-gray-800 group-hover:text-purple-700 mb-1 md:mb-2">
                                                {{ \Carbon\Carbon::parse($showtime->start_at)->format('H:i') }}
                                            </p>
                                            <p class="text-xs md:text-sm text-gray-500 group-hover:text-gray-700 mb-1 md:mb-2">
                                                🏛️ {{ $showtime->screen->name ?? 'Screen' }}
                                            </p>
                                            <p class="text-base md:text-lg text-purple-600 font-bold">
                                                {{ number_format($showtime->base_price ?? 100000) }}đ
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 md:py-16">
                        <span class="text-5xl md:text-6xl mb-4 block">😔</span>
                        <p class="text-gray-500 text-lg md:text-xl">Hiện tại chưa có lịch chiếu cho phim này</p>
                    </div>
                @endif
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
