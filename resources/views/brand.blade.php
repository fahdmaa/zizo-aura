@extends('layouts.app')

@section('title', 'Zizo Aura Maroc — Boutique Cosmétiques & Brumes de Luxe')
@section('meta_description', 'Boutique officielle au Maroc : brumes et soins Sol de Janeiro, Victoria\'s Secret, Rituals. 100% originaux, livraison express 24-48h et paiement à la livraison.')
@section('og_title', 'Zizo Aura Maroc — Boutique Cosmétiques & Brumes de Luxe')
@section('og_description', 'Boutique officielle au Maroc : brumes et soins Sol de Janeiro, Victoria\'s Secret, Rituals. 100% originaux, livraison express 24-48h et paiement à la livraison.')
@section('canonical', url('/'))

@section('content')
    <h1 class="sr-only">Zizo Aura Maroc — Boutique Cosmétiques, Brumes Parfumées & Soins de Luxe Authentiques</h1>
    <x-hero-hismile />

    <!-- Moving Line: Best 8 Discount Offers -->
    <x-products-marquee :products="$top8Discounts ?? $products" />

    <!-- Our Process & How We Deliver -->
    <x-delivery-process />

    <!-- Customer Feedback & Reviews -->
    <x-customer-reviews :reviews="$reviews ?? null" />
@endsection
