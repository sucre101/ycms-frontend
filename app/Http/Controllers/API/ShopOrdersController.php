<?php

namespace App\Http\Controllers\API;

use App\AppUserAnon;
use App\Http\Controllers\YcmsController;
use App\Modules\Ecommerce\ShopCart;
use App\Modules\Ecommerce\ShopCartToProduct;
use App\Modules\Ecommerce\ShopOrder;
use Illuminate\Http\Request;

class ShopOrdersController extends YcmsController
{

  public function getOrders($app_id)
  {
    $anon = AppUserAnon::where('uuid', $this->anon_id)->first();

    $orders = ShopOrder::where('anon_id', $anon->id)->with('status')->get();

    return response()->json($orders);
  }

  public function createOrder($app_id, Request $post)
  {
    $anon = AppUserAnon::where('uuid', $this->anon_id)->first();

    $cart = ShopCart::where('anon_id', $anon->id)->first();

    $order = ShopOrder::where('cart_id', $cart->id)->where('anon_id', $anon->id)->with('status')->first();

    if (!$order) {

      $order = ShopOrder::create([
        'anon_id' => $anon->id,
        'cart_id' => $cart->id,
        'store_id'=> $post->store_id,
        'phone' => $post->phone,
        'email' => $post->email,
        'name' => $post->name,
        'comment' => $post->comment,
        'step' => 1,
        'order_status_id' => 1
      ]);
      $order->save();

      $products = ShopCartToProduct::where('cart_id', $order->id)
        ->with('product', 'product.images', 'product.labelToStores.label', 'productToStore')
        ->get()
        ->toArray();

      $total = 0;
      $data = [];
      $data['products'] = [];

      foreach ($products as $product) {

        $labels = [];

        $total = $product['price'] * $product['quantity'] + $total;

        foreach ($product['product']['label_to_stores'] as $label) {
          if ($product['store_id'] == $label['store_id']) {
            array_push($labels, ['id' => $label['label']['id'], 'title' => $label['label']['title']]);
          }
        }

        $dat['cart_to_product_id'] = $product['id'];
        $dat['store_id'] = $product['store_id'];
        $dat['id'] = $product['product']['id'];
        $dat['price'] = $product['price'];
        $dat['quantity'] = $product['quantity'];
        $dat['product'] = [
          'desc' => $product['product']['desc'],
          'name' => $product['product']['name'],
          'sku' => $product['product']['sku'],
          'quantity' => $product['product_to_store']['quantity'],
          'image' => $product['product']['images'] ? $product['product']['images'][0]['url_original'] : null,
          'labels' => $labels
        ];

        array_push($data['products'], $dat);
      }

      $order['products'] = $data['products'];
      $order['total_price'] = $total;

      $cart->delete();

      return response()->json($order);
    }

    return response()->json(['success' => false, 'message' => 'Error']);
  }

  public function getOrderById($app_id, $order_id)
  {
    $order = ShopOrder::where('id', $order_id)->with('status')->first()->toArray();

    $products = ShopCartToProduct::where('cart_id', $order['cart_id'])
      ->with('product', 'product.images', 'product.labelToStores.label', 'productToStore')
      ->get()
      ->toArray();

    $total = 0;
    $data = [];
    $data['products'] = [];

    foreach ($products as $product) {

      $labels = [];

      $total = $product['price'] * $product['quantity'] + $total;

      foreach ($product['product']['label_to_stores'] as $label) {
        if ($product['store_id'] == $label['store_id']) {
          array_push($labels, ['id' => $label['label']['id'], 'title' => $label['label']['title']]);
        }
      }

      $dat['cart_to_product_id'] = $product['id'];
      $dat['store_id'] = $product['store_id'];
      $dat['id'] = $product['product']['id'];
      $dat['price'] = $product['price'];
      $dat['quantity'] = $product['quantity'];
      $dat['product'] = [
        'desc' => $product['product']['desc'],
        'name' => $product['product']['name'],
        'sku' => $product['product']['sku'],
        'quantity' => $product['product_to_store']['quantity'],
        'image' => $product['product']['images'] ? $product['product']['images'][0]['url_original'] : null,
        'labels' => $labels
      ];

      array_push($data['products'], $dat);
    }

    $order['products'] = $data['products'];
    $order['total_price'] = $total;

    return response()->json($order);
  }

}
