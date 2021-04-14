<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopBar extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    // $comment = Comment::find($this->route('comment'));

    // return $comment && $this->user()->can('update', $comment);
    // TODO: authorization logic needed?
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    // $validator = Validator::make($request->all(), [
    //     'top-bar-bg-image' => 'required',
    //     'cropped-top-bar-bg-image' => 'required'
    // ]);
    // if ($validator->fails()) {
    //     return abort(422, 'You didn`t provided an image');
    // }

    return [
      'top-bar-bg-image' => 'required_if:top-bar-bg-type,image',
      'cropped-top-bar-bg-image' => 'required_if:top-bar-bg-type,image'
    ];
  }
}
