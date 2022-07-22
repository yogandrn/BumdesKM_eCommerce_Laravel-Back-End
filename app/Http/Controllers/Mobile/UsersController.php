<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Mobile\Cart;
use App\Models\Mobile\Product;
use App\Models\Mobile\TransactionOut;
use App\Models\Mobile\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
//   $success['token'] = $user->createToken('appToken')->accessToken;
   return response()->json([
    'success' => true,
    // 'token' => $success,
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
    try {

       $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:users'],
        'email' => ['required', 'string', 'email:dns', 'max:255', 'unique:users'],
        'phone' => ['required', 'string', 'max:15', 'min:10', 'unique:users'],
        'password' => ['required', 'string', 'min:8'],
       ]);
       if($validator->fails()){
        return response()->json(['success' => false, 'message' => $validator->errors(),
        ], 400);
       }
       $input = $request->all();
       $input['password'] = bcrypt($input['password']);
       $user = User::create($input);
    //   $success['token'] = $user->createToken('appToken')->accessToken;
       $me = User::where('email', $input['email'])->first();
       return response()->json([
        'success' => true,
        // 'token' => $success,
        'user' => $user,
        'id' => $me->id,
       ]);
    } catch (Exception $e) {
      return response()->json(['message' => $e->getMessage()], 400);
    }
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
                $price = intval($product->price);

                $weight += intval($product->weight) * $qty;
                $subtotal += intval($price) * intval($qty);
         }

         return response()->json([
            'id' => $data->id,
            'name' => $data->name,
            'gender' => $data->gender,
            'username' => $data->username,
            'email' => $data->email,
            'phone' => $data->phone,
            'image' => $data->image,
            'carts' => intval(count($carts)),
            'orders' => intval(count($orders)),
            'wishlists' => intval(count($carts)),
            'subtotal' => intval($subtotal),
            'weight' => intval($weight),
         ], 200);
      } else {
         return response()->json(['message' => 'FAILED'], 401);
      }
   }

   public function editProfile(Request $request)
   {
      try {
         User::where('id', $request->id)->first();
         $validated = $request->validate([
            'id' => 'required',
         'name' => 'required|string',
         'gender' => 'required|string',
         'email' => 'required|email:dns|unique:users,id,:id',
         'phone' => 'required|string|min:10|max:15|unique:users,id,:id',
         ]);

         // User::where('id', $request->id)->update([
         //    'name' => $request->name,
         //    'email' => $request->email,
         //    'phone' => $request->phone,
         // ]);

         User::where('id', $request->id)->update($validated);
         return response()->json(['message' => 'SUCCESS'], 200);
      } catch (Exception $e) {
         return response()->json(['message' => $e->getMessage()], 400);
      }  
   }

   public function editPhoto(Request $request)
   {
      try {
         $validated = $request->validate([
            'id_user' => 'required',
            // 'image' => 'required',
            'image' => 'required',
        ]);

        $user = User::where('id', $validated['id_user'])->first();
        $filestoDelete = $user->image;
        if ($request->file('image')) {
            $validated['image'] = $request->file('image')->store('assets/user_profile', 'public');
            if ($filestoDelete != 'assets/user_profile/default_user.png') {
               // File::delete(public_path('storage/'.$user->image));
               // unlink(public_path('storage/'.$user->image));
               Storage::delete(public_path($filestoDelete));
            }
         }
        User::where('id', $validated['id_user'])->update(['image' => $validated['image']]);
        return response()->json(['message' => 'SUCCESS'], 200);
      } catch (Exception $e) {
         return response()->json(['message' => $e->getMessage()], 400);
         
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
