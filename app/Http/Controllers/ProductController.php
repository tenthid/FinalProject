<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
// use App\Models\Category;
// use App\Models\Brand;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validSortColumns = ['product_name', 'price', 'created_at'];
        $validSortDirections = ['asc', 'desc'];
        $query = Product::with(['category', 'brand']);
    
        // Validasi input
        $validator = Validator::make($request->all(), [
            'keyword' => 'nullable',
            'sortBy' => 'nullable|in:' . implode(',', $validSortDirections),
            'orderBy' => 'nullable|in:' . implode(',', $validSortColumns),
            'perPage' => 'nullable|integer',
        ],
        [
            'sortBy.in' => 'nilai sortBy yang diperbolehkan adalah asc dan desc',
            'orderBy.in' => 'nilai yang diberikan tidak ada di field products',
            'perPage.integer' => 'nilai yang diberikan harus angka',
        ]
    );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
    
        $keyword = $validated['keyword'] ?? null;
        $sortBy = $validated['sortBy'] ?? 'asc';
        $orderBy = $validated['orderBy'] ?? 'product_name';
        $perPage = $validated['perPage'] ?? 5;

        
        if ($keyword) {
            $query->where('product_name', 'like', "%{$keyword}%");
        }
    
        $query->orderBy($orderBy, $sortBy);
    
        $products = $query->paginate($perPage);

        return response()->json([$products], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'product_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'brand_id' => 'required|integer',
            'category_id' => 'required|integer',
        ],
        [ 
            'product_name.required' => 'wajib ada, harus berupa teks string, panjang maksimal adalah 50 karakter',
            'price.required' => 'wajib ada',
            'price.numeric' => 'Harga harus berupa angka.',
            'stock.required' => 'wajib ada',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'brand_id.required' => 'wajib ada',
            'brand_id.integer' => 'Id dari brand harus berupa bilangan bulat.',
            'category_id.required' => 'wajib ada',
            'category_id.integer' => 'Id dari category harus berupa bilangan bulat.',
        ]);

        if ($validateData->fails()) {
            return response()->json(['errors' => $validateData->errors()], 422);
        } 
        
        Product::create($validateData->validated());
        return response()->json(['message' => 'Produk berhasil disimpan'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'brand'])->where('id',$id)->get();

        if(count($product) === 0) {
            return response()->json([
                'message' => "Product dengan id $id tidak ditemukan"
            ], 404);
        }

        return response()->json(['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'message' => "Product dengan id $id tidak ditemukan"
            ], 404);
        }

        $validateData = Validator::make($request->all(),[
            'product_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'brand_id' => 'required|integer',
            'category_id' => 'required|integer',
        ],
        [ 
            'product_name.required' => 'wajib ada, harus berupa teks string, panjang maksimal adalah 50 karakter',
            'price.required' => 'wajib ada',
            'price.numeric' => 'Harga harus berupa angka.',
            'stock.required' => 'wajib ada',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'brand_id.required' => 'wajib ada',
            'brand_id.integer' => 'Id dari brand harus berupa bilangan bulat.',
            'category_id.required' => 'wajib ada',
            'category_id.integer' => 'Id dari category harus berupa bilangan bulat.',
        ]);

        if ($validateData->fails()) {
            return response()->json(['errors' => $validateData->errors()], 422);
        } 

        $product->update($validateData->validated());
        return response()->json(['message' => 'Produk berhasil diupdate'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'message' => "Product dengan id $id tidak ditemukan"
            ], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }
}
