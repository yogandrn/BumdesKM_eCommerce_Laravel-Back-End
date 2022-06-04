<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\TransactionOut;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

   public function userLogin(Request $request)
   {
      $credentials = $request->validate([
         'email' => 'required|email:dns|max:255',
         'password' => 'required|max:255'
     ]);

     if (Auth::attempt($credentials)) {
         // $request->session()->regenerate();
         return response()->json(['message' => 'SUCCESS'], 200);
     }

     return response()->json(['message' => 'INVALID'], 401);
   }

   public function userLogout()
    {
      //   $cek = 
        Auth::logout();

      //   request()->session()->invalidate();
      //   request()->session()->regenerateToken();

      //   return redirect('/');
      return response()->json(['message' => 'SUCCESS'], 200);
      // if ($cek) {
      //    return response()->json(['message' => 'SUCCESS'], 200);
      // } else {
      // return response()->json(['message' => 'FAILED'], 401); }
    }

    public function userSignUp(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:4|max:255',
            'username' => 'required|unique:users|min:6|max:255',
            'email' => 'required|email:dns|unique:users|min:6|max:255',
            'phone' => 'required|min:10|max:15',
            'gender' => 'required',
            'roles' => 'required',
            'password' => 'required|min:6|max:255',
            'image' => 'required',
        ]);
 
        // enkripsi password
        $validatedData['password'] = Hash::make($validatedData['password']);
 
        User::create($validatedData);
 
        return redirect('/login')->with('success', 'Registration successfully');        
    }


 public function login(){
  if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
   $user = Auth::user();
   $success['token'] = $user->createToken('appToken')->accessToken;
   return response()->json([
    'success' => true,
    'token' => $success,
    'user' => $user,
    'id' => $user->id,
   ]);
  } else{
   return response()->json([
    'success' => false,
    'message' => 'Invalid Email or Password',
   ], 401);
  }
 }
 public function register(Request $request){
    $validator = Validator::make($request->all(), [
     'name' => ['required', 'string', 'max:255'],
     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
     'password' => ['required', 'string', 'min:8'],
    ]);
    if($validator->fails()){
     return response()->json([
      'success' => false,
      'message' => $validator->errors(),
     ], 401);
    }
    $input = $request->all();
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);
    $success['token'] = $user->createToken('appToken')->accessToken;
    $me = User::where('email', $input['email'])->first();
    return response()->json([
     'success' => true,
     'token' => $success,
     'user' => $user,
     'id' => $me->id,
    ]);
   }

   

   public function logout(Request $request){
    if(Auth::user()){
     $user = Auth::user()->token();
     $user->revoke();
  return response()->json([
      'success' => true,
      'message' => 'Logout successfully',
     ]);
    } else{
     return response()->json([
      'success' => false,
      'message' => 'Unable to Logout',
     ]);
    }
   }

   public function getData($id)
   {
      $data = User::where('id', $id)->first();
      $myId = $data->id;
      $carts = Cart::where('id_user', $myId)->get();
      $orders = TransactionOut::where('id_user', $myId)->get();
      // $cart = Cart::where('id_user', $myId)->get();

      if ( $data ) {
         $subtotal = 0;
         $weight = 0;
         foreach ($carts as $cart) {
                            // $id_cart = $cart->id;
                // $user_id = $cart->id_user;
                $id_product = $cart->id_product;
                $qty = $cart->qty;
                // $subtotal = $cart->subtotal;
                $product= Product::where('id',$id_product)->first();
                $title = $product->title;
                $image = $product->image;
                $price = $product->price;

                $weight += $product->weight;
                $subtotal += $price * $qty;
         }

         return response()->json([
            'id' => $data->id,
            'name' => $data->name,
            'gender' => $data->gender,
            'username' => $data->username,
            'email' => $data->email,
            'phone' => $data->phone,
            'image' => $data->image,
            'carts' => count($carts),
            'orders' => count($orders),
            'wishlists' => count($carts),
            'subtotal' => $subtotal,
            'weight' => $weight,
         ], 200);
      } else {
         return response()->json(['message' => 'FAILED'], 401);
      }
   }

   public function checkPassword(Request $request)
   {
      $validatedData = $request->validate([
         'id_user' => 'required',
         'password' => 'required',
      ]);
      
      $id = $validatedData['id_user']; 
      $user = User::where('id',$id)->first();
      if (Hash::check($validatedData['password'], $user->password )) {
         return response()->json(['message' => 'OK'], 200);
      } else {
         return response()->json(['message' => 'FAILED'], 401);
         
      }
   }
   
   public function changePassword(Request $request) 
   {
      $validatedData = $request->validate([
         'id_user' => 'required',
         'new_password' => 'required',
      ]);
      try {
         User::where('id', $validatedData['id_user'])->update(['password' => bcrypt($validatedData['new_password'])]);
         return response()->json(['message' => 'SUCCESS'], 200);
      } catch (Exception $e) {
         return response()->json(['message' => $e->getMessage()], 500);
      }
   }
}
