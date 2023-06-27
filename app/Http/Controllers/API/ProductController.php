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

        $size = $product->productSize()->createMany([
            ['size_name' => 'S', 'stock' => $request->stock_s],
            ['size_name' => 'M', 'stock' => $request->stock_m],
            ['size_name' => 'L', 'stock' => $request->stock_l],
        ]);
        if ($product) {

        if ($request->has('image')) {
            foreach ($request->file('image') as $index => $image) {
                $imageName = Str::random(6) . '_' . $image->getClientOriginalName();

                $image->move(public_path('../../wearit-frontend/public/assets/images'), $imageName);
                Image::create([
                    'image' => $imageName,
                    'product_id' => $product->id,
                ]);

                if ($index == 0) {
                    Product::where('id', $product->id)->update([
                        'thumbnail' => $imageName,
                    ]);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Gambar belum dimasukkan!"
            ], 409);
        }

        if ($request->has('asset')) {
            foreach ($request->file('asset') as $asset) {
                $assetName = $asset->getClientOriginalName();
                $asset->move(public_path('../../wearit-frontend/public/assets/3d'), $assetName);

                //split nama file menjadi 2
                $splitFileName = explode("_", $assetName, 2);
                $kategori = $splitFileName[0];
                $ukuran = $splitFileName[1];
                if ($kategori == 'UWPTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 1,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'UWPB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 2,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'UWSTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 3,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'UWSB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 4,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'UWTTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 5,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'UWTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 6,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'NPTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 7,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'NPB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 8,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'NSTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 9,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'NSB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 10,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'NTTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 11,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'NTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 12,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'OWPTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 13,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'OWPB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 14,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'OWSTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 15,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'OWSB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 16,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'OWTTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 17,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'OWTB' and $ukuran == 'UKURAN_S.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 18,
                        'size_id' => $size->firstWhere('size_name', 'S')->id,
                    ]);
                } elseif ($kategori == 'UWPTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 1,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'UWPB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 2,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'UWSTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 3,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'UWSB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 4,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'UWTTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 5,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'UWTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 6,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'NPTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 7,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'NPB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 8,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'NSTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 9,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'NSB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 10,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'NTTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 11,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'NTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 12,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'OWPTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 13,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'OWPB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 14,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'OWSTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 15,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'OWSB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 16,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'OWTTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 17,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'OWTB' and $ukuran == 'UKURAN_M.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 18,
                        'size_id' => $size->firstWhere('size_name', 'M')->id,
                    ]);
                } elseif ($kategori == 'UWPTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 1,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'UWPB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 2,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'UWSTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 3,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'UWSB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 4,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'UWTTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 5,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'UWTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 6,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'NPTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 7,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'NPB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 8,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'NSTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 9,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'NSB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 10,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'NTTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 11,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'NTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 12,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'OWPTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 13,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'OWPB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 14,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'OWSTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 15,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'OWSB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 16,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'OWTTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 17,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                } elseif ($kategori == 'OWTB' and $ukuran == 'UKURAN_L.glb') {
                    Asset::create([
                        'asset' => $assetName,
                        'product_id' => $product->id,
                        'kategori_id' => 18,
                        'size_id' => $size->firstWhere('size_name', 'L')->id,
                    ]);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Gambar belum dimasukkan!"
            ], 409);
        }
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
        $dataProduct =  Product::where('slug', $slug)->with('productSize')->first();
        if ($dataProduct) {
            return response()->json([
                'success' => true,
                'detailProduct'    => $dataProduct,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'data produk tidak ditemukan!',
            ], 201);
        }
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
        // dd($request->all());
        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $updatedProduct = Product::where('id', $id)->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'slug' => Str::slug($request->product_name),
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
                'message' => 'Data produk berhasil diupdate!',
            ], 201);
        }
        return response()->json([
            'success' => false,
        ], 409);
    }

    public function delete($slug)
    {
        $product = Product::where('slug', $slug)->delete();
        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'produk berhasil dihapus!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data produk!',
            ], 201);
        }
    }
}
