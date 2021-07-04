<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class   SettingsController extends Controller
{
    public function editShippingMethods($typy)
    {
        //free ,local  ,outer for shipping methods
        if ($typy === 'free')
            $ShippingMethod = Setting::where('key', 'free_shipping_label')->first();

        elseif ($typy === 'local')
            $ShippingMethod = Setting::where('key', 'local_label')->first();

        elseif ($typy === 'outer')
            $ShippingMethod = Setting::where('key', 'outer_label')->first();
        else
            $ShippingMethod = Setting::where('key', 'free_shipping_label')->first();

        return view('dashboard.settings.shippings.edit', compact('ShippingMethod'));

    }

    public function updateShippingMethods(ShippingRequest $request, $id)
    {

        try {
        $shipping_method = Setting::find($id);
        DB::beginTransaction();
        $shipping_method -> update(['plain_value' => $request -> plain_value]);


        $shipping_method -> value = $request -> value;
        $shipping_method -> save();
        DB::commit();
          return redirect() -> back() ->with(['success' => 'تم التحديث بنجاح']);
       }catch (\Exception $ex){
            return redirect() -> back() ->with(['error' => 'هناك خطأ ما']);
        DB::rollBack();
        }
    }
}
