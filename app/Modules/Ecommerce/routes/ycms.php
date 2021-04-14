<?php

Route::namespace('\App\Modules\Ecommerce\Controllers')
  ->prefix('ecommerce')
  ->group(function () {

    Route::prefix('{module_id}')->group(function ($r) {

      Route::prefix('store')->group(function($r) {
        Route::get('/', 'ShopStoresController@getStores');
      });

      Route::post('/create-store', 'ShopStoresController@store');
      Route::post('/delete-store', 'ShopStoresController@deleteStore');

      /*
       * Routes to categories
       */
      Route::prefix('category')->group(function($r) {
        Route::get('/', 'ShopStoresController@getCategories');
        Route::post('/', 'ShopStoresController@createCategory');
        Route::patch('/', 'ShopStoresController@rebuildCategoryTree');
        Route::prefix('/{category_id}')->group(function($r) {
          Route::get('/', 'ShopStoresController@getCategory');
          Route::patch('/', 'ShopStoresController@updateCategory');
        });
      });

      /*
       * Routes to products
       */
      Route::prefix('product')->group(function($r) {
        Route::get('/', 'ShopStoresController@getProducts');
        Route::get('/{limit}/{offset}', 'ShopStoresController@afterLoadProduct');
        Route::post('/', 'ShopStoresController@createProduct');
        Route::prefix('{product_id}')->group(function($r) {
          Route::get('/', 'ShopStoresController@getProduct');
          Route::patch('/', 'ShopStoresController@updateProduct');
          Route::get('/duplicate', 'ShopStoresController@productDuplicate');
          Route::delete('/', 'ShopStoresController@productDelete');
        });
      });

      /*
       * Routes to settings
       */
      Route::prefix('settings')->group(function ($r) {
        Route::get('/', 'ShopStoresController@getSettings');
        Route::patch('/', 'ShopStoresController@saveSettings');
      });

      Route::prefix('union')->group(function ($r) {
        Route::get('/', 'ShopStoresController@getUnions');
        Route::put('/', 'ShopStoresController@createUnion');
        Route::patch('/', 'ShopStoresController@updateUnion');
        Route::prefix('{union_id}')->group(function ($r) {
          Route::delete('/', 'ShopStoresController@deleteUnion');
        });
      });

      /*
       * Routes to orders
       */
      Route::prefix('order')->group(function ($r) {
        Route::get('/', 'ShopStoresController@getAllOrders');
        Route::prefix('/{order_id}')->group(function($r) {
          Route::get('/', 'ShopStoresController@getOrder');
          Route::patch('/', 'ShopStoresController@updateOrder');
        });
      });

    });

  });
