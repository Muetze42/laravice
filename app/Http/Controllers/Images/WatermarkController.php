<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\AbstractController;
use App\Support\Facades\TempStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Image;

class WatermarkController extends AbstractController
{
    /**
     * Add a watermark to image with Spatie Image package.
     *
     * @throws \Spatie\Image\Exceptions\CouldNotLoadImage
     */
    public function spateImage(Request $request)
    {
        $request->validate([
            'image' => ['required', File::image()],
            'watermark' => ['required', File::image()],
            'position' => ['nullable', Rule::enum(AlignPosition::class)],
            'paddingX' => 'nullable|int',
            'paddingY' => 'nullable|int',
            'paddingInPercent' => 'nullable|bool',
            'width' => 'nullable|int',
            'widthInPercent' => 'nullable|bool',
            'height' => 'nullable|int',
            'heightInPercent' => 'nullable|bool',
            'fit' => ['nullable', Rule::enum(Fit::class)],
            'alpha' => 'nullable|int|between:0,100',
        ]);

        // Todo: Next step^^
        $image = $request->file('image');
        $watermarkImage = $request->file('watermark');
        $path = 'spatie-watermark';

        $imagePath = $image->storeAs($path, filename($image), 'temporary');
        $watermarkImagePath = $watermarkImage->storeAs($path, filename($watermarkImage), 'temporary');

        Image::load(TempStorage::path($imagePath))->watermark(
            watermarkImage: TempStorage::path($watermarkImagePath),
            position: $request->enumD('position', AlignPosition::class, AlignPosition::BottomRight),
            paddingX: $request->integer('positionX'),
            paddingY: $request->integer('paddingY'),
            paddingUnit: $request->boolean('paddingInPercent') ? Unit::Percent : Unit::Pixel,
            width: $request->integer('width'),
            widthUnit: $request->boolean('widthInPercent') ? Unit::Percent : Unit::Pixel,
            height: $request->integer('height'),
            heightUnit: $request->boolean('heightInPercent') ? Unit::Percent : Unit::Pixel,
            alpha: $request->integer('alpha', 100),
        )->save();

        return $this->fileResponse(TempStorage::path($imagePath), $request);
    }
}
