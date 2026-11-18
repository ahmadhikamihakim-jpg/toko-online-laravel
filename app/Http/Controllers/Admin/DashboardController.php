<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category; use App\Models\Order; use App\Models\Product; use App\Models\User;
class DashboardController extends Controller {
    public function index() {
        $stats=['products'=>Product::count(),'categories'=>Category::count(),'orders'=>Order::count(),'users'=>User::count(),'revenue'=>(int)Order::where('status','!=','cancelled')->sum('total')];
        $latestOrders=Order::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats','latestOrders'));
    }
}
