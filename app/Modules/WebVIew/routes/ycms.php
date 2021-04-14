<?php

Route::prefix('webview')->group(function($r) {
  Route::get('{module_id}', '\App\Modules\WebVIew\Controllers\WebViewController@getWebViewId');
  Route::patch('{module_id}', '\App\Modules\WebVIew\Controllers\WebViewController@saveData');
});
