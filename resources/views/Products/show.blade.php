@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="md:flex">
\
        <div class="md:w-1/2 p-4">
            <img src="{{ asset('storage/'.json_decode($product->images)[0]) }}" 
                 alt="{{ $product->name }}"
                 class="w-full rounded-lg">
        </div>
\
        <div class="md:w-1/2 p-6">
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-gray-500 mt-2">{{ $product->category->name }}</p>
            <p class="text-2xl font-bold text-indigo-600 mt-4">₹{{ number_format($product->price) }}</p>
            
            <div class="mt-6">
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>

            <div class="mt-8">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>
@endsection