<?php

namespace Modules\Gallery\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Base\Entities\Photo;
use Modules\Gallery\Entities\Gallery;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $items = Gallery::all();

        return view('gallery::index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('gallery::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lang' => 'required',
            'brand_id' => 'required',
            'title' => 'required',
        ]);
        try {
            $gallery = Gallery::create([
                'lang' => $request->lang,
                'brand_id' => $request->brand_id,
                'title' => $request->title,
                'image' => (isset($request->image)?file_store($request->image, 'assets/uploads/photos/gallery_image/','photo_'):null),
            ]);

            if (isset($request->photos)){
                foreach ($request->photos as $photo){
                    $ph = new Photo();
                    $ph->path = file_store($photo, 'assets/uploads/photos/galleries/','photo_');
                    $gallery->photo()->save($ph);
                }
            }

            return redirect()->route('gallery.index')->with('flash_message', 'با موفقیت ثبت شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('gallery::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Gallery $gallery)
    {
        return view('gallery::edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Gallery $gallery)
    {
        try {
            if ($request->lang) {
                $gallery->lang = $request->lang;
            }
            if ($request->brand_id) {
                $gallery->brand_id = $request->brand_id;
            }
            $gallery->title = $request->title;
            if (isset($request->image)) {
                if ($gallery->image){
                    File::delete($gallery->image);
                }
                $gallery->image = file_store($request->image, 'assets/uploads/photos/gallery_image/','photo_');
            }

            $gallery->save();

            if (isset($request->photos)){
                foreach ($request->photos as $photo){
                    $ph = new Photo();
                    $ph->path = file_store($photo, 'assets/uploads/photos/galleries/','photo_');
                    $gallery->photo()->save($ph);
                }
            }

            return redirect()->route('gallery.index')->with('flash_message', 'با موفقیت بروزرسانی شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Gallery $gallery)
    {
        try {
            $gallery->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function photo_delete($id)
    {
        $photo = Photo::findOrFail($id);
        try {
            $photo->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
