<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->admin_level < 2) {
                return redirect()->route('home')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $products = Product::with('categories')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'cost_acquisition' => 'required|numeric',
            'sale_value' => 'required|numeric',
            'minimum_quantity' => 'required|integer',
            'barcode' => 'required|string|unique:products,barcode',
            'product_image' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $product = new Product($request->only([
            'name',
            'quantity',
            'cost_acquisition',
            'sale_value',
            'minimum_quantity',
            'barcode',
        ]));

        if ($request->hasFile('product_image')) {
            $product->product_image = $request->file('product_image')->store('product_images', 'public');
        }

        $product->save();

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'cost_acquisition' => 'required|numeric',
            'sale_value' => 'required|numeric',
            'minimum_quantity' => 'required|integer',
            'barcode' => 'required|string|unique:products,barcode,' . $product->id,
            'product_image' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $product->fill($request->only([
            'name',
            'quantity',
            'cost_acquisition',
            'sale_value',
            'minimum_quantity',
            'barcode',
        ]));

        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            $product->product_image = $request->file('product_image')->store('product_images', 'public');
        }

        $product->save();

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
