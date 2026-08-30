@extends('admin.layout')
@section('title', 'Produits')

@section('content')
<script>
    if (!window.location.hash || window.location.hash === '#dashboard') {
        window.location.hash = '#products';
    }
</script>
<div class="flex items-center justify-center py-20">
    <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-3 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
        <span class="text-xs font-bold text-zinc-400">Chargement des produits...</span>
    </div>
</div>
@endsection
