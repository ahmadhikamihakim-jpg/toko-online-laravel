<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category; use App\Models\Product;
use Illuminate\Http\Request; use Illuminate\Support\Str;
class ProductController extends Controller {
    public function index(){ $products=Product::with('category')->latest()->paginate(15); return view('admin.products.index', compact('products')); }
    public function create(){ $categories=Category::orderBy('name')->get(); return view('admin.products.create', compact('categories')); }
    public function store(Request $request){ $data=$this->validated($request); $data['slug']=$this->uniqueSlug($data['name']); $data['is_active']=$request->boolean('is_active'); $data=$this->handlePhoto($request,$data); Product::create($data); return redirect()->route('admin.products.index')->with('success','Produk ditambahkan.'); }
    public function edit(Product $product){ $categories=Category::orderBy('name')->get(); return view('admin.products.edit', compact('product','categories')); }
    public function update(Request $request, Product $product){ $data=$this->validated($request); $data['slug']=$this->uniqueSlug($data['name'],$product->id); $data['is_active']=$request->boolean('is_active'); $data=$this->handlePhoto($request,$data); $product->update($data); return redirect()->route('admin.products.index')->with('success','Produk diperbarui.'); }
    public function destroy(Product $product){ $product->delete(); return redirect()->route('admin.products.index')->with('success','Produk dihapus.'); }
    private function validated(Request $request): array { return $request->validate(['category_id'=>'required|exists:categories,id','name'=>'required|string|max:255','description'=>'nullable|string','price'=>'required|integer|min:0','stock'=>'required|integer|min:0','image'=>'nullable|string|max:1000','photo'=>'nullable|image|max:2048']); }
    private function handlePhoto(Request $request, array $data): array { unset($data['photo']); if ($request->hasFile('photo')) { $file=$request->file('photo'); $filename=$data['slug'].'-'.time().'.'.$file->getClientOriginalExtension(); $file->move(public_path('images/products'), $filename); $data['image']='images/products/'.$filename; } return $data; }
    private function uniqueSlug(string $name, ?int $ignoreId=null): string { $base=Str::slug($name)?:'produk'; $slug=$base; $i=2; while(Product::where('slug',$slug)->when($ignoreId,fn($q)=>$q->where('id','!=',$ignoreId))->exists()){ $slug=$base.'-'.$i++; } return $slug; }
}
