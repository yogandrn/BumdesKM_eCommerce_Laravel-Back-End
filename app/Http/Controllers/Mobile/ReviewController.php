<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Mobile\Review;
use Exception;
use Illuminate\Http\Request;

class ReviewController extends Controller
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
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }

    public function getReviews()
    {
        function sensor($data){
            $jml = strlen($data);
            $jumlah_sensor=$jml - 3;
            $setelah_angka_ke=$jml-$jumlah_sensor - 1;
         
        //ambil 4 angka di tengah yan akan disensor
        $censored = mb_substr($data, $setelah_angka_ke, $jumlah_sensor);
         
        //pecah kelompok angka pertama dan terakhir
        $pecah=explode($censored,$data);
        
        $hide = "*";
        for($i = 1 ; $i < $jumlah_sensor; $i++ ) {
            $hide .= "*";
        }
         
        //gabung angka perama dan terakhir dengan angka tengah yang telah di sensor
        $hasil=$pecah[0].$hide.$pecah[1];
         
        //tampilkan
        return $hasil;
        }
        try {
            $reviews = Review::orderBy('date', 'desc')->get();
            if (count($reviews) > 0) {
                $response = [];
                foreach ($reviews as $review) {
                    $time = \Carbon\Carbon::parse($review->date)->diffForHumans();
                    $data['id'] = $review->id;
                    $data['id_transaction'] = $review->id_transaction;
                    $data['id_user'] = $review->id_user;
                    $data['username'] = sensor($review->user->username);
                    $data['image'] = $review->user->image;
                    $data['date'] = $review->date;
                    $data['time'] = $time;
                    $data['comment'] = $review->comment;

                    array_push($response, $data);
                }
                return response()->json($response, 200);
            } else {
                return response()->json(['message' => 'EMPTY'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function createReview(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_transaction' => 'required',
                'id_user' => 'required',
                'comment' => 'required',
            ]);
            Review::create([
                'id_transaction' => $request->id_transaction,
                'id_user' => $request->id_user,
                'comment' => $request->comment,
            ]);
            
            return response()->json(['message' => 'SUCCESS'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    
}
