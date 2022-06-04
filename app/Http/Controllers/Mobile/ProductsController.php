<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # code
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        # code..
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
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

    public function getAllProduct()
    {
        $products = Product::all();
        if ($products) {
            $response = [];
            foreach($products  as $product) {
                
                    $data['id'] = $product->id;
                    $data['title'] = $product->title;
                    $data['description'] = $product->description;
                    $data['materials'] = $product->materials;
                    $data['price'] = $product->price;
                    $data['stock'] = $product->stock;
                    $data['sold'] = $product->sold;
                    $data['weight'] = $product->weight;
                    $data['image'] = $product['gallery'][0]['image'];
                    // 'id' => $product->id,
                    // 'id' => $product->id,
                array_push($response, $data);
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['data' => null], 401);

        }
    }

    public function getDetailProduct($id)
    {
        // $data = Product::where('id', $id)->get();
        $data = Product::find($id);
        if ($data) {
        return response()->json(
            $data,
        //     [
        //     // 'id' => $data->id,
        //     'title' => $data['title'],
        //     'description' => $data->description,
        //     'materials' => $data->materials,
        //     'price' => $data->price,
        //     'stock' => $data->stock,
        //     'weight' => $data->weight,
        //     'sold' => $data->sold,
        //     'image' => $data->image,
        // ], 
        200); 
        } else {
            return response()->json(['data' => null], 401); 

        }
    }

    public function getBestSeller()
    {
        $products = Product::orderBy('sold', 'desc')->limit(5)->get();
        // return response()->json($products, 200);
        if ($products) {
            $response = [];
            foreach($products  as $product) {
                
                    $data['id'] = $product->id;
                    $data['title'] = $product->title;
                    $data['description'] = $product->description;
                    $data['materials'] = $product->materials;
                    $data['price'] = $product->price;
                    $data['stock'] = $product->stock;
                    $data['sold'] = $product->sold;
                    $data['weight'] = $product->weight;
                    $data['image'] = $product['gallery'][0]['image'];
                    // 'id' => $product->id,
                    // 'id' => $product->id,
                array_push($response, $data);
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['data' => null], 401);

        }
    }
}
