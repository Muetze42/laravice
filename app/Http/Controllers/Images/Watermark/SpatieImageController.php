<?php

namespace App\Http\Controllers\Images\Watermark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Image;

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
        $image = $watermarkImage = 'Todo';

        Image::load($image)->watermark(
            watermarkImage: $watermarkImage,
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
    }
}
