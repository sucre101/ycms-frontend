<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\ShopCartToProduct;
use App\Modules\Ecommerce\ShopOrder;
use App\Modules\Ecommerce\ShopOrderStatus;
use Illuminate\Http\Request;

class ShopOrdersController extends YcmsController
{

  public function allOrders()
  {
    $app = $this->getApp();
    $orders = ShopOrder::with('status')->orderBy('id', 'asc')->get();

    return view('stores.orders.list', compact('orders', 'app'));
  }

  public function show($slug, $order_id)
  {
    $app = $this->getApp();
    $order = ShopOrder::where('id', $order_id)
      ->with('cartToProduct.product')
      ->with('store.currency')
      ->first();

    $total = 0;

    foreach ($order->cartToProduct as $product) {
        $total = $total + $product->quantity * $product->price;
    }

    $order->offsetSet('total', $total);
    $statuses = ShopOrderStatus::all();

    return view('stores.orders.edit', compact('app', 'order', 'statuses'));
  }

  public function removeProduct(Request $post)
  {
    $order = ShopOrder::where('id', $post->order_id)->with('products')->first();
    $order->products()->detach($post->id);
  }

  public function saveOrder($slug, $order_id, Request $post)
  {
    $order = ShopOrder::where('id', $order_id)->first();
    $order->comment = $post->post('comment');
    $order->order_status_id = $post->post('order_status_id');
    $order->save();

    foreach ($post->post('cart_to_product') as $product) {
      ShopCartToProduct::where('cart_id', $product['cart_id'])
        ->where('store_id', $product['store_id'])
        ->where('product_id', $product['product_id'])
        ->update(['quantity' => $product['quantity']]);
    }

    return response()->json(['success' => true], 200);
  }

}
