@auth
    <div class="mt-4">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard Admin →</a>
    </div>
@endauth

<a href="{{ route('shop.show', $product->slug) }}" class="group block overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-stone-200 transition hover:shadow-lg">
    <div class="relative aspect-square overflow-hidden bg-stone-100">
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @if($product->is_featured)
            <span class="absolute left-3 top-3 rounded-full bg-amber-500 px-3 py-1 text-[11px] font-semibold text-stone-950">Unggulan</span>
        @endif
        @if($product->stock <= 0)
            <div class="absolute inset-0 flex items-center justify-center bg-stone-900/60">
                <span class="rounded-full bg-white/90 px-4 py-1.5 text-xs font-semibold text-stone-900">Habis</span>
            </div>
        @endif
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between text-[11px] uppercase tracking-wider text-stone-500">
            <span>{{ $product->category?->name }}</span>
            <span>{{ $product->size }}</span>
        </div>
        <h3 class="mt-2 truncate font-display text-base text-stone-900">{{ $product->name }}</h3>
        <p class="mt-1 font-semibold text-amber-700">{{ $product->formatted_price }}</p>
    </div>
</a>