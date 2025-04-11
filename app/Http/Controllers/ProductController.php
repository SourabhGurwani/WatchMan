<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::query()
            ->with('category')
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%'.request('search').'%')
                      ->orWhere('description', 'like', '%'.request('search').'%');
            })
            ->when(request('category'), function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', request('category'));
                });
            })
            ->when(request('min_price'), function ($query) {
                $query->where('price', '>=', request('min_price'));
            })
            ->when(request('max_price'), function ($query) {
                $query->where('price', '<=', request('max_price'));
            })
            ->orderBy(request('sort', 'name'), request('direction', 'asc'))
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');

        return view('products.index', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for creating a new product (Admin).
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:products|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('product-images', 'public');
            }
        }

        Product::create([
            ...$validated,
            'images' => !empty($images) ? json_encode($images) : null,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the product (Admin).
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product (Admin).
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean'
        ]);

        $images = json_decode($product->images, true) ?? [];
        
        // Handle new images
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
            
            // Store new images
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('product-images', 'public');
            }
        }

        $product->update([
            ...$validated,
            'images' => !empty($images) ? json_encode($images) : null,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product (Admin).
     */
    public function destroy(Product $product)
    {
        // Delete associated images
        if ($product->images) {
            foreach (json_decode($product->images) as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}