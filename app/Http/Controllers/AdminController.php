<?php

namespace App\Http\Controllers;

use App\Models\brands;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image ;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Categorie;
use App\Models\Product;
use App\Models\productImage;
use App\Models\Coupon;
use function PHPUnit\Framework\fileExists;

class AdminController extends Controller
{

    public function index(){
        return view("back.admin.index");
    }

    public function brands(){

        $brands = brands::orderBy("id","desc")->paginate(10);
       $data = [
        "brands" => $brands
       ];
        return view("back.admin.brands",$data);

    }

    public function addBrand(){
        return view("back.admin.add_brand");
    }

    public function addBrandHandler(Request $request){
        $this->validateEditBrand($request,"brands");

        $brand = new brands();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

       if($request->hasFile("image")){
        $file_extension = $request->file("image")->extension();
        $image_name = Carbon::now()->timestamp.".".$file_extension;
        $destination = public_path("uploads/brands");
        $image = $request->file("image");
        $this->generationThumbNailsImage($image,$image_name,$destination);
        $brand->image = $image_name;
       }

        $brand->save();
       return redirect()->route("admin.brands")->with('status',"Brand has ben added succesfully");
    }
    protected function generationThumbNailsImage($image,$image_name,$destination){

        $img = Image::read($image);
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destination."/".$image_name);

    }

    public function editBrand(int $id){


        $brand = brands::findorFail($id);

        // Implementar tratamento de erros ( Criar um exception )


        $data = [
           "brand" => $brand
        ];

        return view("back.admin.edit_brand",$data);
    }

    public function editBrandHandler(Request $request){

        $this->validateEditBrand($request,"brands");
        $brand = brands::findOrFail($request->id);
        // Upload image
        if($request->hasFile("image")){
           if(File::exists(public_path("uploads/brands/".$brand->image))){
                File::delete(public_path("uploads/brands/".$brand->image));
            }
            $file_extension = $request->file("image")->extension();
            $image_name = Carbon::now()->timestamp.".".$file_extension;
            $destination = public_path("uploads/brands");
            $image = $request->file("image");
            $this->generationThumbNailsImage($image,$image_name,$destination);
            $brand->image = $image_name;
        }

         $brand->name = $request->name;
         $brand->slug = Str::slug($request->name);
         $brand->save();
         return redirect()->route("admin.brands")->with("status","Brand update with success");

    }
    private function validateEditBrand(Request $request,string $table ){

        $validator = Validator::make($request->all(), [
              "name" => [
                "required",
                "string",
                "max:255",
                Rule::unique($table,'name')->ignore($request->id),
            ],

            "slug" => [
                "required",
                "string",
                "max:255",
                Rule::unique($table,"slug")->ignore($request->id)
            ],

            "image"=> [
                "nullable",
                "image",
                "mimes:jpg|jpeg|png|webp",
                "max:2048",

            ]
        ]);

    }
    public function deleteBrand(int $id){

        $brand = brands::findOrFail($id);

        if(File::exists(public_path("uploads/brands/".$brand->image))){
                File::delete(public_path("uploads/brands/".$brand->image));
        }

        $brand->delete();

        return redirect()->route("admin.brands")->with("status","Brand deleted with success");

    }

    public function categories(){
        $categories = Categorie::all();
        $data  = [
            "categories" => $categories
        ];

        return view("back.admin.categories",$data);
    }
    public function addCategory(){

        return view("back.admin.add_categorie");

    }
    public function addCategoryHandler(Request $request){

        $this->validateEditBrand($request,"categories");
        $categorie = new Categorie();
        $categorie->name = $request->name;

        $categorie->slug = Str::slug($request->name);
        if($request->hasFile("image")){
            $file_extension = $request->file("image")->extension();
            $image_name = Carbon::now()->timestamp.".".$file_extension;
            $destination = public_path("uploads/categories");
            $image = $request->file("image");
            $this->generationThumbNailsImage($image,$image_name,$destination);

            $categorie->image = $image_name;
        }
        $categorie->save();

        return redirect()->route("admin.categories")->with('status',"Category has ben added succesfully");
    }
    public function editCategory(int $id){


        $category = Categorie::findorFail($id);

        // Implementar tratamento de erros ( Criar um exception )


        $data = [
           "category" => $category
        ];

        return view("back.admin.edit_category",$data);
    }
    public function editCategoryHandler(Request $request){

        $this->validateEditBrand($request,"categories");
        $category = Categorie::findOrFail($request->id);
        // Upload image
        if($request->hasFile("image")){
           if(File::exists(public_path("uploads/categories/".$category->image))){
                File::delete(public_path("uploads/categories/".$category->image));
            }
            $file_extension = $request->file("image")->extension();
            $image_name = Carbon::now()->timestamp.".".$file_extension;
            $destination = public_path("uploads/categories");
            $image = $request->file("image");
            $this->generationThumbNailsImage($image,$image_name,$destination);
            $category->image = $image_name;
        }

         $category->name = $request->name;
         $category->slug = Str::slug($request->name);
         $category->save();
         return redirect()->route("admin.categories")->with("status","Category update with success");

    }


    public function deleteCategory(int $id){

        $category = Categorie::findOrFail($id);

          if(File::exists(public_path("uploads/categories/".$category->image))){
                File::delete(public_path("uploads/categories/".$category->image));
        }

        $category->delete();


        return redirect()->route("admin.categories")->with("status","Category deleted with sucess");

    }

    public function products(){

        $products = Product::orderBy("id","desc")->paginate(10);
        $data =
        [
            "products" => $products
        ];

        return view("back.admin.products",$data);
    }

    public function Addproducts(){
       $categories = Categorie::orderBy('name', 'asc')->get();
       $brands =brands::orderBy("name","asc")->get();
       $data =
       [
          "categories" => $categories,
          "brands" => $brands

       ];

        return view("back.admin.add_product",$data);

    }

    public function addProductHandler(Request $request){

        $this->validateInputProduct($request,"products");
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile("image")){
          $image = $request->file("image");
          $image_name = $current_timestamp.".".$image->extension();
          $this->generateProductThubNailsImage($image,$image_name);
         $product->image = $image_name;

        }
        $product->save();

        return redirect()->route("admin.products")->with("status","Product has ben added successfully");

    }

    public function generateProductThubNailsImage($image,$imageName){

        $destinationPathThumbNails = public_path("uploads/products/thumbnails");
        $destinationPath = public_path("uploads/products");

        $img = Image::read($image);

        $img->cover(540,689,"top");
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath."/".$imageName);

          $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbNails."/".$imageName);

    }
    public function validateInputProduct(Request $request,$table){

        $validator = Validator::make($request->all(), [
              "name" => [
                "required",
                "string",
                "max:255",
            ],

            "slug" => [
                "required",
                "string",
                "max:255",
                Rule::unique($table,"slug")->ignore($request->id)
            ],
            "short_description"=> [
                "required",
            ],

            "description"=> [
                "required",
            ],

            "regular_price"=> [
                "required",
            ],

            "SKU"=> [
                "required",
            ],

            "stock_status"=> [
                "required",
            ],

            "featured"=> [
                "required",
            ],

            "quantity"=> [
                "required",
            ],

            "image"=> [
                "nullable",
                "image",
                "mimes:jpg|jpeg|png|webp",
                "max:2048",
            ],

            "category_id"=> [
                "required",
            ],

            "brand_id"=> [
                "required",
            ],

        ]);
    }
    public function editProduct(int $id){

        $product = Product::findOrFail($id);
        $categories = Categorie::orderBy("name","asc")->get();
        $brands = brands::orderBy("name","asc")->get();
        $data = [

            "product" => $product,
            "categories"=> $categories,
            "brands" => $brands

        ];

        return view("back.admin.edit_product",$data);


    }

    public function editProductHandler(Request $request){

        $this->validateInputProduct($request,"products");

        $product = Product::findOrFail($request->id);

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if($request->hasFile("image")){

            if(File::exists(public_path("uploads/products/".$product->image))){
                File::delete(public_path("uploads/products/".$product->image));
            }
            if(File::exists(public_path("uploads/products/thumbnails/".$product->image))){
                File::delete(public_path("uploads/products/thumbnails/".$product->image));

            }
              $image_extension = $request->file("image")->extension();
              $image_name = Carbon::now()->timestamp.".".$image_extension;
              $this->generateProductThubNailsImage($request->file("image"),$image_name);
              $product->image = $image_name;
        }


        $product->save();
        return redirect()->route("admin.products")->with("status","Produto atualizado com sucesso!");

    }
    public function productImages(int $id){

        $product = Product::find($id);

        $images =$product->imagesGallery;


        $data =
        [
            "images" => $images,
             "product" => $product
        ];
        return view("back.admin.product_images",$data);
    }

    public function productImageDelete( int $id ,Request $request){

        $image = ProductImage::find($id);

        if($image){

            if(File::exists(public_path("uploads/products/".$image->name))){

                File::delete(public_path("uploads/products/".$image->name));
            }
            if(File::exists(public_path("uploads/products/thumbnails/".$image->name))){

                File::delete(public_path("uploads/products/thumbnails/".$image->name));
            }
        }
        $image->delete();
        return redirect()->route('admin.product-images', ['id' => $request->product_id])->with("status","Image deleted with success");
    }

    public function addProductImage(Request $request ,int $id){


        if($request->hasFile("images")){

            $current_timestamp = Carbon::now()->timestamp;
            $array_images = [];
            $counter = 1;


            if($request->hasFile("images")){

                $permited_extensions = ["jpg","jpeg","png","webp"];

                $images = $request->file("images");

                foreach ( $images as $image) {

                    $extension = $image->getClientOriginalExtension();
                    $gcheck = in_array($extension,$permited_extensions);
                    if($gcheck){

                        $image_name = $current_timestamp."-".$counter.".".$extension;
                        $this->generateProductThubNailsImage($image,$image_name);
                        array_push($array_images,$image_name);
                        $counter += 1;
                    }
                }
            }

            // dd($array_images);
             $rows = [];

            foreach ($array_images as $image) {
                $rows[] = [
                    'product_id' => $id,
                    'image' => $image,
                ];
            }

            ProductImage::insert($rows);


            return redirect()->route("admin.product-images",["id" => $id])->with("status","Image has ben added successfully");

        }

        return redirect()->route("admin.product-images",["id" => $id])->with("error-image","Nenhum arquivo encontrado");



    }

    public function deleteProduct(int $id){

        $product = Product::find($id);

        if($product){
            $images = $product->imagesGallery;
            $principal_image = $product->image;

            if($principal_image){
               if(File::exists(public_path("uploads/products/".$principal_image))){
                    File::delete(public_path("uploads/products/".$principal_image));
                }
                 if(File::exists(public_path("uploads/products/thumbnails/".$principal_image))){
                    File::delete(public_path("uploads/products/thumbnails/".$principal_image));
                }
            }

            if(count($images) > 0 ){
                foreach($images as $image){
                if(File::exists(public_path("uploads/products/".$image->image))){
                    File::delete(public_path("uploads/products/".$image->image));
                }
                 if(File::exists(public_path("uploads/products/thumbnails/".$image->image))){
                    File::delete(public_path("uploads/products/thumbnails/".$image->image));
                }
            }
            }

            $product->delete();
            return redirect()->route("admin.products")->with("status","Product has ben deleted successfully");

        }else{
            return redirect()->route("admin.products")->with("error","Product not find");
        }
    }


    public function addProductPrincipalImage(Request $request,int $id){

        $request->validate(
            [
                "image" => "required|image|mimes:jpg,jpeg,png,webp||max:2048"
            ]
        );

        $product = Product::find($id);
        $old_image = $product->image;

        if($request->hasFile("image")){

            if(File::exists(public_path("uploads/products/".$old_image))){
                File::delete(public_path("uploads/products/".$old_image));
            }
            if(File::exists(public_path("uploads/products/thumbnails/".$old_image))){
                File::delete(public_path("uploads/products/thumbnails/".$old_image));
            }


            $image_extension = $request->file("image")->extension();
            $image_name = Carbon::now()->timestamp.".".$image_extension;
            $image = $request->file("image");
            $this->generateProductThubNailsImage($image,$image_name);
            $product->image = $image_name;
            $product->save();
            return redirect()->route("admin.product-images",["id"=>$product->id])->with("status","Imagem atualizada com sucesso");
        }

        return redirect()->route("admin.product-images",["id"=>$product->id])->with("error","Não foi possível fazer upload da imagem.");
    }


  // ===================================================== Coupons Routes ===================================================================



    public function coupons(){
        $coupons = Coupon::orderBy("expiry_date","DESC")->paginate(12);
        $data = [
            "coupons" => $coupons
        ];
        return view("back.admin.Coupons",$data);

    }




}
