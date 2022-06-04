<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            $carts = Cart::where('id_user', $id)->get();
            if (count($carts) > 0 ) {
                return response()->json($carts, 200);
            } else {
                return response()->json(['message' => 'EMPTY'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # code...
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::destroy($id);
        return response()->json(['message' => 'SUCCESS'], 200);
    }

    public function getCarts($id)
    {
        $carts = Cart::where('id_user', $id)
                ->get();
        if (count($carts) > 0) {
            $response = [];
            foreach ($carts as $cart) {
                // $id_cart = $cart->id;
                // $user_id = $cart->id_user;
                $id_product = $cart->id_product;
                // $title = $cart->product->title;
                // $qty = $cart->qty;
                // $subtotal = $cart->subtotal;
                // $product= Product::where('id',$id_product)->first();
                $title = $cart->product->title;
                $image = $cart['product']['gallery'][0]['image'];
                $price = $cart->product->price;

                array_push($response, [
                    'id' => $cart->id,
                    'id_user' => $cart->id_user,
                    'id_product'=>$cart->id_product,
                    'title' => $title,
                    'image' => $image,
                    'price' => $price,
                    'qty' => $cart->qty,
                    'subtotal' => $cart->qty * $price,
                ]);
            }
            return response()->json($response, 200);
        }  else {
            return response()->json(['message' => 'EMPTY'], 220);
        }
        // if ($carts){
        //     // $data =[
        //     //     'id' => $carts->id,
        //     //     'id_user' => $carts->id_user,
        //     //     'product' => $carts->product->title,
        //     // ];
        //     return response()->json($carts, 200);
        // } else {
        //     return response()->json(['data' => null], 401);
        // }
    }

    public function AddToCart(Request $request)
    {
        $ValidatedData = $request->validate([
            'id_user' => 'required',
            'id_product' => 'required',
            'qty' => 'required',
            'subtotal' => 'required',
        ]);

        Cart::create($ValidatedData);

        return response()->json(['message' => 'SUCCESS'], 200);
    }
}
