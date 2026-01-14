<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    // Display a listing of the resource.
    public function index()
    {
        $user_id = auth()->user()->id;
        $products = Product::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 200,
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:80',
            'price' => 'required|numeric',
        ]);

        $data['user_id'] = auth()->user()->id;
        
        if($request->hasFile('image_url')){
            $request->file('image_url')->store("products",'public');
        };

        Product::created($data);

        return response()->json([
            'status' => 201,
            'success' => true,
            'message' => 'Product created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
