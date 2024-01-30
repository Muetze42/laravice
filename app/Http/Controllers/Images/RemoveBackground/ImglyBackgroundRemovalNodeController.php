<?php

namespace App\Http\Controllers\Images\RemoveBackground;

use App\Http\Controllers\Controller;
use App\Services\Images\RemoveBackground\ImglyBackgroundRemovalNodeService;
use App\Support\Facades\TempStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class ImglyBackgroundRemovalNodeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'image' => ['required', File::image()],
            //'quality' => 'nullable|numeric|between:0.01,1',
            'format' => 'nullable|in:png,jpg,jpeg,webp',
        ]);

        $image = $request->file('image');
        //$quality = $request->float('quality', 1);
        $format = $request->input('format', 'png');

        $filename = md5_file($image->path()) . '.' . $image->extension();
        if (!TempStorage::fileExists($filename)) {
            $image->storeAs('', $filename, 'temporary');
        }

        $service = new ImglyBackgroundRemovalNodeService(TempStorage::relativePath($filename), $format);

        if ($service->failed()) {
            return response()->error($service->output());
        }

        return $this->fileResponse(base_path($service->targetPath()), $request);
    }
}
