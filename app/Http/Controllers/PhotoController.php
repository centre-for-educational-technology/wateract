<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $orig_photo = $request['photo'];

        //$orig_file_path = Storage::disk('public')->put('spring-photos', $orig_photo);

        $image = PhotoController::storeImage($request);
        $path = $image['path'];
        $thumbnail = $image['thumbnail'];

        //$orig_file_path = Storage::path($orig_file_path);
        //var_dump($orig_file_path);
        //$resized_photo = PhotoController::imageResizer($orig_photo, 1024, 1024);

        //Storage::disk('public')->delete($orig_file_path);

        //$path = Storage::disk('public')->put('spring-photos', $orig_photo);
        $photo = new Photo();
        $photo->spring_id = null;
        $photo->path = $path;
        $photo->thumbnail = $thumbnail;
        $photo->caption = '';
        $photo->save();
        return response()->json(['error'=>false, 'photo_id'=>$photo->id]);
    }

    public function storeImage($request) {
        // Get file from request
        $file = $request->file('photo');
        // Get filename with extension
        $filenameWithExt = $file->getClientOriginalName();
        // Get file path
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Remove unwanted characters
        $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
        $filename = preg_replace("/\s+/", '-', $filename);
        // Get the original image extension
        $extension = $file->getClientOriginalExtension();
        // Create unique file name
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        $thumbnailFileNameToStore = '300_200_'.$filename.'_'.time().'.'.$extension;
        // Refer image to method resizeImage
        $path = $this->resizeImage($file, $fileNameToStore);
        $thumbnail = $this->thumbnailImage($file, $thumbnailFileNameToStore);
        return array('path' => $path, 'thumbnail' => $thumbnail);
    }

    /**
     * Resizes a image using the InterventionImage package.
     *
     * @param object $file
     * @param string $fileNameToStore
     * @return bool
     */
    public function resizeImage(object $file, string $fileNameToStore) {
        // Resize image
        $resize = Image::make($file)->orientate()->resize(2048, 2048, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg');

        // Create hash value
        $hash = md5($resize->__toString());

        // Prepare qualified image name
        $image = $hash."jpg";

        // Put image to storage
        $save = Storage::put("public/spring-photos/{$fileNameToStore}", $resize->__toString());

        if($save) {
            return "spring-photos/{$fileNameToStore}";
        }
        return false;
    }

    public function thumbnailImage(object $file, string $fileNameToStore) {
        // Resize image
        $resize = Image::make($file)->orientate()->fit(300, 200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg');

        // Create hash value
        $hash = md5($resize->__toString());

        // Prepare qualified image name
        $image = $hash."jpg";

        // Put image to storage
        $save = Storage::put("public/spring-photos/{$fileNameToStore}", $resize->__toString());

        if($save) {
            return "spring-photos/{$fileNameToStore}";
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Photo $photo
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Photo $photo)
    {
        var_dump('delete photo');
        $path = Storage::disk('public')->delete($photo->path);
        $photo->delete();
        var_dump($photo->id);
        exit;
    }
}
