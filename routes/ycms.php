<?php

Route::group(['prefix' => 'auth'], function ($r) {

  Route::post('login', 'Auth\YcmsAuthController@login')->name('login');
  Route::post('logout', 'Auth\YcmsAuthController@logout');
  Route::post('refresh', 'Auth\YcmsAuthController@refresh');
  Route::post('me', 'Auth\YcmsAuthController@me');
  Route::post('register', 'Auth\YcmsAuthController@register');
});

Route::get('/confirm/{app_id}', 'Ycms\AppController@finishBuild');

Route::middleware('auth:ycms')->group(function($r) {

  $modulesPath = scandir(app_path() . '/Modules');

  unset( $modulesPath[0], $modulesPath[1] );

  foreach ($modulesPath as $path) {
    if (file_exists(app_path() . '/Modules/' . $path . '/routes/ycms.php')) {
      include app_path() . '/Modules/' . $path . '/routes/ycms.php';
    }
  }

  Route::get('/file-manager', 'Ycms\AppController@getMediaFiles');
  Route::post('/file-manager/upload', 'Ycms\AppController@uploadFile');

  Route::get('/apps', 'Ycms\AppController@list');
  Route::post('/getApplication', 'Ycms\AppController@getApplication');
  Route::post('/store-app', 'Ycms\AppController@storeApp');
  Route::post('/app-delete', 'Ycms\AppController@delete');

  Route::prefix('{slug}')->group(function($r) {
    Route::prefix('settings')->group(function($r) {
      Route::patch('update', 'Ycms\AppController@updateAppIcon');
    });
  });


  Route::post('/modules', 'Ycms\AppController@moduleList');
  Route::post('/add-module', 'Ycms\AppController@addModule');

  Route::group(['prefix' => 'module'], function($r) {
    Route::post('/', 'Ycms\AppController@moduleList');
    Route::post('/add', 'Ycms\AppController@addModule');
    Route::post('/delete', 'Ycms\AppController@deleteModule');
  });


  Route::prefix('pagebuilder')->group(function($r) {
    Route::prefix('block')->group(function($r) {
      Route::post('/store', 'Ycms\PageBuilder\BlocksController@store');
      Route::post('/update', 'Ycms\PageBuilder\BlocksController@update');
      Route::post('/delete', 'Ycms\PageBuilder\BlocksController@destroy');
      Route::post('/remove', 'Ycms\PageBuilder\BlocksController@remove');
      Route::post('/restore', 'Ycms\PageBuilder\BlocksController@restore');
      Route::post('/change_block', 'Ycms\PageBuilder\BlocksController@change_block');
      Route::post('/change_block_order', 'Ycms\PageBuilder\BlocksController@change_block_order');
    });

    Route::prefix('element')->group(function($r) {
      Route::post('/store', 'Ycms\PageBuilder\ElementsController@store');
      Route::post('/update', 'Ycms\PageBuilder\ElementsController@update');
      Route::post('/delete', 'Ycms\PageBuilder\ElementsController@destroy');
      Route::post('/save_image', 'Ycms\PageBuilder\ElementsController@save_image');
      Route::post('/delete_image', 'Ycms\PageBuilder\ElementsController@delete_image');
    });

    Route::prefix('template')->group(function($r) {
      Route::post('/store', 'Ycms\PageBuilder\TemplatesController@store');
      Route::post('/update', 'Ycms\PageBuilder\TemplatesController@update');
      Route::post('/delete', 'Ycms\PageBuilder\TemplatesController@destroy');
    });

    Route::prefix('{module_id}')->group(function($r) {
      Route::get('/templates', 'Ycms\PageBuilder\TemplatesController@templatesList');
      Route::get('/edit', 'Ycms\PageBuilder\BlocksController@blocksList');
    });
  });

  Route::prefix('socialwall')->group(function($r) {

    Route::prefix('{module_id}')->group(function($r) {
      Route::get('/tags', 'Ycms\SocialWall\TagsController@tags');
      Route::get('/getTags', 'Ycms\SocialWall\TagsController@getTags');
      Route::prefix('tag')->group(function($r) {
        Route::get('/store', 'Ycms\SocialWall\TagsController@store');
        Route::get('/delete', 'Ycms\SocialWall\TagsController@destroy');
      });

      Route::get('/posts', 'Ycms\SocialWall\PostsController@posts');
      Route::prefix('post')->group(function($r) {
        Route::get('/store', 'Ycms\SocialWall\PostsController@store');
        Route::get('/publish', 'Ycms\SocialWall\PostsController@publish');
        Route::get('/delete', 'Ycms\SocialWall\PostsController@destroy');
      });

      Route::get('/blocks', 'Ycms\SocialWall\BlocksController@blocks');
      Route::prefix('block')->group(function($r) {
        Route::get('/change_block', 'Ycms\SocialWall\BlocksController@change_block');
        Route::get('/store', 'Ycms\SocialWall\BlocksController@store');
        Route::get('/update', 'Ycms\SocialWall\BlocksController@update');
        Route::get('/delete', 'Ycms\SocialWall\BlocksController@destroy');
      });
    });

  });
  Route::group(['prefix' => 'page'], function($r) {
    Route::post('/list', 'Ycms\PageController@index');
    Route::put('/create', 'Ycms\PageController@create');
    Route::post('/update-orders', 'Ycms\PageController@updateOrders');
    Route::post('/delete', 'Ycms\PageController@delete');
  });

  Route::prefix('{slug}')->group(function($r) {

    Route::prefix('publication')->group(function($r) {
      Route::get('export', 'Ycms\AppController@export');
      Route::get('/', 'Ycms\AppController@getBuilds');
    });

    Route::group(['prefix' => 'payment'], function($r) {

      Route::get('/', 'PaymentController@getList');
      Route::post('save', 'PaymentController@saveData');

      Route::prefix('{service_id}')->group(function($r) {
        Route::get('/', 'PaymentController@getService');
        Route::patch('/', 'PaymentController@updateService');
        Route::patch('/update-list', 'PaymentController@updateList');
      });

      Route::group(['prefix' => 'test'], function($r) {
        Route::post('stripe', 'PaymentController@testStripe');
      });

    });
  });

});


