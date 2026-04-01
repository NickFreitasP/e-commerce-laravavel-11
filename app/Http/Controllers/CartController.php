<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session ;

class CartController extends Controller
{
    public function index(){

        $cart = Cart::instance("cart")->content();
        $data = ["items" => $cart];
    //   dd(Session::get("discounts"),$cart);
        // dd($cart);
        return view("front.cart",$data);

    }

    public function addItemCart(Request $request){

      Cart::instance("cart")->add($request->id,$request->name,$request->quantity,$request->price)->associate("App\Models\Product");
      if(Session::has("discounts")){
         $this->calculateDiscount();
      }


      return redirect()->back();


    }

    public function incrementCartQuantity($rowId){

     $product = Cart::instance("cart")->content()->get($rowId);
     $quantity = $product->qty + 1;
     Cart::instance("cart")->update($rowId,$quantity);

     if(Session::has("discounts")){
        $this->calculateDiscount();
     }

     return redirect()->back();
    }

    public function decrementCartQuantity($rowId){
        $product = Cart::instance("cart")->content()->get($rowId);
        $quantity = $product->qty - 1;
        Cart::instance("cart")->update($rowId,$quantity);
        if(Session::has("discounts")){
          $this->calculateDiscount();
        }

        return redirect()->back();
    }

    public function deleteItemCart($rowId){
        Cart::instance('cart')->remove($rowId);
        if(Session::has("discounts")){
            $this->calculateDiscount();
        }
        return redirect()->back();
    }

    public function emptyCart(){
        Cart::instance("cart")->destroy();
        if(Session::has("discounts")){
            $this->removeCouponCart();
        }

        return redirect()->back();


    }



    // ===================================================== Coupon routes ===================================================================



    public function apllyCouponCode(Request $request){

        $coupon = $request->coupon_code;

        if(isset($coupon)){

          $coupon = Coupon::where("code",$coupon)->where("expiry_date",">=",Carbon::today())
                ->where("cart_value","<=",Cart::instance("cart")->subtotal())->first();

           if(!$coupon){
            return redirect()->back()->with("error","Inválid Coupon!");
           }
           else{
              Session::put("coupon",[
                "code" => $coupon->code,
                "type" => $coupon->type,
                "value" => $coupon->value,
                "cart_value" => $coupon->cart_value
              ]);

            $this->calculateDiscount();
            return redirect()->back()->with("success","Coupon applied with success!");

           }
        }
        else{

          return redirect()->back()->with("error","Inválid coupon");
        }
    }


    public function calculateDiscount(){

       $discount = 0 ;
      if(Session::has("coupon")){
       $subtotal =(float) str_replace(',', '', Cart::instance("cart")->subtotal()) ;
         if(Session::get("coupon")["type"] === "fixed"){

            $discount = Session::get("coupon")["value"];

         }else{
            $discount =$subtotal * Session::get("coupon")["value"]/100;
        }

          $subTotalAfterDiscount = $subtotal  - $discount;
          $taxAfterDiscount = ($subTotalAfterDiscount * config("cart.tax"))/100;
          $totalAfterDiscount = $subTotalAfterDiscount + $taxAfterDiscount;

          Session::put("discounts",[
                 "discount" => number_format(floatval($discount),2,".",""),
                 "subtotal" => number_format(floatval($subTotalAfterDiscount),2,".",""),
                 "tax" => number_format(floatval($taxAfterDiscount),2,".",""),
                 "total" => number_format(floatval($totalAfterDiscount),2,".",""),

                 ]
          );

      }

    }

   public function removeCouponCart(){

     Session::forget("coupon");
     Session::forget("discounts");
     return redirect()->back()->with("success","Coupon has been removed");

   }



}
