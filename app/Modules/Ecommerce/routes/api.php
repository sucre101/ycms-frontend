<?php

Route::namespace('\App\Modules\Ecommerce\Controllers')
  ->prefix('ecommerce')
  ->group(function ($r) {

    Route::prefix('{module_id}')->group(function ($r) {

      Route::prefix('category')->group(function ($r) {

        Route::get('/', 'ApiEcommerceController@getCategories');

      });

      Route::prefix('product')->group(function ($r) {

        Route::prefix('{product_id}')->group(function($r) {
          Route::get('/', 'ApiEcommerceController@getProductData');
        });

      });
      Route::prefix('notifications')->group(function($r) {
        Route::get('/{user_anon_id}', 'ApiEcommerceController@getNotifications');
        Route::get('/{notification_id}/read', 'ApiEcommerceController@notifyRead');
      });

    });

    Route::post('checkout', 'ApiEcommerceController@cartCheckout');

  });
