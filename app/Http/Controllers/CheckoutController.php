<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class CheckoutController extends Controller {
    public function index() {
        $cart = CartController::items();
        if (empty($cart)) return redirect()->route('cart.index')->with('error','Keranjang masih kosong.');
        $total = CartController::total($cart);
        $midtransConfigured = app(\App\Services\MidtransService::class)->configured();
        return view('checkout.index', compact('cart','total','midtransConfigured'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'recipient_name'=>'required|string|max:255','phone'=>'required|string|max:30','address'=>'required|string|max:1000','payment_method'=>'required|in:transfer,cod,midtrans',
        ]);
        $cart = CartController::items();
        if (empty($cart)) return redirect()->route('cart.index')->with('error','Keranjang masih kosong.');
        $order = DB::transaction(function() use ($data,$cart,$request) {
            $total=0; $items=[];
            foreach ($cart as $item) {
                $product = Product::where('id',$item['id'])->lockForUpdate()->first();
                if (!$product || !$product->is_active) throw ValidationException::withMessages(['cart'=>'Produk "'.$item['name'].'" tidak tersedia lagi.']);
                if ($product->stock < $item['quantity']) throw ValidationException::withMessages(['cart'=>'Stok "'.$product->name.'" tidak cukup. Sisa stok: '.$product->stock]);
                $subtotal=(int)$product->price*(int)$item['quantity']; $total+=$subtotal;
                $items[]=['product'=>$product,'name'=>$product->name,'price'=>(int)$product->price,'quantity'=>(int)$item['quantity'],'subtotal'=>$subtotal];
            }
            do { $code='INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)); } while (Order::where('code',$code)->exists());
            $order = Order::create(['user_id'=>$request->user()->id,'code'=>$code,'recipient_name'=>$data['recipient_name'],'phone'=>$data['phone'],'address'=>$data['address'],'payment_method'=>$data['payment_method'],'status'=>'pending','total'=>$total]);
            foreach ($items as $it) {
                $order->items()->create(['product_id'=>$it['product']->id,'product_name'=>$it['name'],'price'=>$it['price'],'quantity'=>$it['quantity'],'subtotal'=>$it['subtotal']]);
                $it['product']->decrement('stock', $it['quantity']);
            }
            return $order;
        });
        session()->forget('cart');
        if ($order->payment_method === 'midtrans') {
            return redirect()->route('payment.show', $order)->with('success','Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
        }
        return redirect()->route('checkout.success', $order)->with('success','Pesanan berhasil dibuat!');
    }
    public function success(Order $order) {
        abort_unless($order->user_id === auth()->id() || (auth()->user()?->is_admin), 403);
        $order->load('items');
        return view('checkout.success', compact('order'));
    }
    public function myOrders() { $orders = auth()->user()->orders()->with('items')->latest()->paginate(10); return view('orders.index', compact('orders')); }
    public function myOrderShow(Order $order) { abort_unless($order->user_id===auth()->id(),403); $order->load('items'); return view('orders.show', compact('order')); }
}
