<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishListController;
use app\Http\Controllers\CouponController;

Auth::routes();

// ROUTE HOME
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::controller(ShopController::class)->group(function(){

    Route::get("/shop","shop")->name("shop");

    Route::get("/shop/{slug}","shopDetails")->name("shop_details");
});

Route::controller(WishListController::class)->group(function(){

   Route::get("/wishlist","index")->name("wishlist");

   Route::post("/wishlist/add","addToWishList")->name("add-wish-list");

   Route::delete("/wishlist/remove-item/{rowId}","removeWishlistItem")->name("wishlist-remove-item");

   Route::delete("/wishlist/empty-wishlist","emptyWishlist")->name("wishlist-empty");

   Route::post("/wishlist/move-to-cart/{rowId}","moveToCart")->name("wishlist-move-to-cart");



});




Route::controller(CartController::class)->group(function(){

    Route::get("/cart","index")->name("cart");

    Route::post("/add-item-cart","addItemCart")->name("add-item-cart");

    Route::put("increment-cart-quantity/{rowId}","incrementCartQuantity")->name("increment-cart");

    Route::put("decrement-cart-quantity/{rowId}","decrementCartQuantity")->name("decrement-cart");

    Route::delete("delete-item-cart/{rowId}","deleteItemCart")->name("delete-item-cart");

    Route::delete("empty-cart","emptyCart")->name("empty-cart");
});

// Login Route User/Admin routes
Route::middleware("auth",AdminMiddleware::class)->group(function(){

    // Admin Routes
    Route::controller(AdminController::class)->group(function(){

        Route::prefix("admin")->group(function(){

            // Dash index admin
            Route::get("/index","index")->name("admin.index");

            // ===================================================== Brand Routes ===================================================================

            // Page all brands
            Route::get("/brands","brands")->name("admin.brands");

            // Page add new brand
            Route::get("/new-brand","addBrand")->name("admin.new-brand");

            Route::post("/new-brand","addBrandHandler")->name("admin.new-brand-handler");

            Route::get("/edit-brand/{id}","editBrand")->name("admin.edit-brand");

            Route::post("/edit-brand-handler/{id}","editBrandHandler")->name("admin.edit-brand-handler");

            Route::delete("/delete-brand/{id}","deleteBrand")->name("admin.delete-brand");

            // ===================================================== End Brand Routes ===================================================================


            // ===================================================== Categories Routes ===================================================================

            Route::get("/categories","categories")->name("admin.categories");

            Route::get("/new-category","addCategory")->name("admin.add-category");

            Route::post("/new-category","addCategoryHandler")->name("admin.new-category-handler");

            Route::get("/edit-category/{id}","editCategory")->name("admin.edit-category");

            Route::post("/edit-category/{id}","editCategoryHandler")->name("admin.edit-category-handler");

            Route::delete("/delete-category/{id}","deleteCategory")->name("admin.delete-category");

            // ===================================================== End Categories Routes ===================================================================


            // ===================================================== Product Routes ===================================================================

            Route::get("/products","products")->name("admin.products");

            Route::get("/add-products","Addproducts")->name("admin.add-product");

            Route::post("/add-product","addProductHandler")->name("admin.add-product-handler");

            Route::get("/edit-product/{id}","editProduct")->name("admin.edit-product");

            Route::post("/edit-product","editProductHandler")->name("admin.edit-product-handler");

            Route::get("/product-images/{id}","productImages")->name("admin.product-images");

            Route::delete("/product-image-destroy/{id}","productImageDelete")->name("admin.product.image.destroy");

            Route::post("/add-product-image/{id_product}","addProductImage")->name("admin.add-product-image");

            Route::delete("/delete-product/{id}","deleteProduct")->name("admin.delete-product");

            Route::post("/add-product-principal-image/{id}","addProductPrincipalImage")->name("admin.add-product-principal-image");



            // ===================================================== END  Product Routes ===================================================================



            // ===================================================== Coupons Routes ===================================================================

            Route::get("/coupons","coupons")->name("admin.coupons");

            Route::get("/add-coupon","addCoupon")->name("admin.coupons.add");

            Route::post("/add-coupon-handler","addCouponHandler")->name("admin.coupons.add.handler");

            // Edit coupon view
            Route::get("/edit-coupon/{id}","editCoupon")->name("admin.coupons.edit");

            // Edit coupon handler
            Route::post("/edit-coupon-handler","editCouponHandler")->name("admin.coupons.edit.handler");


            // Delete coupon
            Route::delete("/delete-coupon","deleteCoupon")->name("admin.copouns.delete");



        });


    });
});
Route::middleware("auth")->group(function(){
    // // User Routes
    Route::controller(UserController::class)->group(function(){

        Route::prefix("user")->group(function(){

            // Dash index user
            Route::get("/index","index")->name("user.index");

        });

    });
});



