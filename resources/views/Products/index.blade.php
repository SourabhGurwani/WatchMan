@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    @foreach($products as $product)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <img src="{{ asset('storage/'.json_decode($product->images)[0]) }}" 
             alt="{{ $product->name }}"
             class="w-full h-64 object-cover">
        
        <div class="p-4">
            <h3 class="font-bold text-xl">{{ $product->name }}</h3>
            <p class="text-gray-600">{{ $product->category->name }}</p>
            <p class="text-indigo-600 font-bold mt-2">₹{{ number_format($product->price) }}</p>
            <a href="{{ route('products.show', $product->slug) }}" 
               class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
               View Details
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection