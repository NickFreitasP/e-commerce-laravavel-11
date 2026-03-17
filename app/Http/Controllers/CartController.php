<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
    public function index(){

        $cart = Cart::instance("cart")->content();
        $data = ["items" => $cart];

        return view("front.cart",$data);

    }

    public function addItemCart(Request $request){

      Cart::instance("cart")->add($request->id,$request->name,$request->quantity,$request->price)->associate("App\Models\Product");


      return redirect()->back();


    }

    public function incrementCartQuantity($rowId){

     $product = Cart::instance("cart")->content()->get($rowId);
     $quantity = $product->qty + 1;
     Cart::instance("cart")->update($rowId,$quantity);
     return redirect()->back();
    }

    public function decrementCartQuantity($rowId){
        $product = Cart::instance("cart")->content()->get($rowId);
        $quantity = $product->qty - 1;
        Cart::instance("cart")->update($rowId,$quantity);
        return redirect()->back();
    }

    public function deleteItemCart($rowId){
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function emptyCart(){
        Cart::instance("cart")->destroy();
        return redirect()->back();
    }

}
