<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ImageHandler {

    /**
     * * Store image in server storage
     * * $id : user/apartment id base on $type
     * * $originalImage: orginal image to be saved
     * * $type: user/apartment
     */

    public static function storeImage($id, $originalImage, $type) {

        // 1) get file extension
        $fileExtension = $originalImage->getClientOriginalExtension();
       
        // 2) create unique filename
        $fileName = $id . Carbon::now()->timestamp . rand(10000000, 90032000) . '.' . $fileExtension;

        // 3) get photo type
        $folder = null;
        if (in_array($type, ['user', 'apartment'])) {
            $folder = '/photo/' . $type;
        }

        // 4) actually save photo on Server storage with those filename and extension
        $path = $originalImage->storeAs($folder, $fileName, ['disk' => 'public']);
        
        // 5) return to path image
        return '/' . $path;
    }

    public static function deleteImage($path) {
        Storage::disk('public')->delete($path);
    }
}