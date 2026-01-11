<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


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



