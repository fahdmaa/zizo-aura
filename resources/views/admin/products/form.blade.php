@extends('admin.layout')
@section('title', isset($product) ? 'Modifier produit' : 'Nouveau produit')
@section('heading', isset($product) ? 'Modifier · '.$product->name : 'Nouveau produit')

@section('header-actions')
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-800">← Retour</a>
@endsection

@section('content')
@php $editing = isset($product); @endphp

<form method="POST"
      action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}"
      class="max-w-3xl space-y-8">
    @csrf
    @if($editing) @method('PUT') @endif

    {{-- Basic info --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        <h2 class="font-semibold text-gray-800 border-b pb-3">Informations de base</h2>

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit *</label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                    class="input-luxury w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                <select name="category_id" required
                    class="select-luxury w-full">
                    <option value="">-- Choisir --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug ?? '') }}"
                    placeholder="généré automatiquement"
                    class="input-luxury w-full">
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="textarea-luxury w-full">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Pricing --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        <h2 class="font-semibold text-gray-800 border-b pb-3">Prix (DH)</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix original *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required
                    class="input-luxury w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix soldé <span class="text-gray-400">(laisser vide si aucune promo)</span></label>
                <input type="number" step="0.01" name="discounted_price" value="{{ old('discounted_price', $product->discounted_price ?? '') }}"
                    class="input-luxury w-full">
            </div>
        </div>
    </div>

    {{-- Image --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-800 border-b pb-3">Image</h2>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">URL image principale *</label>
            <input type="text" name="image" value="{{ old('image', $product->image ?? '') }}" required
                class="input-luxury w-full"
                placeholder="https://... ou /images/produits/...">
        </div>
    </div>

    {{-- Badges & flags --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 border-b pb-3 mb-5">Badges & disponibilité</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            @foreach([
                ['is_new',        'Nouveau'],
                ['is_bestseller', 'Best-seller'],
                ['in_stock',      'En stock'],
                ['is_active',     'Actif (visible)'],
                ['has_sizes',     'Variantes de taille'],
                ['has_flavors',   'Variantes de parfum'],
            ] as [$field, $label])
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="hidden" name="{{ $field }}" value="0">
                <input type="checkbox" name="{{ $field }}" value="1"
                    {{ old($field, $product->$field ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded accent-pink-500">
                {{ $label }}
            </label>
            @endforeach
        </div>
    </div>

    {{-- Sizes (shown dynamically) --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-800 border-b pb-3">Tailles / Volumes <span class="text-gray-400 font-normal text-xs">(optionnel)</span></h2>
        <div id="sizes-list" class="space-y-2">
            @foreach(old('sizes', $product->sizes->toArray() ?? []) as $i => $size)
            <div class="flex gap-2 items-center">
                <input type="text" name="sizes[{{ $i }}][label]" value="{{ $size['label'] }}"
                    placeholder="ex: 100ml" class="input-luxury flex-1">
                <input type="number" step="0.01" name="sizes[{{ $i }}][price]" value="{{ $size['price'] ?? '' }}"
                    placeholder="Prix DH" class="input-luxury w-28">
                <label class="flex items-center gap-1 text-xs text-gray-500">
                    <input type="checkbox" name="sizes[{{ $i }}][in_stock]" {{ $size['in_stock'] ? 'checked' : '' }}> Stock
                </label>
                <button type="button" onclick="this.closest('.flex').remove()" class="btn-circle-action text-red-400 hover:text-red-600 text-lg leading-none">×</button>
            </div>
            @endforeach
        </div>
        <button type="button" onclick="addSize()" class="btn-pill-secondary btn-pill-sm">+ Ajouter une taille</button>
    </div>

    {{-- Save --}}
    <div class="flex gap-3">
        <button type="submit" class="btn-pill-primary">
            {{ $editing ? 'Enregistrer les modifications' : 'Créer le produit' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn-pill-secondary">
            Annuler
        </a>
    </div>
</form>

<script>
let sizeIdx = {{ count(old('sizes', $product->sizes->toArray() ?? [])) }};
function addSize() {
    document.getElementById('sizes-list').insertAdjacentHTML('beforeend', `
        <div class="flex gap-2 items-center">
            <input type="text" name="sizes[${sizeIdx}][label]" placeholder="ex: 100ml" class="input-luxury flex-1">
            <input type="number" step="0.01" name="sizes[${sizeIdx}][price]" placeholder="Prix DH" class="input-luxury w-28">
            <label class="flex items-center gap-1 text-xs text-gray-500">
                <input type="checkbox" name="sizes[${sizeIdx}][in_stock]" checked> Stock
            </label>
            <button type="button" onclick="this.closest('.flex').remove()" class="btn-circle-action text-red-400 hover:text-red-600 text-lg leading-none">×</button>
        </div>
    `);
    sizeIdx++;
}
</script>
@endsection
