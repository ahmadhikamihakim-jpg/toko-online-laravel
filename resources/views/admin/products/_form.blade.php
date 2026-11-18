<label for="name">Nama Produk</label><input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
<label for="category_id">Kategori</label><select id="category_id" name="category_id">@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product->category_id ?? '')==$c->id)>{{ $c->name }}</option>@endforeach</select>
<label for="description">Deskripsi</label><textarea id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
<label for="price">Harga (Rupiah, tanpa titik)</label><input id="price" type="number" name="price" min="0" value="{{ old('price', $product->price ?? '') }}" required>
<label for="stock">Stok</label><input id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
@if(!empty($product) && $product->image)<p class="muted" style="margin:0 0 6px">Foto saat ini:</p><img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset($product->image) }}" alt="Foto {{ $product->name }}" style="width:180px;height:130px;object-fit:cover;border-radius:12px;display:block;margin-bottom:10px">@endif
<label for="photo">Unggah Foto Produk (opsional, maks 2MB)</label><input id="photo" type="file" name="photo" accept="image/*">
<label for="image">atau isi Path/URL Gambar (opsional)</label><input id="image" name="image" value="{{ old('image', $product->image ?? '') }}" placeholder="https://...">
<label style="font-weight:400"><input type="checkbox" name="is_active" value="1" style="width:auto" @checked(old('is_active', $product->is_active ?? true))> Produk aktif / tampil di toko</label><br>
