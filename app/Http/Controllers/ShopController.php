<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\brands;
use App\Models\Categorie;

class ShopController extends Controller
{

    public function shop(Request $request)
    {

        $size = $request->query("size") ? $request->query("size") : 12;
        $order = $request->query("order") ? $request->query("order") : -1;
        $o_column = "";
        $o_order = "";
        $f_brands = $request->query("brands");
        $f_category = $request->query("category");

        $max_price = $request->query("max_price") ? $request->query("max_price") : 1500;
        $min_price= $request->query("min_price") ?  $request->query("min_price") : 1;



        switch ($order) {
            case 1:
                $o_column = "created_at";
                $o_order = "desc";
                break;
            case 2:
                $o_column = "created_at";
                $o_order = "asc";
                break;
            case 3:
                $o_column = "sale_price";
                $o_order = "asc";
                break;
            case 4:
                $o_column = "sale_price";
                $o_order = "desc";
                break;
            default:
                $o_column = "id";
                $o_order = "desc";
                break;
        }

        $products = Product::query()
            ->when($f_brands, function ($query) use ($f_brands) {
                $query->whereIn("brand_id", explode(",", $f_brands));
            })
            ->when($f_category, function ($query) use ($f_category) {
                $query->whereIn("category_id", explode(",", $f_category));
            })
            ->when(function ($query) use ($max_price,$min_price) {
                $query->whereBetween("regular_price",[$min_price,$max_price]);
                $query->orWhereBetween("sale_price",[$min_price,$max_price]);
            })

            ->orderBy($o_column, $o_order)
            ->paginate($size);

        $brands = brands::orderBy("name", "asc")->get();
        $categories = Categorie::orderBy("name", "asc")->get();
        $data =
            [
                "products" => $products,
                "categories" => $categories,
                "brands" => $brands,
                "size" => $size,
                "order" => $order,
                "f_brands" => $f_brands,
                "f_category" => $f_category,
                "max_price" => $max_price,
                "min_price" => $min_price

            ];

        //   dd($max_price,$min_price);
        return view("front.shop", $data);
    }

    public function shopDetails(string $slug)
    {

        $product = Product::where("slug", $slug)->first();

        $releted_products = Product::where("slug", "<>", $product->slug)->where("category_id", $product->category->id)->take(8)->get();

        $data =
            [
                "product" => $product,
                "releted_products" => $releted_products

            ];

        return view("front.shop_details", $data);
    }
}
