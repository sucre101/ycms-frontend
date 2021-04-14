<?php

//Route::get('/{any}', 'AppsController@spa')->where('any', '.*');

//Route::get('/', function() {
//   return http_redirect('https://yappix.io');
//});

//Auth::routes();
//Broadcast::routes();
//
//Route::middleware(['auth'])->group(function() {
//  Route::get('/', 'AppsController@index');
//  // TODO: wrap to app/ prefix
//  Route::get('new-app', 'AppsController@newApp');
//  Route::post('create-app', 'AppsController@createApp');
//  Route::post('store-app', 'AppsController@storeApp');
//  Route::get('apps', 'AppsController@list')->name('appsList');
//
//  Route::prefix('app')->group(function () {
//
//    Route::get('/', 'AppsController@show')->name('app.show');
//    Route::get('{slug}/edit', 'AppsController@edit')->name('app.edit');
//    Route::post('duplicate', 'AppsController@duplicate');
//    Route::post('/delete', 'AppsController@delete');
//
//    Route::prefix('{slug}/style')->group(function (){
//      Route::get('/', 'StyleController@element')->name('styleElements');
////      Route::get('elements', 'StyleController@elements')->name('styleElements');
//      Route::post('top-bar-settings', 'StyleController@updateTopBar');
//    });
//
//    Route::prefix('{slug}/pages')->group(function(){
//      Route::get('/', 'PagesController@index')->name('pagesList');
//      Route::post('new-page', 'PagesController@newPage');
//      Route::get('home', 'PagesController@home')->name('homeMenu');
//      Route::post('set-home', 'PagesController@setHome');
//      Route::post('update-pages', 'PagesController@updatePages');
//      Route::get('{pageId}/edit', 'PagesController@editPage');
//    });
//
//    Route::prefix('{slug}/shop-categories')->group(function(){
//      Route::get('/', 'ShopCategoriesController@index')->name('shopCategoryList');
//      Route::post('new-category', 'ShopCategoriesController@store');
//      Route::get('{id}/edit', 'ShopCategoriesController@edit');
//      Route::post('/change-name', 'ShopCategoriesController@changeName');
//      Route::post('/change-image', 'ShopCategoriesController@changeImage');
//      Route::post('rebuild', 'ShopCategoriesController@rebuild');
//      Route::post('duplicate', 'ShopCategoriesController@duplicate');
//      Route::post('/delete', 'ShopCategoriesController@destroy');
//
//      Route::get('{id}/filters', 'ShopCategoryFiltersController@filters');
//      Route::post('filter-save', 'ShopCategoryFiltersController@filter_save');
//      Route::post('filter-extra-save', 'ShopCategoryFiltersController@filter_extra_save');
//      Route::post('filter-grouping-save', 'ShopCategoryFiltersController@filter_grouping_save');
//      Route::post('filter-delete', 'ShopCategoryFiltersController@filter_delete');
//      Route::post('filter-extra-delete', 'ShopCategoryFiltersController@filter_extra_delete');
//      Route::post('filter-group-delete', 'ShopCategoryFiltersController@filter_grouping_delete');
//    });
//
//    Route::prefix('{slug}/shop-products')->group(function(){
//      Route::get('/', 'ShopProductsController@index');
//      Route::post('/', 'ShopProductsController@getProducts');
//      Route::get('{category_id}/create', 'ShopProductsController@create')->name('shop.product.add');
//      Route::get('{id}/edit', 'ShopProductsController@edit')->name('shop.product.edit');
//      Route::post('new-product', 'ShopProductsController@store');
//      Route::post('update-product', 'ShopProductsController@update');
//      Route::post('{id}/save-image', 'ShopProductsController@saveImage');
//      Route::post('delete-image', 'ShopProductsController@deleteImage');
//      Route::post('duplicate', 'ShopProductsController@duplicate');
//      Route::post('remove-from-union', 'ShopProductsController@removeUnion');
//      Route::post('delete', 'ShopProductsController@destroy');
//      Route::post('change-product', 'ShopProductsController@getProductJson');
//      Route::post('get-products-to-union', 'ShopProductsController@getProductsToUnion');
//      Route::post('get-images', 'ShopProductsController@getImages');
//    });
//
//    Route::prefix('{slug}/shop-specs')->group(function(){
//      Route::post('new-spec', 'ShopSpecsController@store');
//      Route::post('/delete', 'ShopSpecsController@destroy');
//      Route::post('/change-position', 'ShopSpecsController@changePosition');
//      Route::post('/save-name', 'ShopSpecsController@saveName');
//    });
//
//    Route::prefix('{slug}/shop-specs-group')->group(function(){
//      Route::post('new-spec-group', 'ShopSpecGroupsController@store');
//      Route::post('/delete', 'ShopSpecGroupsController@destroy');
//      Route::post('/change-position', 'ShopSpecGroupsController@changePosition');
//      Route::post('/save-name', 'ShopSpecGroupsController@saveName');
//    });
//
//    Route::prefix('{slug}/shop-product-union')->group(function(){
//      Route::post('new-union', 'ShopProductUnionsController@store');
//      Route::post('/add-product-to-union', 'ShopProductUnionsController@addProductToUnion');
//      Route::post('/{union_id}/save-image', 'ShopProductUnionsController@saveImage');
//      Route::post('/change-image', 'ShopProductUnionsController@changeImage');
//      Route::post('/delete', 'ShopProductUnionsController@destroy');
//      Route::post('/save-name', 'ShopProductUnionsController@saveName');
//    });
//
//    Route::prefix('{slug}/auth')->group(function(){
//      Route::get('settings', 'AuthSettingsController@settings');
//      Route::post('settings/store', 'AuthSettingsController@settings_save');
//      Route::post('settings/save_prefix', 'AuthSettingsController@save_prefix');
//      Route::post('settings/save_suffix', 'AuthSettingsController@save_suffix');
//      Route::post('settings/save_gender', 'AuthSettingsController@save_gender');
//    });
//
//    Route::get('{slug}/settings', 'SettingsController@index');
//    Route::post('{slug}/set-app-mode', 'SettingsController@setOSMode');
//    Route::post('{slug}/fix-pages', 'SettingsController@fixPages');
//
//    Route::post('{slug}/shop-settings/update', 'SettingsController@updateSettings');
//
//    Route::prefix('{slug}/shops')->group(function(){
//      Route::get('/', 'ECommerceController@list');
//      Route::get('/structure', 'ECommerceController@showStructure');
//      Route::get('{store_id}', 'ECommerceController@editStore');
//      Route::post('create-store', 'ECommerceController@createStore');
//      Route::post('delete-store', 'ECommerceController@deleteStore');
//      Route::post('save-store', 'ECommerceController@saveEditedStore');
//      Route::post('copy-store', 'ECommerceController@copyStore');
//      Route::post('default-store', 'ECommerceController@setDefault');
//      Route::post('update-order', 'ECommerceController@updateStoreOrder');
//
//      Route::post('new-structure', 'ECommerceController@newStructure');
//      Route::post('update-structure', 'ECommerceController@updateStructure');
//      Route::post('delete-element-structure', 'ECommerceController@deleteElement');
//      Route::post('edit-element-structure', 'ECommerceController@editNameStructureElement');
//    });
//
//    Route::get('{slug}/labels', 'ShopLabelsController@list');
//    Route::post('{slug}/label/save-label', 'ShopLabelsController@saveLabel');
//    Route::post('{slug}/label/edit-label', 'ShopLabelsController@updateLabel');
//    Route::post('{slug}/label/delete-label', 'ShopLabelsController@deleteLabel');
//
//    Route::get('{slug}/units', 'ShopUnitsController@index');
//    Route::post('{slug}/unit/store', 'ShopUnitsController@store');
//    Route::post('{slug}/unit/update', 'ShopUnitsController@update');
//    Route::post('{slug}/unit/destroy', 'ShopUnitsController@destroy');
//
//    Route::get('{slug}/orders', 'ShopOrdersController@allOrders');
//    Route::get('{slug}/order/{order_id}', 'ShopOrdersController@show');
//    Route::post('{slug}/order/{order_id}/save', 'ShopOrdersController@saveOrder');
//    Route::post('{slug}/order/{order_id}/remove-product', 'ShopOrdersController@removeProduct');
//
//    Route::prefix('{slug}/feed')->group(function() {
//      Route::get('modules', 'SocialFeed\ModulesController@index');
//      Route::post('module/store', 'SocialFeed\ModulesController@store');
//      Route::post('module/delete', 'SocialFeed\ModulesController@destroy');
//
//      Route::get('/{id}/posts', 'SocialFeed\PostsController@index');
//      Route::post('post/store', 'SocialFeed\PostsController@store');
//      Route::post('post/delete', 'SocialFeed\PostsController@destroy');
//      Route::post('post/publish', 'SocialFeed\PostsController@publish');
//
//      Route::get('/{id}/blocks', 'SocialFeed\BlocksController@index');
//      Route::post('block/store', 'SocialFeed\BlocksController@store');
//      Route::post('block/update', 'SocialFeed\BlocksController@update');
//      Route::post('block/change_block', 'SocialFeed\BlocksController@change_block');
//      Route::post('block/delete', 'SocialFeed\BlocksController@destroy');
//
//      Route::get('/{id}/tags', 'SocialFeed\TagsController@index');
//      Route::get('/{id}/tags_json', 'SocialFeed\TagsController@getTags');
//      Route::post('tag/store', 'SocialFeed\TagsController@store');
//      Route::post('tag/update', 'SocialFeed\TagsController@update');
//      Route::post('tag/delete', 'SocialFeed\TagsController@destroy');
//
//    });
//
//    Route::prefix('{slug}/page-builder')->group(function(){
//      Route::get('/', 'PageBuilder\PagesController@index');
//      Route::get('/create', 'PageBuilder\PagesController@create');
//      Route::get('{id}/edit', 'PageBuilder\PagesController@edit');
//      Route::post('/store', 'PageBuilder\PagesController@store');
//      Route::post('/update', 'PageBuilder\PagesController@update');
//      Route::post('/delete', 'PageBuilder\PagesController@destroy');
//
//
//      Route::prefix('/block')->group(function(){
//        Route::post('/store', 'PageBuilder\BlocksController@store');
//        Route::post('/update', 'PageBuilder\BlocksController@update');
//        Route::post('/change_block', 'PageBuilder\BlocksController@change_block');
//        Route::post('/change_block_order', 'PageBuilder\BlocksController@change_block_order');
//        Route::post('/delete', 'PageBuilder\BlocksController@destroy');
//      });
//
//      Route::prefix('/element')->group(function(){
//        Route::post('/store', 'PageBuilder\ElementsController@store');
//        Route::post('/update', 'PageBuilder\ElementsController@update');
//        Route::post('/delete', 'PageBuilder\ElementsController@destroy');
//      });
//
//      Route::prefix('/template')->group(function(){
//        Route::get('/', 'PageBuilder\TemplatesController@index');
//        Route::post('/store', 'PageBuilder\TemplatesController@store');
//        Route::post('/update', 'PageBuilder\TemplatesController@update');
//        Route::post('/delete', 'PageBuilder\TemplatesController@destroy');
//      });
//    });
//  });
//
////  Route::prefix('style')->group(function(){
////    Route::get('/', 'StyleController@elements')->name('styleElements');
////    Route::get('elements', 'StyleController@elements')->name('styleElements');
////    Route::post('top-bar-settings', 'StyleController@updateTopBar');
////  });
//
//  Route::post('testSocket', function(){
//    event(new IonicStarted(User::find(15), rand()));
//  });
//});

