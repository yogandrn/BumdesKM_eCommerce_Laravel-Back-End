<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Mobile\Product;
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
        $products = Product::where('deleted_at', null)->orderBy('title', 'asc')->get();
        if ($products) {
            $response = [];
            foreach($products  as $product) {
                
                    $data['id'] = $product->id;
                    $data['title'] = $product->title;
                    $data['description'] = $product->description;
                    $data['materials'] = $product->materials;
                    $data['price'] = intval($product->price);
                    $data['stock'] = intval($product->stock);
                    $data['sold'] = intval($product->sold);
                    $data['weight'] = intval($product->weight);
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
        $product = Product::find($id);
        if ($product) {
            $data['id'] = $product->id;
                    $data['title'] = $product->title;
                    $data['description'] = $product->description;
                    $data['materials'] = $product->materials;
                    $data['price'] = intval($product->price);
                    $data['stock'] = intval($product->stock);
                    $data['sold'] = intval($product->sold);
                    $data['weight'] = intval($product->weight);
                    $data['image'] = $product['gallery'][0]['image'];
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
        $products = Product::where('deleted_at', null)->orderBy('sold', 'desc')->limit(5)->get();
        // return response()->json($products, 200);
        if ($products) {
            $response = [];
            foreach($products  as $product) {
                
                $data['id'] = $product->id;
                $data['title'] = $product->title;
                $data['description'] = $product->description;
                $data['materials'] = $product->materials;
                $data['price'] = intval($product->price);
                $data['stock'] = intval($product->stock);
                $data['sold'] = intval($product->sold);
                $data['weight'] = intval($product->weight);
                $data['image'] = $product['gallery'][0]['image'];
                array_push($response, $data);
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['data' => null], 401);

        }
    }
    
    public function getNewProducts()
    {
        $products = Product::where('deleted_at', null)->where('created_at', '>', \Carbon\Carbon::now()->subDays(10))->get();
        // return response()->json($products, 200);
        if ($products) {
            $response = [];
            foreach($products  as $product) {
                
                $data['id'] = $product->id;
                $data['title'] = $product->title;
                $data['description'] = $product->description;
                $data['materials'] = $product->materials;
                $data['price'] = intval($product->price);
                $data['stock'] = intval($product->stock);
                $data['sold'] = intval($product->sold);
                $data['weight'] = intval($product->weight);
                $data['image'] = $product['gallery'][0]['image'];
                array_push($response, $data);
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['data' => null], 401);

        }
    }

    public function getAllBestSeller()
    {
        $products = Product::where('deleted_at', null)->orderBy('sold', 'desc')->get();
        // return response()->json($products, 200);
        if ($products) {
            $response = [];
            foreach($products  as $product) {
                
                $data['id'] = $product->id;
                $data['title'] = $product->title;
                $data['description'] = $product->description;
                $data['materials'] = $product->materials;
                $data['price'] = intval($product->price);
                $data['stock'] = intval($product->stock);
                $data['sold'] = intval($product->sold);
                $data['weight'] = intval($product->weight);
                $data['image'] = $product['gallery'][0]['image'];
                array_push($response, $data);
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['data' => null], 401);

        }
    }
    
    public function searching($keyword) {
        try {
            $products = Product::where('title', 'LIKE', '%' . $keyword . '%', 'OR', 'materials', 'LIKE', '%' . $keyword . '%')->where('deleted_at', null)->orderBy('title', 'ASC')->get();
            if (count($products) > 0) {
                $response = [];
            foreach($products  as $product) {
                
                $data['id'] = $product->id;
                $data['title'] = $product->title;
                $data['description'] = $product->description;
                $data['materials'] = $product->materials;
                $data['price'] = intval($product->price);
                $data['stock'] = intval($product->stock);
                $data['sold'] = intval($product->sold);
                $data['weight'] = intval($product->weight);
                $data['image'] = $product['gallery'][0]['image'];
                array_push($response, $data);
            }
            return response()->json($response, 200);
            } else {
                return response()->json(['message' => 'EMPTY'], 400);
            }
        } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            
        }
    }
}
