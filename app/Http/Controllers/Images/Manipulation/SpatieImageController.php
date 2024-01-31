<?php

namespace App\Http\Controllers\Images\Manipulation;

use App\Http\Controllers\Controller;
use App\Rules\SpatieImageManipulationsRule;
use App\Services\Images\Manipulation\SpatieImageService;
use App\Support\Facades\TempStorage;
use Illuminate\Http\Request;
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

        $actions = $request->input('action');
        $image = $request->file('image');

        $path = 'spatie-image-manipulation';
        $filename = filename($image);

        $imagePath = $image->storeAs($path, $filename, 'temporary');

        new SpatieImageService(TempStorage::path($imagePath), $actions);

        return $this->fileResponse(TempStorage::path($imagePath));
    }
}
