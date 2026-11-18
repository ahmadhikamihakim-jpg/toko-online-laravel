<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller {
    public function index(Request $request){ $query=Order::with('user')->latest(); if($request->filled('status')) $query->where('status',$request->input('status')); $orders=$query->paginate(15)->withQueryString(); return view('admin.orders.index', compact('orders')); }
    public function show(Order $order){ $order->load(['user','items']); return view('admin.orders.show', compact('order')); }
    public function update(Request $request, Order $order){ $data=$request->validate(['status'=>'required|in:pending,paid,shipped,completed,cancelled']); $order->update($data); return back()->with('success','Status pesanan diperbarui menjadi '.$order->statusLabel().'.'); }
}
