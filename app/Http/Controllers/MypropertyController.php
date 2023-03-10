<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MypropertyController extends Controller
{
    public function index1() {
        $properties = Property::get();
        return view('my.index', compact('properties'));
      }
    public function index() {
        if (Auth::check()) {
            $properties = property::where('user_id', Auth::id())->get();
        }
        return view('myproperty', compact('properties'));
        // $properties = Property::get();
        // return view('my.index', compact('properties'));
        //dd("deneme");
      }

      public function edit($id) {
        $property = Property::find($id);
        return view('editmy', compact('property'));
      }

      public function update(Request $request, $id) {
        $property = Property::find($id);
        $filename = $property->image;

        if($request->file('image')) {
          // if a new image was chosen, save it in storage/app/public/product directory
          // "the store method returns the path of the file relative to the disk's root" https://laravel.com/docs/8.x/requests#storing-uploaded-files
          $image = $request->file('image')->store('public/product');
          // dd($image);
          // delete the original image in storage/app/public/files
          Storage::delete($filename);

          // set form input data on the property object
          $property->price = $request->price;
          $property->address = $request->address;
          $property->bedrooms = $request->bedrooms;
          $property->bathrooms = $request->bathrooms;
          $property->image = $image;
          $property->category_id = $request->category;
         // $property->subcategory_id = $request->subcategory;
          //save the modified property object
          $property->save();
        } else {
            // set form input data on the property object without the image
            $property->price = $request->price;
            $property->address = $request->address;
            $property->bedrooms = $request->bedrooms;
            $property->bathrooms = $request->bathrooms;
            $property->category_id = $request->category;
            //$property->subcategory_id = $request->subcategory;
            //save the modified property object
            $property->save();
        }

        return redirect()->route('my.index')->with('message', 'Property updated successfully');
      }

      public function destroy($id) {
        $property = Property::find($id);// get the property by id
        $filename = $property->image;// get the image
        $property->delete();// delete the property
        Storage::delete($filename);// delete the image

        return redirect()->route('my.index')->with('message', 'Property deleted successfully');
      }
}
