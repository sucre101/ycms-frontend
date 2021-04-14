<?php

namespace App\Http\Controllers\Ycms\PageBuilder;

use App\Http\Controllers\Controller;
use App\Modules\PageBuilder\Element;
use App\Modules\PageBuilder\Image;
use App\Modules\PageBuilder\Template;
use Illuminate\Http\Request;

class ElementsController extends Controller
{
  /**
   * store new element
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $element = Element::create($request->element);

    $element = Element::whereId($element->id)->with('template')->first();
    return response()->json([
      'success' => true,
      'element' => $element
    ]);
  }
  /**
   * update element
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $element = Element::find($request->element['id']);
    if($element){
      $element->update($request->element);

      $elements = Element::where(['block_id' => $element->block_id])->with('images')->with('template')->get();
      return response()->json([
        'success' => true,
        'elements' => $elements,
        'element' => $element
      ]);
    }
    return response()->json([
      'success' => false,
      'message' => "element  not found"
    ]);
  }
  /**
   * delete element
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $element = Element::find($request->id);
    $block_id = $element->block_id;

    //delete images
    if(in_array($element->type, ['image', 'slider'])){
      $this->delete_element_image($element->id);
    }

    $element->delete();

    $elements = Element::where(['block_id' => $block_id])->with('template')->with('images')->get();
    return response()->json([
      'success' => true,
      'elements' => $elements
    ]);
  }

  /**
   * store images
   *
   * @return \Illuminate\Http\Response
   */
  public function save_image(Request $request)
  {

    $element = Element::find($request->id);
    if(!$element){
      return response()->json([
        'success' => false,
        'message' => "No data found"
      ]);
    }
    if($element->type === 'image'){
      Image::where(['entity_id' => $element->id])->delete();
    }

    foreach ($request->image as $image){
      Image::save_image($element->id, $element->type, $image);
    }

    $element = Element::whereId($element->id)->with('images')->with('template')->first();
    return response()->json([
      'success' => true,
      'element' => $element
    ]);
  }
  /**
   * delete element's image
   *
   * @return \Illuminate\Http\Response
   */
  protected function delete_element_image($id)
  {
    $element = Element::find($id);
    if(!$element){
      return false;
    }

    //delete images
    if($element->type == "image"){
      $image = Image::where(['entity_id' => $element->id])->first();
      if($image){
        Image::delete_image($image->id);
      }
    }else if($element->type == "slider"){
      $images = Image::where(['entity_id' => $element->id])->get();
      foreach ($images as $image) {
        Image::delete_image($image->id);
      }
    }

    return true;
  }
  /**
   * delete image
   *
   * @return \Illuminate\Http\Response
   */
  protected function delete_image(Request $request)
  {
      if($request->id){
        $image = Image::find($request->id);
        $element_id = $image->entity_id;
        if($image){
          unlink(public_path($image->url_original));
          $image->delete();
        }

        $images = Image::where(['entity_id' => $element_id])->get();
        return response()->json([
          'success' => true,
          'images' => $images
        ]);
      }else{
        return response()->json([
          'success' => false,
          'message' => "No data found"
        ]);
      }
  }
}
