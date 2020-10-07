<?php

namespace App\Rlustosa;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GenericImageUpload
{
    public static function store($file, $config, $noCrop = false)
    {

        $fileName = Carbon::now('America/Fortaleza')->format('Ymd_His') . '_' .
            Str::random(3) . '_' .
            rand(100, 999) . '_' .
            Str::random(3) . '.' .
            $file->guessClientExtension();

        $folderToSave = config('upload.' . $config . '.folderToSave');
        $pathInStorage = 'public/images/';

        $file->storeAs(
            $pathInStorage . $folderToSave . '/Original/', $fileName
        );

        $path = $file->storeAs(
            $pathInStorage . $folderToSave, $fileName
        );

        if (config('upload.' . $config . '.width') && $noCrop == false) {

            $complete_path = storage_path() . '/app/' . $path;
            $img = Image::make($complete_path);
            $img->fit(config('upload.' . $config . '.width'), config('upload.' . $config . '.height'));
            $img->save($complete_path, 90);
        }

        return str_replace('public/', 'storage/', $path);
    }

    public static function storeGallery($file, $config, $parentId, $noCrop = false)
    {

        $fileName = Carbon::now('America/Fortaleza')->format('Ymd_His') . '_' .
            Str::random(3) . '_' .
            rand(100, 999) . '_' .
            Str::random(3) . '.' .
            $file->guessClientExtension();

        $folderToSave = config('upload.' . $config . '.folderToSave') . '/gallery_' . $parentId;

        $pathInStorage = 'public/images/';

        $file->storeAs(
            $pathInStorage . $folderToSave . '/Original/', $fileName
        );

        $path = $file->storeAs(
            $pathInStorage . $folderToSave, $fileName
        );

        if (config('upload.' . $config . '.width') && $noCrop == false) {

            $complete_path = storage_path() . '/app/' . $path;
            $img = Image::make($complete_path);
            $img->fit(config('upload.' . $config . '.width'), config('upload.' . $config . '.height'));
            $img->save($complete_path, 90);
        }

        return str_replace('public/', 'storage/', $path);
    }
}
