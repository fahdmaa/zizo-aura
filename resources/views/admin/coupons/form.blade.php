@extends('admin.layout')
@section('title', isset($coupon) ? 'Modifier code promo' : 'Nouveau code promo')
@section('heading', isset($coupon) ? 'Modifier · '.$coupon->code : 'Nouveau code promo')
@section('header-actions')
    <a href="{{ route('admin.coupons.index') }}" class="text-sm text-gray-500 hover:text-gray-800">← Retour</a>
@endsection

@section('content')
@php $editing = isset($coupon); @endphp
<form method="POST"
      action="{{ $editing ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
      class="max-w-lg space-y-6">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required
                    class="input-luxury w-full font-mono uppercase"
                    placeholder="EX: SUMMER20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <select name="type" required class="select-luxury w-full">
                    <option value="percent" {{ old('type', $coupon->type ?? '') === 'percent' ? 'selected' : '' }}>% Pourcentage</option>
                    <option value="fixed"   {{ old('type', $coupon->type ?? '') === 'fixed'   ? 'selected' : '' }}>DH Montant fixe</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valeur *</label>
                <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value ?? '') }}" required
                    class="input-luxury w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Montant min. panier (DH)</label>
                <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}"
                    class="input-luxury w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max utilisations</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses ?? '') }}"
                    placeholder="Illimité"
                    class="input-luxury w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d') : '') }}"
                    class="input-luxury w-full">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
                class="w-4 h-4 rounded accent-pink-500">
            Actif
        </label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-pill-primary">
            {{ $editing ? 'Enregistrer' : 'Créer' }}
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="btn-pill-secondary">
            Annuler
        </a>
    </div>
</form>
@endsection
