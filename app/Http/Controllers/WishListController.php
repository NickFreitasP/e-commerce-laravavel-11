<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
class WishListController extends Controller

{
  public function index(){
    $items = Cart::instance("wishlist")->content();
    $data = [
      "items" => $items
    ];
    // dd($items);
    return view("front.wishlist",$data);
  }

  public function addToWishList(Request $request){
      Cart::instance("wishlist")->add($request->id,$request->name,$request->quantity,$request->price)->associate("App\Models\Product");
      return redirect()->back();

   }

   public function removeWishlistItem($rowId){
      Cart::instance("wishlist")->remove($rowId);
      return redirect()->back();

   }

   public function emptyWishlist($rowId){
     Cart::instance("wishlist")->destroy($rowId);
     return redirect()->back();
   }
   public function moveToCart($rowId){
    $item = Cart::instance("wishlist")->get($rowId);
    Cart::instance("wishlist")->remove($rowId);
    Cart::instance("cart")->add($item->id,$item->name,$item->qty,$item->price)->associate("App\Models\Product");
    Return redirect()->back();

   }
}
