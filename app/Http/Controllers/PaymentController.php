<?php

namespace App\Http\Controllers;

use App\PaymentService;
use App\UserPaymentService;
use Illuminate\Http\Request;
use Exception;
use Stripe\StripeClient;

class PaymentController extends YcmsController
{
  public function testStripe(Request $request)
  {
    $secret = $request->post('skey');
    $stripe = new StripeClient($secret);

    try {

      $stripe->charges->all();

    } catch (Exception $e) {

      return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }

    return response()->json(['success' => true]);
  }

  public function getList(Request $request)
  {
    $app = $this->getApp();
    $services = UserPaymentService::where(['app_id' => $app->id, 'user_id' => $this->user->id])->get();

    return response()->json(['success' => true, 'services' => $services]);
  }

  public function saveData(Request $request)
  {

    $service = UserPaymentService::where(['user_id' => $this->user->id, 'app_id' => $this->getApp()->id])->first();

    $active = $service ? false : true;

    $res = UserPaymentService::create([
      'user_id' => $this->user->id,
      'app_id' => $this->getApp()->id,
      'payment_service_id' => $request->post('id'),
      'payment_data' => json_encode($request->post('data'), JSON_THROW_ON_ERROR),
      'label' => $request->post('label') ?? '',
      'active' => $active,
    ]);


    if (!$res) {
      return response()->json(['success' => false]);
    }

    return response()->json(['success' => true]);
  }

  public function getService($slug, $serviceId)
  {
    return response()->json(['success' => true, 'service' => UserPaymentService::where('id', $serviceId)->first()]);
  }

  public function updateService($slug, $serviceId, Request $request)
  {
    $service = UserPaymentService::where('id', $serviceId)->first();

    if ($service) {
      $service->update([
        'label' => $request->post('label'),
        'payment_data' => json_encode($request->post('fields'), JSON_THROW_ON_ERROR)
      ]);
    }

    return response()->json(['success' => true]);
  }

  public function updateList($slug, $serviceId, Request $request)
  {
    if ($request->post('active') === true) {
      UserPaymentService::where(['user_id' => $this->user->id, 'app_id' => $this->getApp()->id])
        ->update(['active' => false]);

      UserPaymentService::where('id', $serviceId)->update(['active' => true]);

    } else {

      $services = UserPaymentService::where(['user_id' => $this->user->id, 'app_id' => $this->getApp()->id])->count();

      if ($services !== 0) {
        UserPaymentService::where('id', $serviceId)->update(['active' => false]);
        $services = [];
      }

    }

    $services = UserPaymentService::where(['user_id' => $this->user->id, 'app_id' => $this->getApp()->id])->get();

    return response()->json(['success' => true, 'services' => $services]);
  }

}
