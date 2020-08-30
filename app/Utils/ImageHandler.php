<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ImageHandler {

    public static function storeImage($id, $originalImage, $type) {

        // 1) get file extension
        $fileExtension = $originalImage->getClientOriginalExtension();

        // 2) create unique filename
        $fileName = $id . Carbon::now()->timestamp . rand(0, 10032000) . '.' . $fileExtension;

        // 3) get photo type
        $folder = null;
        if (in_array($type, ['user', 'apartment'])) {
            $folder = '/photo/' . $type;
        }
        // check if the type is invalid
        if ($folder == null) {
            return null;
        }

        // 4) actually create save with those filename and extension
        $path = $originalImage->storeAs($folder, $fileName, ['disk' => 'public']);
        
        // return to path image
        return '/' . $path;
    }

    public static function deleteImage($path) {
        Storage::disk('public')->delete($path);
    }
}