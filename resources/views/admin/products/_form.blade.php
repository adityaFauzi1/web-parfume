{{-- Form produk (dipakai oleh create & edit) --}}
<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <h3 class="font-display text-lg">Informasi Produk</h3>

            <div class="mt-4 space-y-4">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-stone-700">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required
                           class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="mb-1 block text-sm font-medium text-stone-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="5"
                              class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <h3 class="font-display text-lg">Harga & Stok</h3>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="price" class="mb-1 block text-sm font-medium text-stone-700">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="1000" required
                           class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="purchase_price" class="mb-1 block text-sm font-medium text-stone-700">Harga Beli / Modal (Rp)</label>
                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}" min="0"
                           placeholder="0"
                           class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    <p class="mt-1 text-xs text-stone-400">Modal dari supplier per botol.</p>
                    @error('purchase_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="stock" class="mb-1 block text-sm font-medium text-stone-700">Stok <span class="text-red-500">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? '') }}" min="0" required
                           class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    @error('stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="min_stock" class="mb-1 block text-sm font-medium text-stone-700">Batas Minimum Stok</label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? 5) }}" min="0"
                           class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    <p class="mt-1 text-xs text-stone-400">Peringatan restock muncul jika stok ≤ angka ini.</p>
                    @error('min_stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="size" class="mb-1 block text-sm font-medium text-stone-700">Ukuran <span class="text-red-500">*</span></label>
                    <input type="text" id="size" name="size" value="{{ old('size', $product->size ?? '100 ml') }}" required
                           placeholder="cth: 100 ml"
                           class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    @error('size') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="gender" class="mb-1 block text-sm font-medium text-stone-700">Gender <span class="text-red-500">*</span></label>
                    <select id="gender" name="gender" required
                            class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                        @foreach(['Pria', 'Wanita', 'Unisex'] as $gender)
                            <option value="{{ $gender }}" @selected(old('gender', $product->gender ?? '') === $gender)>{{ $gender }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <label for="category_id" class="mb-1 block text-sm font-medium text-stone-700">Kategori <span class="text-red-500">*</span></label>
            <select id="category_id" name="category_id" required
                    class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <label class="mb-1 block text-sm font-medium text-stone-700">Gambar</label>
            @if(isset($product) && $product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="mb-3 aspect-square w-40 rounded-xl object-cover ring-1 ring-stone-200">
            @endif
            <input type="file" id="image" name="image" accept="image/*"
                   class="w-full text-sm text-stone-500 file:mr-3 file:rounded-lg file:border-0 file:bg-stone-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-stone-700">
            <p class="mt-2 text-xs text-stone-400">JPG, PNG, WEBP atau SVG. Maks 2MB. Kosongkan jika tidak diubah.</p>
            @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <h3 class="font-display text-lg">Status</h3>
            <div class="mt-4 space-y-3">
                <label class="flex items-center gap-3 text-sm text-stone-700">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))
                           class="rounded border-stone-300 text-amber-500 focus:ring-amber-500">
                    Tampilkan sebagai produk unggulan
                </label>
                <label class="flex items-center gap-3 text-sm text-stone-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))
                           class="rounded border-stone-300 text-amber-500 focus:ring-amber-500">
                    Produk aktif (tampil di toko)
                </label>
            </div>
        </div>
    </div>
</div>