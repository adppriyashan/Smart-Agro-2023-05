<?php

namespace App\Http\Controllers;

use App\Models\CarPark;
use App\Models\Feedback;
use App\Models\ParkingRecords;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $carparks = 0;
        $bookings = 0;
        $customers = 0;
        $feedbacks = 0;

        $dates = [];
        $sales = [];

        $sales = array_reverse($sales);

        return view('home', compact('carparks', 'bookings', 'customers', 'feedbacks','dates', 'sales'));
    }
}
