<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller {
    public function index(Request $request) {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $query = Product::with('category')->where('is_active', true);
        if ($request->filled('q')) { $q=$request->input('q'); $query->where(fn($x)=>$x->where('name','like',"%{$q}%")->orWhere('description','like',"%{$q}%")); }
        if ($request->filled('kategori')) { $query->whereHas('category', fn($x)=>$x->where('slug', $request->input('kategori'))); }
        $products = $query->latest()->paginate(9)->withQueryString();
        return view('home', compact('products','categories'));
    }
    public function show(Product $product) {
        abort_unless($product->is_active, 404);
        $related = Product::where('category_id',$product->category_id)->where('id','!=',$product->id)->where('is_active',true)->take(4)->get();
        return view('products.show', compact('product','related'));
    }
}
