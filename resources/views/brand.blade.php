@extends('layouts.app')

@section('content')
    <x-hero-hismile />

    <!-- Moving Line: Best 8 Discount Offers -->
    <x-products-marquee :products="$top8Discounts ?? $products" />

    <!-- Our Process & How We Deliver -->
    <x-delivery-process />

    <!-- Customer Feedback & Reviews -->
    <x-customer-reviews />
@endsection
