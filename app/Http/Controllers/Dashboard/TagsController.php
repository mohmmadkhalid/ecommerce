<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Http\Requests\TagsRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    public function index()
    {

        $tags = Tag::orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.tags.index', compact('tags'));
    }


    public function create()
    {
        return view('dashboard.tags.create');

    }

//          return $category;
    public function store(TagsRequest $request)
    {

            DB::beginTransaction();

            $tag = Tag::create($request->only('slug'));


            $tag->name = $request->name;
            $tag->save();
            DB::commit();

            return redirect()->route('admin.tags')->with(['success' => 'تم الإضافة بنجاح']);

    }

    public function edit($id)
    {
        $tag = Tag::find($id);

//        $tag -> makeVisible(['translations']);
//        return $tag ;


        if (!$tag) {
            return redirect()->route('admin.tags')->with(['error' => 'هذا الماركة غير موجود']);
        }
        return view('dashboard.tags.edit', compact('tag'));
    }


    public function update($id, TagsRequest $request)
    {

        try {


            $tag = Tag::find($id);

            if (!$tag)
                return redirect()->route('admin.tags')->with(['error' => 'هذا الماركة غير موجود']);
            DB::beginTransaction();


            $tag->update($request->except('_token','id'));

            $tag->name = $request->name;
            $tag->save();

            DB::commit();

            return redirect()->route('admin.tags')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.tags    ')->with(['error' => 'حدث خطأ ما يرجى المحاولة لاحقا']);

        }
    }


    public function destroy($id)
    {
        try {

            $tag = Tag::find($id);

            if (!$tag)
                return redirect()->route('admin.tags')->with(['error' => 'هذا الماركة غير موجود']);

            $tag->delete();
            return redirect()->route('admin.tags')->with(['error' => 'تم الحذف بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->route('admin.tags  ')->with(['error' => 'حدث خطأ ما يرجى المحاولة لاحقا']);


        }
    }
}
