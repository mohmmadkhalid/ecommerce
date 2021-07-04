<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandsController extends Controller
{
    public function index()
    {

        $brands = Brand::orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.brands.index', compact('brands'));
    }


    public function create()
    {
        return view('dashboard.brands.create');

    }


    public function store(BrandRequest $request)
    {

            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $fileName = "";
            if($request->has('photo')){
                $fileName =uploadImage('brand',$request->photo);
            }

            $brand = Brand::create($request->except('_token','photo'));
            $brand->name = $request->name;
            $brand->photo = $fileName;
//            return $category;
            $brand->save();
            DB::commit();

            return redirect()->route('admin.brands')->with(['success' => 'تم الإضافة بنجاح']);

    }

    public function edit($id)
    {
        $brand = Brand::find($id);


        if (!$brand) {
            return redirect()->route('admin.brands')->with(['error' => 'هذا الماركة غير موجود']);
        }
        return view('dashboard.brands.edit', compact('brand'));
    }


    public function update($id, BrandRequest $request)
    {

        try {


            $brand = Brand::find($id);

            if (!$brand)
                return redirect()->route('admin.brands')->with(['error' => 'هذا الماركة غير موجود']);
            DB::beginTransaction();

            if ($request ->has('photo')) {
                $fileName = uploadImage('brands',$request->photo);
                Brand::where('id',$id)
                    ->update([
                        'photo' => $fileName
                    ]);

            }

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $brand->update($request->except('_token','id','photo'));

            $brand->name = $request->name;
            $brand->save();

            DB::commit();

            return redirect()->route('admin.brands')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.brands')->with(['error' => 'حدث خطأ ما يرجى المحاولة لاحقا']);

        }
    }


    public function destroy($id)
    {
        try {

            $brand = Brand::find($id);

            if (!$brand)
                return redirect()->route('admin.brands')->with(['error' => 'هذا الماركة غير موجود']);

            $brand->delete();
            return redirect()->route('admin.brands')->with(['error' => 'تم الحذف بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->route('admin.brands  ')->with(['error' => 'حدث خطأ ما يرجى المحاولة لاحقا']);


        }
    }
}
