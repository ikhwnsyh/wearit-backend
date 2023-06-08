<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
        $produk = Product::all();
        if ($produk->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data_produk'    => $produk,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message'    => "Belum ada produk!",
            ], 200);
        }
    }

    public function detailProduct($slug)
    {
        $detailProduct = Product::where('slug', $slug)->with(['image', 'productSize', 'assets' => function ($query) {
            $kategori_id = Auth::user()->body->kategori_id;
            $query->where('kategori_id', $kategori_id);
        }])->firstOrFail();

        if ($detailProduct) {
            return response()->json([
                'success' => true,
                'detailProduct'    => $detailProduct,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => "maaf data tidak ditemukan!",
            ], 201);
        }
        // session()->put('product', $detailProduct);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name'      => 'required||unique:products',
            'description'      => 'required|string|max:500|',
            'price'      => 'required',
            'stock_s' => 'required|integer',
            'stock_m' => 'required|integer',
            'stock_l' => 'required|integer',
            'image' => 'required'
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
        if ($request->has('image')) {
            foreach ($request->file('image') as $image) {
                $imageName = Str::random(6) . '-' . $image->getClientOriginalName();
                $image->move(public_path('product_image'), $imageName);
                Image::create([
                    'image' => $imageName,
                    'product_id' => $product->id,
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Gambar belum dimasukkan!"
            ], 409);
        }

        $kategori_id = $request->kategori_id;
        if ($request->has('asset')) {
            foreach ($request->file('asset') as $asset) {
                $assetName = Str::random(6) . '-' . $asset->getClientOriginalName();
                $asset->move(public_path('product_asset3d'), $assetName);
                Asset::create([
                    'asset' => $assetName,
                    'product_id' => $product->id,
                    'kategori_id' => $kategori_id,
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Gambar belum dimasukkan!"
            ], 409);
        }
        if ($product) {
            return response()->json([
                'success' => true,
                'product'    => Product::where('id', $product->id)->with(['image', 'productSize', 'assets'])->firstOrFail(),
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => "Porduk gagal ditambahkan!"
        ], 409);
    }

    public function editProduct($slug)
    {
        $dataProduct =  Product::with('productSize')->where('slug', $slug)->firstOrFail();
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
