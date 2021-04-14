<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request){
  return $request->user();
});
Route::get('unguarded', function() { return ['unguarded']; });

//Route::middleware('auth:api')->group(function($r) {
//
//
//
//});

Route::prefix('{app_id}')->group(function($r) {
  $modulesPath = scandir(app_path() . '/Modules');

  unset( $modulesPath[0], $modulesPath[1] );

  foreach ($modulesPath as $path) {

    if (file_exists(app_path() . '/Modules/' . $path . '/routes/api.php')) {
      include app_path() . '/Modules/' . $path . '/routes/api.php';
    }

  }
});



// TODO: delete?
//Route::get('category/{category}/products', function(ShopCategory $category){
//  return $category->products()->orderBy('order')->with('media')->get();
//});

//Route::get('{app_id}/categories/{category_id}', 'API\ShopCategoriesController@categories');
//Route::get('{app_id}/store/{store_id}/category/{category_id}/products', 'API\ShopCategoriesController@getProducts');
//
//// API get product by store_id and product_id
//Route::get('{app_id}/store/{store_id}/product/{product_id}', 'API\ShopProductsController@show');
//Route::get('{app_id}/store/{store_id}/chosen_products', 'API\ShopProductsController@chosen_products');
//Route::post('{app_id}/store/{store_id}/add-product', 'API\ShopProductsController@productToCart');
//
//Route::get('{app_id}/store/{store_id}/label_lists', 'API\ShopLabelListsController@labelsByStoreId');
//Route::get('{app_id}/store/{store_id}/label_list/{label_list_id}', 'API\ShopLabelListsController@labelsByStoreIdAndLabelListId');
//
//Route::get('{app_id}/store/{store_id}', 'API\ShopStoreLeafController@show');
//Route::get('{app_id}/stores', 'API\ShopStoreLeafController@index');
//
//Route::get('{app_id}/cart', 'API\ShopCartController@getCart');
//Route::delete('{app_id}/cart/{cart_to_product_id}', 'API\ShopCartController@deleteProduct');
//Route::patch('{app_id}/cart/{cart_to_product_id}', 'API\ShopCartController@updateQtyProduct');
//Route::post('{app_id}/cart', 'API\ShopCartController@productToCard');
//
//Route::get('{app_id}/orders', 'API\ShopOrdersController@getOrders');
//Route::get('{app_id}/order/{order_id}', 'API\ShopOrdersController@getOrderById');
//Route::post('{app_id}/order', 'API\ShopOrdersController@createOrder');

Route::get('{app_id}/recipe', 'API\AuthSettingsController@recipe');
Route::post('{app_id}/auth/registration', 'API\AuthSettingsController@registration');
Route::post('{app_id}/auth/registration/confirmation', 'API\AuthSettingsController@confirmation');
Route::post('{app_id}/auth/registration/resend', 'API\AuthSettingsController@resend');
Route::post('{app_id}/auth/login', 'API\AuthSettingsController@login');
Route::post('{app_id}/auth/recovery', 'API\AuthSettingsController@recovery');
Route::post('{app_id}/auth/recovery/confirmation', 'API\AuthSettingsController@confirmation');
Route::post('{app_id}/auth/recovery/set-password', 'API\AuthSettingsController@setPassword');

Route::get('{app_id}/profile/{id}', 'API\ProfilesController@profile');
Route::post('{app_id}/profile', 'API\ProfilesController@update');

Route::get('{app_id}/page-builder/pages', 'API\PageBuilderController@getPages');
Route::get('{app_id}/page-builder/page/{page_id}/blocks', 'API\PageBuilderController@getBlocks');
Route::get('{app_id}/page-builder/block/{block_id}/elements', 'API\PageBuilderController@getElements');

Route::post('{app_id}/feed/{post_id}/comment', 'API\SocialFeedController@comment');

// FIXME: must be app-shops
Route::get('user-shops/{user}', function(User $user){
  // FIXME: i think there is a better way to do it (using \DB and joins)
  $rootCategories = $user->app->pages()->where('module_type', MEcommerce::class)
    ->get()->pluck('module.shops')[0]
    ->pluck('categories')[0]->whereNull('parent_id')->values();
  $rootCategories->each(function($c){
    $c->childrenCount = $c->children->count();
  });
  return [
    'type' => 'categories',
    'content' => $rootCategories,
  ];
});

Route::get('shop-category/{category}', function(ShopCategory $category){
  if ($category->children->count()) return [
    'type' => 'categories',
    'content' => $category->children()->with('media')->get(),
  ];
  else return [
    'type' => 'products',
    'content' => $category->products()->with('media')->orderBy('order')->get(),
  ];
});

//// FIXME: must be app-shops
//Route::get('user-shops/{user}', function(User $user){
//  // FIXME: i think there is a better way to do it (using \DB and joins)
//  $rootCategories = $user->app->pages()->where('module_type', MEcommerce::class)
//    ->get()->pluck('module.shops')[0]
//    ->pluck('categories')[0]->whereNull('parent_id')->values();
//  $rootCategories->each(function($c){
//    $c->childrenCount = $c->children->count();
//  });
//  return [
//    'type' => 'categories',
//    'content' => $rootCategories,
//  ];
//});
//
//Route::get('shop-category/{category}', function(ShopCategory $category){
//  if ($category->children->count()) return [
//    'type' => 'categories',
//    'content' => $category->children()->with('media')->get(),
//  ];
//  else return [
//    'type' => 'products',
//    'content' => $category->products()->with('media')->orderBy('order')->get(),
//  ];
//});
//
//Route::get('product/{product}', function(Product $product){
//  return [
//    'type' => 'product',
//    'content' => $product->with('media')->get(),
//  ];
//});
//
//Route::prefix('{slug}')->group(function(){
//  Route::get('store/{id}', 'API\ShopStoreLeafController@show');
//});
//
//Route::namespace('API')->group(function(){
//  Route::post('register', 'AuthController@register');
//  Route::post('login', 'AuthController@login');
//  Route::post('recover', 'AuthController@recover');
//  Route::group(['middleware' => ['auth.api']], function(){
//    Route::get('logout', 'AuthController@logout');
//  });
//});
