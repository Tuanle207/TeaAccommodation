<?php

namespace App\Http\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHandler {

    /**
     * * Store image in server storage
     * * $id : user/apartment id base on $type
     * * $originalImage: orginal image to be saved
     * * $type: user/apartment
     */

    public static function storeImage($originalImage, $type) {

        // 1) get file extension
        $fileExtension = $originalImage->getClientOriginalExtension();
       
        // 2) create unique filename
        $fileName =  rand(100320000000, 230920000000) . Carbon::now()->timestamp . '.' . $fileExtension;

        // 3) get photo type
        $folder = null;
        if (in_array($type, ['user', 'apartment', 'comment'])) {
            $folder = public_path() . '/photo/' . $type;
        }

        // 4) minify file size and save photo on Server storage with those filename and extension
        $img = Image::make($originalImage->getRealPath());
        // $img->resize(800, 600, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save($folder . '/' . $fileName);

        $img->save($folder . '/' . $fileName);

        // 5) return to path image
        return 'photo/' . $type . '/' . $fileName;
    }

    public static function deleteImage($path) {
        Storage::disk('public')->delete($path);
    }
}