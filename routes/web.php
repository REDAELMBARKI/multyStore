<?php

use App\Events\UserLogin;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RuleBasedCollectionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StoreConfigController;
use App\Http\Controllers\VariantsController;
use App\Http\Controllers\AttributesController;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\GoogleSheetsController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeLayoutOrcController;
use App\Http\Controllers\StripeWebhookController;
use App\Jobs\WelcomeBack;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Raw;



// auth 
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.login');
   
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');


Route::get('/', [HomeController::class, 'index'])->name('home');
// home layout orchestration
Route::get('/store/home-editor', [HomeLayoutOrcController::class, 'index'])->name('home.editor.index');
Route::post('/store/home-editor/publish', [HomeLayoutOrcController::class, 'publish'])->name('home.editor.publish');

//collecctions
Route::get('/store/collections', [RuleBasedCollectionController::class, 'index'])->name('collections.index');
Route::get('/store/collections/{collection:slug}', [RuleBasedCollectionController::class, 'edit'])->name('collections.edit');
Route::put('/store/collections/{collection:slug}', [RuleBasedCollectionController::class, 'update'])->name('collections.update');
Route::patch('/store/collections/{collection:slug}', [RuleBasedCollectionController::class, 'reorder'])->name('collections.reorder');

// banners
Route::get('/store/banners', [BannerController::class, 'index'])->name('banners.index');
Route::get('/store/banners/{banner:slug}', [BannerController::class, 'edit'])->name('banners.edit');
Route::put('/store/banners/{banner:slug}', [BannerController::class, 'update'])->name('banners.update');

// catalog 
Route::get('/shop', function () {
    return redirect()->route('marketplace.index');
})->name('shop');
Route::get('/about', function () {
    return Inertia::render('AboutPage');
})->name('about');
Route::get('/contact', function () {
    return Inertia::render('ContactPage');
})->name('contact');
Route::get('/blog', function () {
    return Inertia::render('BlogPage');
})->name('blog');


