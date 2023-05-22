<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_name'      => 'required',
            'description'      => 'required',
            'price'      => 'required',
            'stock_s' => 'required|integer',
            'stock_m' => 'required|integer',
            'stock_l' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::create([
            'product_name'      => $request->product_name,
            'description'      => $request->description,
            'slug'      => Str::slug($request->product_name),
            'price'      => $request->price,
        ]);

        $product->productSize()->createMany([
            ['size_name' => 'S', 'stock' => $request->stock_s],
            ['size_name' => 'M', 'stock' => $request->stock_m],
            ['size_name' => 'L', 'stock' => $request->stock_l],
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'product'    => $product,
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }

    public function editProduct($slug)
    {
        $dataProduct =  Product::with('productSize')->where('slug', $slug)->first();
        // session()->put('product', $detailProduct);
        return response()->json([
            'success' => true,
            'detailProduct'    => $dataProduct,
        ], 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_name'      => 'required',
            'description'      => 'required',
            'price'      => 'required',
            'stock_s' => 'required|integer',
            'stock_m' => 'required|integer',
            'stock_l' => 'required|integer',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $updatedProduct = Product::where('id', $id)->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        $stockSizeS = Size::where('product_id', $id)->where('size_name', 'S')->update([
            'stock' => $request->stock_s
        ]);
        $stockSizeM = Size::where('product_id', $id)->where('size_name', 'M')->update([
            'stock' => $request->stock_m
        ]);
        $stockSizeL = Size::where('product_id', $id)->where('size_name', 'L')->update([
            'stock' => $request->stock_l
        ]);
        if ($updatedProduct) {
            return response()->json([
                'success' => true,
                'updated_product'    => $updatedProduct,
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }
}
