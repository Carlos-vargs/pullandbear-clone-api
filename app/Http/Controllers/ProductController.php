<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        $products = $this->findProducts($request->gender, $request->category);

        return ProductResource::collection($products);
    }


    public function store(StoreProductRequest $request)
    {

        $validatedFields = $request->safe()->except(['image']);

        $validatedImages = $request->safe()->only(['image']);

        $product = Product::create($validatedFields);

        if ($product) {
            foreach ($validatedImages as $images) {
                foreach ($images as $img) {
                    $image = $img->store('product', 'public');
                    $product->productImages()->create(compact('image'));
                }
            }
        }

        return ProductResource::make($product);
    }


    protected function findProducts($gender, $category)
    {      

        return ($category == 'clothing')
            ? Product::whereGender($gender)->get()
            : Category::whereName($category)
            ->with(['products' => fn ($query) => $query->whereGender($gender)])
            ->get()
            ->pluck('products')
            ->collapse();
    }
}
