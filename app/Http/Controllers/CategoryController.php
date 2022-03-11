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

    public function index(Request $request)
    {

        (sizeof($request->query) < 2)
            ? $categories = Category::all()
            : $categories =  $this->findCategories($request->gender, $request->category);


        return CategoryResource::collection($categories);
    }


    public function store(StoreCategoryRequest $request)
    {

        $category = Category::create($request->validated());

        return CategoryResource::make($category);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {

        $category = Category::findOrFail($id);

        $category->update($request->validated());

        return CategoryResource::make($category);
    }

    protected function findCategories($gender, $category)
    {

        $products = Product::whereGender($gender)
            ->get()
            ->pluck('category_id')
            ->unique();

        return ($category == 'clothing')
            ? Category::findOrFail($products)
            ->where('name', '!=', 'shoes')
            ->where('name', '!=', 'bags')
            : Category::findOrFail($products);
    }
}
