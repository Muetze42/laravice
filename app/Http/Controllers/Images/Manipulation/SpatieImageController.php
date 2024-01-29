<?php

namespace App\Http\Controllers\Images\Manipulation;

use App\Http\Controllers\Controller;
use App\Rules\SpatieImageManipulationsRule;
use App\Services\Images\Manipulation\SpatieImageService;
use App\Support\Facades\TempStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class SpatieImageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws \Spatie\Image\Exceptions\CouldNotLoadImage
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'image' => ['required', File::image()],
            'action' => ['required', new SpatieImageManipulationsRule()],
        ]);

        $image = $request->file('image');
        $path = 'spatie-image-manipulation';
        $filename = Str::slug(Str::uuid()) . '.' . $image->extension();

        $image->storeAs($path, $filename, 'temporary');

        $filePath = TempStorage::path($path . DIRECTORY_SEPARATOR . $filename);

        new SpatieImageService($filePath, $request->input('action'));

        return $this->fileResponse($filePath);
    }
}
