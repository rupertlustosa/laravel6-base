<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class ImageController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index($folder, $width, $filename)
    {

        $path = 'public/images/' . $folder . '/Original/' . $filename;
        $file = Storage::get($path);

        return Image::make($file)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        })->response();
    }
}
