<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\AbstractController;
use App\Services\Images\WebPConverter\ImagickService;
use App\Support\Facades\TempStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class ConvertController extends AbstractController
{
    /**
     * Convert image to WebP using Imagick.
     *
     * @throws \ImagickException
     */
    public function toWepPWithImagick(Request $request)
    {
        $request->validate([
            'image' => ['required', File::image()],
            'quality' => 'nullable|int|between:1,100',
        ]);

        $image = $request->file('image');
        $quality = $request->integer('quality', 80);

        $imagePath = $image->storeAs(
            'webp-converter',
            filename($image),
            'temporary'
        );

        return $this->fileResponse(ImagickService::toWebp(TempStorage::path($imagePath), $quality), $request);
    }
}
