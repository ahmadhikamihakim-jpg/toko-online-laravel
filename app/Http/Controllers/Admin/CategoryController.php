<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request; use Illuminate\Support\Str; use Illuminate\Validation\Rule;
class CategoryController extends Controller {
    public function index(){ $categories=Category::withCount('products')->orderBy('name')->paginate(15); return view('admin.categories.index', compact('categories')); }
    public function create(){ return view('admin.categories.create'); }
    public function store(Request $request){ $data=$request->validate(['name'=>'required|string|max:255','description'=>'nullable|string']); $data['slug']=$this->uniqueSlug($data['name']); Category::create($data); return redirect()->route('admin.categories.index')->with('success','Kategori ditambahkan.'); }
    public function edit(Category $category){ return view('admin.categories.edit', compact('category')); }
    public function update(Request $request, Category $category){ $data=$request->validate(['name'=>'required|string|max:255','description'=>'nullable|string']); $data['slug']=$this->uniqueSlug($data['name'],$category->id); $category->update($data); return redirect()->route('admin.categories.index')->with('success','Kategori diperbarui.'); }
    public function destroy(Category $category){ $category->delete(); return redirect()->route('admin.categories.index')->with('success','Kategori dihapus.'); }
    private function uniqueSlug(string $name, ?int $ignoreId=null): string { $base=Str::slug($name)?:'kategori'; $slug=$base; $i=2; while(Category::where('slug',$slug)->when($ignoreId,fn($q)=>$q->where('id','!=',$ignoreId))->exists()){ $slug=$base.'-'.$i++; } return $slug; }
}
