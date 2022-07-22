<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Mobile\Cart;
use App\Models\Mobile\Product;
use App\Models\Mobile\TransactionDetail;
use App\Models\Mobile\TransactionOut;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class TransactionOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
    }

    public function myorder($id)
    {
        $orders = TransactionOut::where('id_user', $id)->with(['detail'])->orderBy('date', 'desc')->get();
        try {
        if (count($orders) > 0) {

            $response = [];
            foreach ($orders as $order) {
                $id_trx = $order->id;
                $get = TransactionDetail::where('id_transaction', $id_trx)->first();
                $id_product = $get->id_product;
                $find = Product::where('id', $id_product)->first();
                $image = $order['detail'][0]['product'][0]['gallery'][0]['image'];


                $f['id'] = $order->id;
                $f['id_user'] = intval($order->id_user);
                $f['date'] = Carbon::parse($order->date)->translatedFormat('d M Y');
                // $f['item'] = $gg;
                $f['total'] = intval($order->total);
                $f['status'] = $order->status;
                $f['image'] = $image;

                array_push($response, $f);
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['message' => 'EMPTY'], 220);
        } } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function orderInfo($id)
    {
        $order = TransactionOut::find($id);
        if ($order) {
            return response()->json([
                'id' => intval($order->id),
                'id_user' => intval($order->id_user),
                'date' => $order->date,
                'recipient' => $order->recipient,
                'address' => $order->address,
                'phone' => $order->phone,
                'subtotal' => intval($order->subtotal),
                'shipment' => intval($order->shipment),
                'total' => intval($order->total),
                'resi' => $order->resi,
                'status' => $order->status,

            ], 200);
        } else {
            return response()->json(['message' => 'FAILED'], 401);
        }
    }

    public function cekOngkir(Request $request)
    {
        $dest = $request->destination;
        $weight = $request->weight;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=68161&destination=" . $dest . "&weight=" . $weight . "&courier=jne",
            //   CURLOPT_POSTFIELDS => "origin=68161&destination=61473&weight=400&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: dbb01554f7fa308466e6dcc8b335378b"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            exit("cURL Error #:" . $err);
            $new_response = (array('status' => 'FAILED'));
        }

        $responses = json_decode($response, true);
        $cost = $responses['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
        $est = $responses['rajaongkir']['results'][0]['costs'][0]['cost'][0]['etd'];
        // echo $response;
        $new_response = array('status' => 'SUCCESS', 'cost' => $cost, 'estimasi' => $est);
        // var_dump($new_response);
        return response()->json($new_response, 200);
        // return response()->json($responses, 200);
    }

    public function order(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required',
            'recipient' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'subtotal' => 'required',
            'shipment' => 'required',
            'total' => 'required',
            'resi' => 'required',
            'status' => 'required',
        ]);

        $id_user = $request->id_user;

        
        $create = TransactionOut::create($validatedData);
        
        if ($create) {
            $carts = Cart::where('id_user', $id_user)->get();
            $get = TransactionOut::where('id_user', $id_user)->orderBy('created_at', 'desc')->first();
            $id_trx = $get->id;

            foreach($carts as $cart) {
                $data = Product::where('id', $cart->id_product)->first();
                $price = $data->price;
                TransactionDetail::create([
                    'id_transaction' => $id_trx,
                    'id_product' => $cart->id_product,
                    'qty' => $cart->qty,
                    'price' => $price,
                    'subtotal' => $price * $cart->qty
                ]);

                $data->stock = $data->stock - $cart->qty;
                $data->sold = $data->sold + $cart->qty;
                $data->save();
                // Product::find($cart->id_product)->

                Cart::destroy($cart->id);
            }

            return response()->json(['message' => 'SUCCESS'], 200);
        } else {
            return response()->json(['message' => 'SUCCESS'], 200);
        }

    }

    public function confirmOrder($id)
    {
        try {
            TransactionOut::where('id', $id)->update(['status' => 'SUCCESS']);
            return response()->json(['message' => 'SUCCESS'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);

        }
    }
    
    public function checkOrder(Request $request) {
        $request->validate([
            'id_user' => 'required'
            ]);
        $array = [];
        $orders = TransactionOut::where('id_user', $request->id_user)->where('status', 'PENDING')->where('date', '<', \Carbon\Carbon::now()->subHours(24))->get();
        if ($orders->count() > 0) {
            foreach ($orders as $order) {
            $theorders = TransactionDetail::where('id_transaction', $order->id )->get();
                foreach ($theorders as $order) {
                    $id_product = intval($order->id_product);
                    $qty = intval($order->qty);
                    $stock = intval($order['product'][0]['stock']);
                    $sold = intval($order['product'][0]['sold']);
                    
                    Product::where('id', $id_product)->update([
                        'stock' => $stock + $qty,    
                        'sold' => $sold - $qty,    
                    ]);
                }
            }
            
            TransactionOut::where('id_user', $request->id_user)->where('status', 'PENDING')->where('date', '<', \Carbon\Carbon::now()->subHours(24))->update(['status' => 'CANCEL']);
            
            return response()->json(['message' => 'SUCCESS'], 200);
        } else {
            return response()->json(['message' => 'EMPTY'], 200);
        }
        
    }
    
    
   
    
    public function reboundStock(Request $request) {
        $validated = $request->validate([
            'id_user' => 'required'
            ]);
        $orders = TransactionOut::where('id_user', $request->id_user)->where('status', 'CANCEL')->with(['detail'])->get();
        if ($orders->count() > 0){
        $array = [];
        foreach($orders as $order) {
                array_push($array, $order['detail']);
        }
        $newdata = [];
        foreach ($array as $item) {
                
                $data['id'] = $item[0]['product'][0]['id'];
                $data['qty'] = intval($item[0]['qty']);
                $data['stock'] = intval($item[0]['product'][0]['stock']);
                $data['sold'] = intval($item[0]['product'][0]['sold']);
                
                array_push($newdata, $data);
        }
        try {
            foreach($newdata as $item) {
                $id = $item['id'];
                $qty = intval($item['qty']);
                $stock = intval($item['stock']);
                $sold = intval($item['sold']);
                Product::where('id', $id)->update([
                    'stock' => $stock + $qty, 
                    'sold' => $sold - $qty, 
                    ]);
            }
            
            return response()->json(['message' => 'SUCCESS'], 200);
        } catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 400); 
        }   
        }
        else {
            return response()->json(['message' => 'EMPTY'], 200);
            
        }
    } 
    
    
    
    
    
    public function test() {
        $orders = TransactionOut::where('id_user', '25')->where('status', 'PENDING')->where('date', '<', \Carbon\Carbon::now()->subHours(24))->get();
        if ($orders->count() > 0) {
            
            return response()->json($orders, 200);
        } else {
            return response()->json(['message' => 'EMPTY'], 400);
        }
        
        
        // $orders = TransactionOut::where('id_user', '8')->where('status', 'CANCEL')->with(['detail'])->get();
        // $array = [];
        // foreach($orders as $order) {
        //         array_push($array, $order['detail']);
        // }
        // $newdata = [];
        // foreach ($array as $item) {
                
        //         $data['id'] = $item[0]['product'][0]['id'];
        //         $data['qty'] = intval($item[0]['qty']);
        //         $data['stock'] = intval($item[0]['product'][0]['stock']);
        //         $data['sold'] = intval($item[0]['product'][0]['sold']);
                
        //         array_push($newdata, $data);
        // }
        // try {
        //     foreach($newdata as $item) {
        //         $id = $item['id'];
        //         $qty = intval($item['qty']);
        //         $stock = intval($item['stock']);
        //         $sold = intval($item['sold']);
        //         Product::where('id', $id)->update([
        //             'stock' => $stock + $qty, 
        //             'sold' => $sold - $qty, 
        //             ]);
        //     }
        //     return response()->json(['message' => 'SUCCESS'], 200); 
            
        // } catch (Exception $e) {
        //     return response()->json(['message' => $e->getMessage()], 400); 
        // }
        
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
