<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->query();

        if (sizeof($params) != 0) {
            $genderParam = $params['gender'];
            $filterParam = $params['filter'];

            $products = Product::where('gender', $genderParam)
                ->get()
                ->pluck('category_id')
                ->unique();

            if ($filterParam == 'clothing') {
                $categories = Category::findOrFail($products)
                    ->where('name', '!=', 'shoes')
                    ->where('name', '!=', 'bags');
            } else {
                $categories = Category::findOrFail($products);
            }
        } else {
            $categories = Category::all();
        }

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $field = $request->validated();

        $category = Category::create($field);

        return CategoryResource::make($category);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {

        $requestValidated = $request->validated();

        $category = Category::findOrFail($id);

        $category->update($requestValidated);

        return CategoryResource::make($category);

    }

}
