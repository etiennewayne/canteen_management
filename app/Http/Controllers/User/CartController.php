<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $offices = Office::orderBy('office',  'asc')->get();
        $role = Auth::user()->role;

        return view('user.my-cart')
            ->with('role', $role)
            ->with('offices', $offices);
    }

    public function getCountCartItems (){
        $user = Auth::user();
        return Cart::where('user_id', $user->user_id)->count();
    }

    public function getCartItems(){

        $user = Auth::user();

        $data = DB::table('carts as a')
            ->join('products as b', 'a.product_id', 'b.product_id')
            ->join('stores as c', 'b.store_id', 'c.store_id')
            ->join('users as d', 'c.user_id', 'd.user_id')
            ->select('a.cart_id', 'a.user_id', 'a.product_id', 'a.qty',
                'b.store_id', 'b.product', 'b.qty as product_qty', 'b.is_inv', 'b.is_available', 'b.time_consume',
                'b.product_price', 'b.product_img_path', 'b.critical_level',
                'c.store', 'c.user_id as owner_id', 'c.owner', 'c.contact_no'
            )
            ->where('is_place_order', 0)
            ->get();

        // $data = Cart::with(['product'])->where('user_id', $user->user_id)
        //     ->where('is_place_order', 0)
        //     ->get();


        return $data;
    }

    public function store(Request $req){

        $user = Auth::user();

        $exist = Cart::where('product_id', $req->product_id)
            ->where('user_id', $user->user_id)
            ->where('is_place_order', 0)
            ->exists();

        if($exist){
            $cart = Cart::where('user_id', $user->user_id)
                ->where('product_id', $req->product_id)
                ->where('is_place_order', 0)
                ->increment('qty', $req->qty);
        }else{
            Cart::create([
                'user_id' => $user->user_id,
                'product_id' => $req->product_id,
                'qty' => $req->qty,
                'price' => $req->price,
                'date_added' => date('Y-m-d')
            ]);
        }




        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function placeCartOrder(Request $req){
        //return $req;

        $user =  Auth::user();

        $req->validate([
            //'delivery_type'=>['required'],
            'carts.*.delivery_type' => ['required']
        ]);

        $user =  Auth::user();


        foreach($req->carts as $item){

            $nTime = date("H:i:s", strtotime($item['est_delivery'])); //convert to date format UNIX

            $prod = Product::find($item['product_id']);
            $cur_qty = $prod->qty;

            if($cur_qty < $item['qty']){
                return response()->json([
                    'errors' => [
                        'stock_over' => ['Remaining quantity is not enough for the quantity of the order.']
                    ]
                ], 422);
            }

            if($cur_qty <= 0){
                return response()->json([
                    'errors' => [
                        'stock_out' => ['Out of stock.']
                    ]
                ], 422);
            }

            $cart = Cart::find($item['cart_id']);
            $cart->is_place_order = 1;
            $cart->save();

            $data = ProductOrder::create([
                'user_id' => $item['owner_id'], //serve as owner of the product
                'customer_id' => $user->user_id, //serve as customer id
                'product_id' => $item['product_id'],
                'price' => ($item['product_price'] * $item['qty']),
                'qty' => $item['qty'],
                'delivery_type' => $item['delivery_type'],
                'date_order' =>date('Y-m-d'),
                'office' => $item['delivery_type'] == 'DELIVER' ? $item['office'] : '',
                'est_delivery' => $nTime
            ]);


            $prod->qty = $prod->qty - $item['qty'];
            $prod->save();

            ProductLog::create([
                'user_id' => $user->user_id,
                'product_id' => $item['product_id'],
                'current_qty' => $cur_qty,
                'qty' => $item['qty'],
                'remarks' => 'Order ' . $item['product']  . ' was placed. Delivery type is .' . $item['delivery_type'] . '.' . $item['delivery_type'] == 'DELIVER' ? 'Deliver at ' . $item['office'] : ''
            ]);
        }


        return response()->json([
            'status' => 'saved'
        ], 200);


        // $req->validate([
        //     'qty' => ['min:1'],
        //     'delivery_type' => ['required'],
        // ],[
        //     'qty.min' => 'Quantity must have atleast 1.'
        // ]);

        // if($req->delivery_type == 'DELIVER'){
        //     $req->validate([
        //         'qty' => ['min:1'],
        //         'delivery_type' => ['required'],
        //         'office' => ['required'],
        //     ],[
        //         'qty.min' => 'Quantity must have atleast 1.'
        //     ]);
        // }



    }

    public function removeFromCarts(Request $req){
        $delete = 0;

        foreach($req->all() as $item){
            if($item['is_place_order'] > 0){
                Cart::destroy($item['cart_id']);
                $delete = 1;
            }
        }
        
        if($delete > 0){
            return response()->json([
                'status' => 'deleted'
            ], 200);
        }
        
    }

    public function removeFromCart($id){
        Cart::destroy($id);

    }




}
