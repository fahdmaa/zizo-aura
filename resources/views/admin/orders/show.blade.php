@extends('admin.layout')
@section('title', 'Détail Commande #' . $order->id)

@section('content')
<script>
    window.location.hash = '#orders/{{ $order->id }}';
</script>
<div class="flex items-center justify-center py-20">
    <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-3 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
        <span class="text-xs font-bold text-zinc-400">Chargement de la commande #{{ $order->id }}...</span>
    </div>
</div>
@endsection
