<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\MainCategoryRequest;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainCategoriesController extends Controller
{
    public function index()
    {

        $categories = Category::with('_parent')->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.categories.index', compact('categories'));
    }


//    public function create()
//    {
//        $categories = Category::select('id', 'parent_id')->get();
//        return view('dashboard.categories.create',compact($categories));
//
//    }


    public function create()
    {
        $categories = Category::select('id','parent_id')
//            ->where('parent_id', NULL) // الاقسام الرئيسية
            ->get();

        return view('dashboard.categories.create', compact('categories'));
    }


    public function store(MainCategoryRequest $request)
    {
//        dd($request);
//        return $request;
        try {
            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            if($request -> type == CategoryType::mainCategory )
            {
                $request->request->add(['parent_id' => null]);
            }




            $category = Category::create($request->except('_token'));
            $category->name = $request->name;
//            return $category;
            $category->save();
            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الإضافة بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما يرجى المحاولة لاحقا']);
        }

    }

    public function edit($id)
    {
        $category = Category::find($id);


        if (!$category) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);
        }
        return view('dashboard.categories.edit', compact('category'));
    }


    public function update($id, MainCategoryRequest $request)
    {

        try {


            $category = Category::find($id);

            if (!$category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $category->update($request->all());

            $category->name = $request->name;
            $category->save();


            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما يرجى المحاولة لاحقا']);

        }
    }


    public function destroy($id)
    {
        try {

            $category = Category::find($id);

            if (!$category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            $category->delete();
            return redirect()->route('admin.maincategories')->with(['error' => 'تم الحذف بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما يرجى المحاولة لاحقا']);


        }
    }


}
