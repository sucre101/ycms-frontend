<?php

namespace App\Modules\Ecommerce\Controllers;

use App\AppUserAnon;
use App\AppUserNotification;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Models\ShopCart;
use App\Modules\Ecommerce\Models\ShopCartToProduct;
use App\Modules\Ecommerce\Models\ShopCategory;
use App\Modules\Ecommerce\Models\ShopOrder;
use App\Modules\Ecommerce\Models\ShopOrderStatus;
use App\Modules\Ecommerce\Models\ShopProduct;
use App\Modules\Ecommerce\Models\ShopProductToStore;
use App\PaymentService;
use App\Services\Notification\NotificationService;
use App\Services\Payment\PaymentDriver;
use App\Services\Payment\StripeDriver;
use App\UserPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class ApiEcommerceController extends Controller
{

  public function getCategories($app_id, $module_id)
  {
    $categories = ShopCategory::where('user_module_id', $module_id)->with('products.images')->get()->toTree();

    return response()->json(['success' => true, 'categories' => $categories]);
  }

  public function getProductData($app_id, $module_id, $product_id)
  {
    $data = [];

    $product = ShopProduct::where(['user_module_id' => $module_id, 'id' => $product_id])->with('toProduct')->first();

    $data['prices'] = $product->toProduct;

    return response()->json(['success' => true, 'data' => $data]);
  }

  public function cartCheckout($app_id, Request $request): JsonResponse
  {
    $device = $request->headers->get('device');

    $user = AppUserAnon::where('uuid', $request->post('uuid'))->first();
    $userData = $request->post('user_data');

    if (!$user) {
      $user = AppUserAnon::create(['app_id' => $app_id, 'uuid' => $request->post('uuid')]);
    }

    $cart = ShopCart::create(['anon_id' => $user->id]);

    foreach ($request->post('products') as $product) {

      ShopCartToProduct::create([
        'product_id' => $product['product_id'],
        'cart_id' => $cart->id,
        'store_id' => $product['store_id'],
        'quantity'=> $product['quantity'],
        'price' => $product['price']
      ]);

    }

    try {

      $order = ShopOrder::create([
        'anon_id' => $user->id,
        'cart_id' => $cart->id,
        'store_id' => 1,
        'step' => 1,
        'phone' => $userData['phone'],
        'email' => $userData['email'],
        'name' => $userData['name'],
        'comment' => $userData['comment'] ?? '',
        'order_status_id' => 1,
        'user_module_id' => $request->post('module_id'),
      ]);

    } catch (Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }

    $paymentData['card'] = $request->post('payment_data');

    if ($paymentData) {

      $service = UserPaymentService::where(['app_id' => $app_id, 'active' => true])->first();

      $keys = json_decode($service->payment_data, true, 512, JSON_THROW_ON_ERROR);

      if (config()->get('app.env') === 'local') {
        $paymentData['card']['number'] = '4242424242424242';
      }

      $paymentDriver = new PaymentDriver(
          PaymentService::$services[$service->payment_service_id],
          $paymentData,
          $keys
      );

      $result = $paymentDriver->pay(['amount' => 2000, 'currency' => 'usd', 'description' => '']);

      if ($result['status'] === 'succeeded') {

        ShopOrder::where('id', $order->id)->update(['order_status_id' => ShopOrderStatus::ORDER_STATUS_SUCCESS]);

        $message = "You bought an item. Order #{$order->id}";

        (new NotificationService(
          $app_id, $user->id, AppUserNotification::LEVEL_MODULE, ['title' => $message, 'message' => $message], $request->post('module_id')
        ))->send();

      }

    }

    return response()->json(['success' => true, 'order_id' => $order->id]);
  }

  public function getNotifications($app_id, $module_id, $user_anon_id)
  {
    $user = AppUserAnon::where('uuid', $user_anon_id)->first();

    $notifications = [];

    if ($user) {

      $notifications = AppUserNotification::where([
        'app_id' => $app_id,
        'module_id' => $module_id,
        'app_user_anon_id' => $user->id,
        'level_id' => AppUserNotification::LEVEL_MODULE,
      ])->get();

    }

    return response()->json(['success' => true, 'notifications' => $notifications]);
  }

  public function notifyRead($app_id, $module_id, $notify_id): JsonResponse
  {
    AppUserNotification::where('id', $notify_id)->update(['status_id' => AppUserNotification::STATUS_READED]);

    return response()->json(['success' => true]);
  }

}
