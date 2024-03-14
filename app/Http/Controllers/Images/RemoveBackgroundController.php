<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\AbstractController;
use App\Services\Images\RemoveBackground\ImglyBackgroundRemovalNodeService;
use App\Support\Facades\TempStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class RemoveBackgroundController extends AbstractController
{
    public function imglyBackgroundRemovalNode(Request $request)
    {
        $request->validate([
            'image' => ['required', File::image()],
            'quality' => 'nullable|numeric|between:0.1,1',
            'format' => 'nullable|in:png,jpg,jpeg,webp',
        ]);

        $image = $request->file('image');
        $quality = $request->float('quality', 1);
        $format = $request->input('format', 'png');

        $md5 = md5_file($image->path());
        $targetPath = $md5 . '-' . $quality . '.' . ext_by_mime($format);
        $target = TempStorage::path('imgly-background-removal-node/' . $targetPath);

        if (!file_exists($target)) {
            $filename = $md5 . '.' . $image->extension();
            if (!TempStorage::fileExists('imgly-background-removal-node/' . $filename)) {
                $image->storeAs('imgly-background-removal-node', $filename, 'temporary');
            }

            $service = new ImglyBackgroundRemovalNodeService(
                TempStorage::relativePath('imgly-background-removal-node/' . $filename),
                TempStorage::relativePath('imgly-background-removal-node/' . $targetPath),
                $format,
                $quality,
            );

            if ($service->failed()) {
                return response()->error($service->output());
            }
        }

        return $this->fileResponse($target, $request);
    }
}
