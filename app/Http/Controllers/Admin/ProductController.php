<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->withTrashed();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'active'   => $query->where('is_active', true)->whereNull('deleted_at'),
                'inactive' => $query->where('is_active', false)->whereNull('deleted_at'),
                'deleted'  => $query->onlyTrashed(),
                default    => null,
            };
        }

        $products   = $query->orderBy('category_id')->orderBy('sort_order')->paginate(25)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        DB::transaction(function () use ($data, $request) {
            $product = Product::create($data);
            $this->syncVariants($product, $request);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load('sizes', 'flavors');

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        DB::transaction(function () use ($data, $request, $product) {
            $product->update($data);
            $this->syncVariants($product, $request);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        $product->delete(); // soft delete

        return back()->with('success', 'Produit archivé.');
    }

    public function restore(int $id)
    {
        Product::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Produit restauré.');
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:255',
            'slug'             => ['nullable', 'string', 'max:255', "unique:products,slug,{$ignoreId}"],
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0|lt:price',
            'image'            => 'required|string|max:500',
            'gallery'          => 'nullable|array',
            'gallery.*'        => 'string|max:500',
            'is_new'           => 'boolean',
            'is_bestseller'    => 'boolean',
            'in_stock'         => 'boolean',
            'is_active'        => 'boolean',
            'stock_quantity'   => 'nullable|integer|min:0',
            'has_sizes'        => 'boolean',
            'has_flavors'      => 'boolean',
            'sort_order'       => 'integer',
        ]);
    }

    private function syncVariants(Product $product, Request $request): void
    {
        // Sizes
        if ($request->has('sizes')) {
            $product->sizes()->delete();
            foreach ($request->sizes as $i => $size) {
                if (! empty($size['label'])) {
                    $product->sizes()->create([
                        'label'      => $size['label'],
                        'price'      => $size['price'] ?? null,
                        'in_stock'   => isset($size['in_stock']),
                        'sort_order' => $i,
                    ]);
                }
            }
        }

        // Flavors
        if ($request->has('flavors')) {
            $product->flavors()->delete();
            foreach ($request->flavors as $i => $flavor) {
                if (! empty($flavor['label'])) {
                    $product->flavors()->create([
                        'label'      => $flavor['label'],
                        'color_hex'  => $flavor['color_hex'] ?? null,
                        'in_stock'   => isset($flavor['in_stock']),
                        'sort_order' => $i,
                    ]);
                }
            }
        }
    }
}
