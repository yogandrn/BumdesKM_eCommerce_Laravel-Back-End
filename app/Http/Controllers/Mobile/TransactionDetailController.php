<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Mobile\Product;
use App\Models\Mobile\TransactionDetail;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailTransactionOut  $detailTransactionOut
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetailTransactionOut  $detailTransactionOut
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailTransactionOut  $detailTransactionOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetailTransactionOut  $detailTransactionOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }

    public function detailOrder($id)
    {
        $items = TransactionDetail::where('id_transaction', $id)->get();
        if (count($items) > 0) {
            $response = [];
            foreach ($items as $item) {
                $id_product = $item->id_product;
                $getProduct = Product::where('id', $id_product)->first();
                $title = $getProduct->title;
                $image = $getProduct['gallery'][0]['image'];
                $price = intval($getProduct->price);

                array_push($response, [
                    'id' => $item->id,
                    'id_product' => intval($id_product),
                    'title' => $title,
                    'price' => intval($price),
                    'qty' => intval($item->qty),
                    'subtotal' => intval($item->subtotal),
                    'image' => $image,
                ]);
            }
            return response()->json($response, 200);
            // return response()->json($orders, 200);
        } else {
            return response()->json(['message' => 'EMPTY'], 220);
        }
    }
}
