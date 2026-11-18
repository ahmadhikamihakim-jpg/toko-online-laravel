<label for="name">Nama Kategori</label><input id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required>
<label for="description">Deskripsi</label><textarea id="description" name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
