<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
class CartController extends Controller {
    public static function items(): array { return session()->get('cart', []); }
    public static function total(array $cart): int { return collect($cart)->sum(fn($i)=>$i['price']*$i['quantity']); }
    public static function count(): int { return collect(session()->get('cart', []))->sum('quantity'); }
    public function index() { $cart=self::items(); $total=self::total($cart); return view('cart.index', compact('cart','total')); }
    public function add(Request $request, Product $product) {
        abort_unless($product->is_active, 404);
        $qty = max(1, (int)$request->input('quantity',1));
        if ($product->stock < 1) { return back()->with('error','Stok produk habis.'); }
        $cart = self::items();
        $existing = $cart[$product->id]['quantity'] ?? 0;
        $newQty = min($existing+$qty, $product->stock);
        $cart[$product->id] = ['id'=>$product->id,'name'=>$product->name,'slug'=>$product->slug,'price'=>(int)$product->price,'image'=>$product->image,'quantity'=>$newQty];
        session()->put('cart',$cart);
        return redirect()->route('cart.index')->with('success','Produk ditambahkan ke keranjang.');
    }
    public function update(Request $request) {
        $cart=self::items();
        foreach ((array)$request->input('quantities',[]) as $id=>$qty) {
            if (!isset($cart[$id])) continue;
            $qty=(int)$qty;
            if ($qty<=0) { unset($cart[$id]); continue; }
            $product=Product::find($id); $max=$product ? (int)$product->stock : $qty;
            $cart[$id]['quantity']=min($qty, max(1,$max));
        }
        session()->put('cart',$cart);
        return redirect()->route('cart.index')->with('success','Keranjang diperbarui.');
    }
    public function remove(Product $product) { $cart=self::items(); unset($cart[$product->id]); session()->put('cart',$cart); return redirect()->route('cart.index')->with('success','Produk dihapus dari keranjang.'); }
    public function clear() { session()->forget('cart'); return redirect()->route('cart.index')->with('success','Keranjang dikosongkan.'); }
}
