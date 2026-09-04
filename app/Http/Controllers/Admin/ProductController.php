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
            $like = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('name', $like, '%' . $request->search . '%');
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
        $data['slug'] = ! empty($data['slug']) ? Str::slug($data['slug']) : $this->generateUniqueSlug($data['name']);
        $data['image'] = \App\Services\ImageOptimizer::optimizeBase64($data['image']);
        if (isset($data['gallery'])) {
            $data['gallery'] = \App\Services\ImageOptimizer::optimizeGallery($data['gallery']);
        }

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
        $data['slug'] = ! empty($data['slug']) ? Str::slug($data['slug']) : $this->generateUniqueSlug($data['name'], $product->id);
        if (isset($data['image'])) {
            $data['image'] = \App\Services\ImageOptimizer::optimizeBase64($data['image']);
        }
        if (isset($data['gallery'])) {
            $data['gallery'] = \App\Services\ImageOptimizer::optimizeGallery($data['gallery']);
        }

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

    public function duplicate(Product $product)
    {
        $product->load(['sizes', 'flavors']);

        $duplicated = DB::transaction(function () use ($product) {
            $baseName = preg_replace('/\s*\(\d+\)$/', '', $product->name);
            $i = 1;
            $newName = "{$baseName} (1)";
            $newSlug = Str::slug($newName);

            do {
                $candidateName = "{$baseName} ({$i})";
                $candidateSlug = Str::slug($candidateName);
                $exists = Product::withTrashed()->where(function ($q) use ($candidateName, $candidateSlug) {
                    $q->where('name', $candidateName)->orWhere('slug', $candidateSlug);
                })->exists();

                if (! $exists) {
                    $newName = $candidateName;
                    $newSlug = $candidateSlug;
                    break;
                }
                $i++;
            } while ($i < 1000);

            $data = $product->only([
                'category_id',
                'subtitle',
                'description',
                'ingredients',
                'olfactory',
                'usage',
                'price',
                'discounted_price',
                'image',
                'gallery',
                'badge',
                'badge_color',
                'rating',
                'review_count',
                'is_new',
                'is_bestseller',
                'in_stock',
                'is_active',
                'stock_quantity',
                'has_sizes',
                'has_flavors',
                'sort_order',
            ]);

            $data['name'] = $newName;
            $data['slug'] = $newSlug;
            $data['sort_order'] = ($product->sort_order ?? 0) + 1;

            $newProduct = Product::create($data);

            foreach ($product->sizes as $size) {
                $newProduct->sizes()->create([
                    'label'      => $size->label,
                    'price'      => $size->price,
                    'in_stock'   => $size->in_stock,
                    'sort_order' => $size->sort_order,
                ]);
            }

            foreach ($product->flavors as $flavor) {
                $newProduct->flavors()->create([
                    'label'      => $flavor->label,
                    'color_hex'  => $flavor->color_hex,
                    'in_stock'   => $flavor->in_stock,
                    'sort_order' => $flavor->sort_order,
                ]);
            }

            return $newProduct;
        });

        return redirect()->route('admin.products.edit', $duplicated)
            ->with('success', "Produit \"{$duplicated->name}\" dupliqué avec succès.");
    }

    public function restore(int $id)
    {
        Product::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Produit restauré.');
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'produit';
        $slug = $baseSlug;
        $i = 1;
        while (Product::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }
        return $slug;
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'slug'             => ['nullable', 'string', 'max:255', "unique:products,slug,{$ignoreId}"],
            'description'      => 'nullable|string',
            'ingredients'      => 'nullable|string',
            'olfactory'        => 'nullable|string',
            'usage'            => 'nullable|string',
            'price'            => 'required|numeric|min:0.01',
            'discounted_price' => 'nullable|numeric|min:0|lt:price',
            'image'            => 'required|string',
            'gallery'          => 'nullable|array',
            'gallery.*'        => 'string',
            'badge'            => 'nullable|string|max:100',
            'badge_color'      => 'nullable|string|max:100',
            'rating'           => 'nullable|numeric|min:0|max:5',
            'review_count'     => 'nullable|integer|min:0',
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
                        'in_stock'   => isset($size['in_stock']) ? filter_var($size['in_stock'], FILTER_VALIDATE_BOOLEAN) : false,
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
                        'in_stock'   => isset($flavor['in_stock']) ? filter_var($flavor['in_stock'], FILTER_VALIDATE_BOOLEAN) : false,
                        'sort_order' => $i,
                    ]);
                }
            }
        }
    }
}
