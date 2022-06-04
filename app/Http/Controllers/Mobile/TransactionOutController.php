<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\TransactionOut;
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
        $orders = TransactionOut::where('id_user', $id)->get();
        if (count($orders) > 0) {

            $response = [];
            foreach ($orders as $order) {
                $id_trx = $order->id;
                $get = TransactionDetail::where('id_transaction', $id_trx)->first();
                $id_product = $get->id_product;
                $find = Product::where('id', $id_product)->first();
                $image = $find['gallery'][0]['image'];


                $f['id'] = $order->id;
                $f['id_user'] = $order->id_user;
                $f['date'] = Carbon::parse($order->date)->translatedFormat('d M Y');
                // $f['item'] = $gg;
                $f['total'] = $order->total;
                $f['status'] = $order->status;
                $f['image'] = $image;

                array_push($response, $f);

                // array_push($response, [
                //     'id' => $order->id,
                //     'id_user' => $order->id_user,
                //     'date' => $order->date,
                //     'total' => $order->total,
                //     'status' => $order->status,
                //     'resi' => $order->resi,
                //     'image' => $image,
                // ]);
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['message' => 'EMPTY'], 220);
        }
    }

    public function orderInfo($id)
    {
        $order = TransactionOut::find($id);
        if ($order) {
            return response()->json([
                'id' => $order->id,
                'id_user' => $order->id_user,
                'date' => $order->date,
                'recipient' => $order->recipient,
                'address' => $order->address,
                'phone' => $order->phone,
                'subtotal' => $order->subtotal,
                'shipment' => $order->shipment,
                'total' => $order->total,
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
            CURLOPT_POSTFIELDS => "origin=68124&destination=" . $dest . "&weight=" . $weight . "&courier=jne",
            //   CURLOPT_POSTFIELDS => "origin=68124&destination=61473&weight=400&courier=jne",
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
        $cost = $responses['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'];
        $est = $responses['rajaongkir']['results'][0]['costs'][1]['cost'][0]['etd'];
        // echo $response;
        $new_response = array('status' => 'SUCCESS', 'cost' => $cost, 'estimasi' => $est);
        // var_dump($new_response);
        return response()->json($new_response, 200);
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
