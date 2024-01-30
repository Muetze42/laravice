<?php

namespace App\Http\Controllers\Images\WebPConverter;

use App\Http\Controllers\Controller;
use App\Services\Images\WebPConverter\ImagickService;
use App\Support\Facades\TempStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class ImagickController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws \ImagickException
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'image' => ['required', File::image()],
            'quality' => 'nullable|int|between:1,100',
        ]);

        $image = $request->file('image');
        $quality = $request->integer('quality', 80);

        $filename = md5_file($image->path()) . '.' . $image->extension();
        if (!TempStorage::fileExists($filename)) {
            $image->storeAs('', $filename, 'temporary');
        }

        return $this->fileResponse(ImagickService::toWebp($filename, $quality));
    }
}