//payment section
// checkout 
Route::post('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
// single product by now 
Route::post('/order/buy-now/now', [OrderController::class , 'store'])->name('order.buynow');
//payment section end



// cart
Route::get('/cart', [CartController::class , 'index'])->name('shoppingCart.index');
Route::post('/cart', [CartController::class , 'store'])->name('cart.store');
Route::patch('/cart/{id}', [CartController::class , 'update'])->name('cart.update');
Route::delete('/cart/clear', [CartController::class , 'clear'])->name('cart.clear');
Route::delete('/cart/{id}', [CartController::class , 'destroy'])->name('cart.destroy');

// checkout steps routes (this fakes the url to make steps work fine)
Route::get('/checkout', [CartController::class, 'index']);
// end fake url 

//shipping
Route::get('/shipping/calculate/{id}' , [ShippingController::class , 'calculate' ])->name('shipping.calculate');
Route::get('/shippings_cities' , [ShippingController::class, 'getCities'])->name('shipping.cities.get') ;


// routes/web.php or api.php
Route::get('/sheetAuth/google/callback', [DriveController::class, 'callBack']);
Route::get('/sheetAuth/google/auth', [DriveController::class, 'auth']);
Route::post('/sheets', [DriveController::class, 'auth'])
->name('googleSheet.create');


Route::get('admin/dashboard', [DashboardController::class, 'overview'])->name("dashboard.overview");

// Admin Coupons
Route::get('admin/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
Route::get('admin/coupons/create', [AdminCouponController::class, 'create'])->name('coupons.create');
Route::post('admin/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
Route::get('admin/coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
Route::put('admin/coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
Route::delete('admin/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

// Admin Promotions
Route::get('admin/promotions', [AdminPromotionController::class, 'index'])->name('promotions.index');
Route::get('admin/promotions/create', [AdminPromotionController::class, 'create'])->name('promotions.create');
Route::post('admin/promotions', [AdminPromotionController::class, 'store'])->name('promotions.store');
Route::get('admin/promotions/{promotion}/edit', [AdminPromotionController::class, 'edit'])->name('promotions.edit');
Route::put('admin/promotions/{promotion}', [AdminPromotionController::class, 'update'])->name('promotions.update');
Route::delete('admin/promotions/{promotion}', [AdminPromotionController::class, 'destroy'])->name('promotions.destroy');

// API routes for products marketing
Route::get('api/promotions', [PromotionController::class, 'getAll'])->name('get.promotions');
Route::get('api/coupons', [CouponController::class, 'getAll'])->name('get.coupons');
// ->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

// products
Route::prefix('products')->group(function(){
    Route::get('' , [ProductController::class, 'index'])->name('products') ;
    Route::get('/drafts' , [ProductController::class, 'drafts'])->name('drafts.index') ;
    Route::get('/create' , [ProductController::class, 'create'])->name('products.create') ;
    Route::get('/{product:id}/edit' , [ProductController::class, 'edit'])->name('product.edit') ;
    // drafts
    Route::post('/drafts' , [ProductController::class, 'storeDraft'])->name('products.storeDraft');
    Route::patch('/{product:id}/publish' , [ProductController::class, 'publish'])->name('product.publish');
    Route::delete('/{product:id}' , [ProductController::class, 'destroy'])->name("product.destroy") ;
    Route::put('/{product}/leave',  [ProductController::class, 'updateOnPageLeave'])->name('draft.save.leave');
    Route::put('/{product:id}/submit', [ProductController::class, 'updateOnSubmit'])->name('draft.save.submit');
    Route::post("/{product:id}/duplicate" , [ProductController::class,"duplicate"])->name("draft.duplicate");
})->can('manage-products');

// Public Product Detail
Route::get('/products/{product:id}', [ProductController::class, 'show'])->name('product.show');

// media section
// store media route
Route::post('/media' , [MediaController::class, 'store'])->name('media.store') ;
// destroy deleted media
Route::delete('/media/{media}', [MediaController::class, 'destroy'])
    ->name('media.destroy');

Route::delete('/media', [MediaController::class, 'destroyBulk'])
    ->name('media.destroy.bulk');
// end media section 

// categories 
Route::prefix('categories')->group(function(){
    Route::get('' , [CategoryController::class, 'index'])->name("categories.index");
    Route::get('/create' , [CategoryController::class, 'create'])->name("categories.create");
    Route::get('/tree' , [CategoryController::class, 'tree'])->name("categories.tree");
    Route::get('/{category:slug}' , [CategoryController::class, 'edit'])->name("categories.edit");
});
Route::get('api/subCategories' , [CategoryController::class,'subCategories'])->name('get.sub_categories');
//end categories section

// attributes
Route::prefix('attributes')->group(function(){
    Route::get('' , [AttributesController::class, 'index'])->name('get.attributes');
    Route::post('' , [AttributesController::class, 'store'])->name('store.attributes');
});

// settings 
Route::get("/store" , [StoreConfigController::class ,  'index'])->name("store") ; 

// admin
Route::get('/admins' , [AdminController::class, 'index'])->name('admins.index') ;

// variants managment
Route::get('/variants/colors' , [VariantsController::class, 'colors'])->name('variants.colors') ;
Route::get('/variants/sizes' , [VariantsController::class, 'sizes'])->name('variants.sizes') ;

// oderes
// OrderManager
Route::prefix('orders')->group(function(){
    Route::get('' , [OrderController::class, 'index'])->middleware('auth')->name('orders.index') ;
    // after checkout sucess
    Route::get("/{order}/track" , [OrderController::class, 'authTrack'])->middleware('auth')->name('track.auth') ;
    Route::get('/track/{token}', [OrderController::class, 'guestTrack'])
        ->where('token', '[0-9a-f-]{36}')->name('track.guest') ;
});

// coupon aplly ajaxrequest
Route::post('/coupon_feedback', [CouponController::class,'coupon_feedback'])->name('coupon.feedback');

// customer
Route::prefix('customers')->group(function(){
    Route::get('' , [CustomerController::class, 'index'])->name('customers.index') ;
    Route::get('/{id}' , [CustomerController::class, 'show'])->name('customers.show') ;
});

// reviews
Route::prefix('reviews')->group(function(){
    Route::get('', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/pending', [ReviewController::class, 'pending'])->name('reviews.pending');
});

// Roles
Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
Route::post('/users/{user}/roles', [RoleController::class, 'assignRole'])->name('admin.users.assignRole');
Route::delete('/users/{user}/roles', [RoleController::class, 'removeRole'])->name('admin.users.removeRole');



// messages
Route::get('/messages' , [MessageController::class, 'index'])->name('messages') ;

// dashboard/sales_analytics
Route::prefix('dashboard')->group(function(){
    Route::get('/sales_analytics' , [DashboardController::class, 'salesIndex'])->name('dashboard.sales_analytics');
    Route::get('/customers_analytics' , [DashboardController::class, 'customerIndex'])->name('dashboard.customers_analytics');
    Route::get('/inventory_analytics' , [DashboardController::class, 'inventoryIndex'])->name('dashboard.inventory_analytics');
});

// require __DIR__.'/auth.php';
  