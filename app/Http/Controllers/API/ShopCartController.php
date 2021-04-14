<?php

namespace App\Http\Controllers\API;

use App\AppUserAnon;
use App\Http\Controllers\YcmsController;
use App\Modules\Ecommerce\ShopCart;
use App\Modules\Ecommerce\ShopCartToProduct;
use App\Modules\Ecommerce\ShopProduct;
use App\Modules\Ecommerce\ShopProductToStore;
use Illuminate\Http\Request;

class ShopCartController extends YcmsController
{
  /**
   * Method which return cart with products
   * @return \Illuminate\Http\JsonResponse
   */
  public function getCart()
  {
    $this->checkAnonId();

    $user_anon = AppUserAnon::where('uuid', $this->anon_id)->first();

    if (!$user_anon) {
      return response()->json(['success' => false, 'message' => 'invalid anon_id or cart is broken']);
    }

    $cart = ShopCart::where('anon_id', $user_anon->id)
      ->with('getProductCart.product')
      ->with('getProductCart.product.images')
      ->with('getProductCart.product.labelToStores.label')
      ->with('getProductCart.productToStore')
      ->first();

    if (!$cart) {
      return response()->json(['success' => false, 'message' => 'Cart is not created. Add some products to cart']);
    }

    $total = 0;
    $data = [];
    $data['products'] = [];

    foreach ($cart->getProductCart->toArray() as $product) {

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

    $data['total_price'] = $total;

    return response()->json($data);
  }

  /**
   * Method which update Quantity by product_id
   * @param $app_id
   * @param $cart_to_product_id
   * @param  Request  $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateQtyProduct($app_id, $cart_to_product_id, Request $post)
  {
    $user_anon = AppUserAnon::where('uuid', $this->anon_id)->first();

    if (!$user_anon) {
      return response()->json(['success' => false, 'message' => 'invalid anon_id or cart is broken']);
    }

    $cart = ShopCart::where('anon_id', $user_anon->id)->first();

    if (!$cart) {
      return response()->json(['success' => false, 'message' => 'invalid anon_id or cart is broken']);
    }

    if ($post->quantity < 1) {

      self::deleteProduct($app_id, $cart_to_product_id);

      $cart = self::getCart();

      return response()->json(['success' => true, 'message' => 'product deleted', 'cart' => $cart]);
    }

    $product = ShopCartToProduct::where('id', $cart_to_product_id)->where('cart_id', $cart->id)->first();

    if (!$product) {
      return response()->json(['success' => false, 'message' => 'invalid product_id or cart is broken']);
    }

    $productPrice = ShopProductToStore::where('app_id', $app_id)
      ->where('store_id', $product->store_id)
      ->where('product_id', $product->product_id)
      ->first();

    if ($post->quantity > $productPrice->quantity) {
      return response()->json([
        'success' => false,
        'message' => 'The quantity of goods ordered is greater than the quantity in stock',
        'current_quantity' => $productPrice->quantity
      ]);
    }

    $product->update(['quantity' => $post->quantity]);

    $cart = self::getCart();

    return response()->json($cart->original);
  }

  /**
   * Method which delete product unit from cart
   * @param $app_id
   * @param $cart_to_product_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteProduct($app_id, $cart_to_product_id)
  {
    $user_anon = AppUserAnon::where('uuid', $this->anon_id)->first();

    if (!$user_anon) {
      return response()->json(['success' => false, 'message' => 'invalid anon_id or cart is broken']);
    }

    $cart = ShopCart::where('anon_id', $user_anon->id)->first();

    if (!$cart) {
      return response()->json(['success' => false, 'message' => 'invalid anon_id or cart is broken']);
    }

    ShopCartToProduct::where('id', $cart_to_product_id)->where('cart_id', $cart->id)->delete();

    $cart = self::getCart();

    return response()->json($cart->original);
  }

  /**
   * Method which add product to card
   * request body params: {
   *   product_id: int
   *   store_id: int
   *   quantity: int
   * }
   * @param $app_id
   * @param  Request  $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function productToCard($app_id, Request $post)
  {
    $this->checkAnonId();

    $product = ShopProduct::where('id', $post->product_id)->first();

    if (!$product) {
      return response()->json(['success' => false, 'message' => 'invalid post data']);
    }

    $user_anon = AppUserAnon::where('uuid', $this->anon_id)->first();

    if (!$user_anon) {
      $user_anon = new AppUserAnon(['app_id' => $app_id, 'uuid' => $this->anon_id]);
      $user_anon->save();
    }

    $productPrice = ShopProductToStore::where('app_id', $app_id)
      ->where('store_id', $post->store_id)
      ->where('product_id', $post->product_id)
      ->first();

    if (!$productPrice) {
      return response()->json(['success' => false, 'message' => 'invalid post data']);
    }

    $cart = ShopCart::where('anon_id', $user_anon->id)->first();

    if (!$cart) {
      $cart = new ShopCart(['anon_id' => $user_anon->id]);
      $cart->save();
    }

    $productInCard = ShopCartToProduct::where('cart_id', $cart->id)
      ->where('product_id', $product->id)
      ->where('store_id', $post->store_id)
      ->first();

    if ($productInCard && ($productInCard->quantity + $post->quantity) > $productPrice->quantity) {
      return response()->json(['success' => false, 'message' => 'The quantity of goods ordered is greater than the quantity in stock']);
    }

    if (!$productInCard) {
      ShopCartToProduct::create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'store_id' => $post->store_id,
        'quantity' => $post->quantity,
        'price' => $productPrice->price
      ]);
    } else {
        $productInCard->update(['quantity' => $post->quantity + $productInCard->quantity]);
    }

    $cart = self::getCart();

    return response()->json($cart->original);
  }
}
