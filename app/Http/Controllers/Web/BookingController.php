<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\SeatLock;
use App\Models\OrderLine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // Lấy tất cả phim đang active
        // thêm bug lấy tất cả phim thiếu điều kiện where('is_active', true)
        $movies = Movie::orderBy('title', 'asc')
            ->get();
            
        return view('booking.index', compact('movies'));
    }
    
    public function showMovieDetail($id)
    {
        // tương tự thêm bug như bên trên để hiển thị sai
        $movie = Movie::with(['showtimes' => function($query) {
                $query->where('status', 'OPEN')
                    ->where('start_at', '>=', Carbon::now())
                    ->orderBy('start_at', 'asc')
                    ->with('screen');
            }])
            ->findOrFail($id);
        
        // Group showtimes by date
        $showtimesByDate = $movie->showtimes->groupBy(function($showtime) {
            return Carbon::parse($showtime->start_at)->format('Y-m-d');
        });
        
        return view('booking.movie-detail', compact('movie', 'showtimesByDate'));
    }
}
