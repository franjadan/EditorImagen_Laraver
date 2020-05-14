<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Spot;
use Intervention\Image\Facades\Image;
use Spatie\Color\Rgba;

class SpotController extends Controller
{
    public function create()
    {
        $spot = new Spot;

        return view('spots.create', [
            'spot' => $spot,
        ]);
    }

    public function store(Request $request)
    {
        if($request->get('text') != null){
            $img = Image::make($request->get('image'));

            if($request->get('obscure') != null){
                $img->fill(public_path('media/spots/images/dark.png'));
            }

            $color = $request->get('color') ?? '#FFFFFF';
            $size = $request->get('size') ?? 24;
   
            $img->text($request->get('text'), $img->width() / 2, $img->height() / 2, function($font) use($size, $color) {  
                $font->file(public_path('fonts/Lobster-Regular.ttf'));
                $font->size($size);
                $font->color($color);
                $font->align('center');
                $font->valign('top');
                $font->angle(0);
            });  

            $pathInfo = pathinfo($request->get('image'));
           
            $img->save(public_path($pathInfo['dirname']) . '/' . $pathInfo['filename'] . '_text.' . $pathInfo['extension']);

            dd('saved image successfully.');

        }


    }

    public function uploadImage(Request $request)
    {
       
        $folderName = 'media/spots/images/';
        $folderPath = public_path($folderName);

        $image_parts = explode(";base64,", $request->upload_image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        $file = $folderPath . $fileName;

        file_put_contents($file, $image_base64);

        $response = array(asset($folderName . $fileName), $folderName . $fileName);

        echo json_encode($response);

    }

    
}
