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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $params = $request->query();

        $genderParam = $params['gender'];
        $filterParam = $params['filter'];

        if ($filterParam == 'clothing') {
            $result = Product::where('gender', $genderParam)->get();
        } else {
            $result = Category::where('name', $filterParam)
                ->with([
                    'products' => function ($query) use ($genderParam) {
                        $query->where('gender', $genderParam);
                    },
                ])
                ->get()
                ->pluck('products')
                ->collapse();
        }

        return ProductResource::collection($result);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {

        $validatedFields = $request->safe()->except(['image']);

        // $validatedImages = $request->safe()->only(['image']);

        $product = Product::create($validatedFields);

        if ($product) {
            $image = $request->file('image')->store('product', 'public');
            $product->productImages()->create(compact('image'));
        }

        return ProductResource::make($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
