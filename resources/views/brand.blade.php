@extends('layouts.app')

@section('title', 'Sol de Janeiro Maroc — Bum Bum Cream & Brumes Cheirosa Originales | Zizo Aura')
@section('meta_description', 'Découvrez la collection officielle Sol de Janeiro au Maroc : Brazilian Bum Bum Cream, brumes parfumées Cheirosa 68, 62, 59 & 40. Produits 100% authentiques, livraison express partout au Maroc et paiement à la livraison.')
@section('og_title', 'Sol de Janeiro Maroc — Bum Bum Cream & Brumes Cheirosa | Zizo Aura')
@section('og_description', 'Boutique Sol de Janeiro officielle au Maroc. Brumes Cheirosa & soins corps iconiques. Livraison 24-48h partout au Maroc & paiement à la livraison.')
@section('canonical', url('/'))

@section('content')
    <x-hero-hismile />

    <!-- Moving Line: Best 8 Discount Offers -->
    <x-products-marquee :products="$top8Discounts ?? $products" />

    <!-- Our Process & How We Deliver -->
    <x-delivery-process />

    <!-- Customer Feedback & Reviews -->
    <x-customer-reviews :reviews="$reviews ?? null" />
@endsection
