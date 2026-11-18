<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller {
    public function index(Request $request){
        $query = User::withCount('orders')->latest();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(fn($w) => $w->where('name','like',"%{$q}%")->orWhere('email','like',"%{$q}%"));
        }
        $users = $query->paginate(15)->withQueryString();
        $totalUsers = User::count();
        return view('admin.users.index', compact('users','totalUsers'));
    }
}
