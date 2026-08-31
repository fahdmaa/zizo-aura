@extends('admin.layout')
@section('title', isset($category) ? 'Modifier catégorie' : 'Nouvelle catégorie')
@section('heading', isset($category) ? 'Modifier · '.$category->name : 'Nouvelle catégorie')
@section('header-actions')
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500 hover:text-gray-800">← Retour</a>
@endsection

@section('content')
@php $editing = isset($category); @endphp
<form method="POST"
      action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      class="max-w-lg space-y-6">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                class="input-luxury w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}"
                placeholder="généré automatiquement"
                class="input-luxury w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Image (URL)</label>
            <input type="text" name="image" value="{{ old('image', $category->image ?? '') }}"
                class="input-luxury w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                class="textarea-luxury w-full">{{ old('description', $category->description ?? '') }}</textarea>
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded accent-pink-500">
                Active (visible sur le site)
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-pill-primary">
            {{ $editing ? 'Enregistrer' : 'Créer' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn-pill-secondary">
            Annuler
        </a>
    </div>
</form>
@endsection
